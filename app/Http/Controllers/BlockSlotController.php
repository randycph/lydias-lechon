<?php

namespace App\Http\Controllers;

use App\EcommerceModel\Branch;
use App\Models\BlockedSlot;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlockSlotController extends Controller
{
    public function events()
    {
        $blocks = BlockedSlot::with([
                'products:id,name',
                'categories:id,name',
                'comboProducts:id,name',
                'locations:id,name'
            ])
            ->orderBy('date')
            ->get();

        // Group by full block signature (including pivot sets)
        $groups = $blocks->groupBy(function ($b) {

            $productIds = $b->products->pluck('id')->sort()->implode(',');
            $categoryIds = $b->categories->pluck('id')->sort()->implode(',');
            $locationIds = $b->locations->pluck('id')->sort()->implode(',');
            $comboIds = $b->comboProducts->pluck('id')->sort()->implode(',');

            return implode('|', [
                $b->scope,
                $b->block_type,
                $b->is_all_day,
                $b->start_time,
                $b->end_time,
                $productIds,
                $categoryIds,
                $locationIds,
                $comboIds
            ]);
        });

        $events = [];

        // Process each group independently
        foreach ($groups as $group) {

            $group = $group->sortBy('date')->values();

            $current = null;

            foreach ($group as $block) {

                if (
                    $current &&
                    \Carbon\Carbon::parse($current['end'])
                        ->addDay()
                        ->toDateString() === $block->date
                ) {
                    // Extend date range
                    $current['end'] = $block->date;
                } else {

                    if ($current) {
                        $events[] = $this->formatEvent($current);
                    }

                    $current = [
                        'id' => $block->id,
                        'group_id' => $block->group_id,
                        'scope' => $block->scope,
                        'block_type' => $block->block_type,
                        'start' => $block->date,
                        'end' => $block->date,
                        'start_time' => $block->start_time,
                        'end_time' => $block->end_time,
                        'is_all_day' => $block->is_all_day,
                        'products' => $block->products,
                        'categories' => $block->categories,
                        'combo_products' => $block->comboProducts,
                        'locations' => $block->locations
                    ];
                }
            }

            if ($current) {
                $events[] = $this->formatEvent($current);
            }
        }

        return $events;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scope' => 'required|in:all,category,product,location',
            'block_type' => 'required|in:both,delivery,pickup',

            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:product_categories,id',

            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',

            'location_ids' => 'nullable|array',
            'location_ids.*' => 'integer|exists:branches,id',

            'combo_product_ids' => 'nullable|array',
            'combo_product_ids.*' => 'integer|exists:products,id',

            'dates' => 'required|array|min:1',
            'dates.*' => 'date',

            'is_all_day' => 'required|boolean',

            'times' => 'nullable|array',
            'times.*.start' => 'required_if:is_all_day,false|date_format:H:i',
            'times.*.end' => 'required_if:is_all_day,false|date_format:H:i',

            'combo_product_ids' => 'nullable|array',
            'combo_product_ids.*' => 'integer|exists:products,id',

            'date_mode' => 'required|in:range,multiple',
        ]);

        DB::transaction(function () use ($validated) {

            $groupId = (string) Str::uuid();

            $scope = $validated['scope'];

            // FILTER BASED ON SCOPE
            $productIds  = $validated['product_ids'] ?? [];
            $categoryIds = $validated['category_ids'] ?? [];
            $locationIds = $validated['location_ids'] ?? [];
            $comboProductIds = $validated['combo_product_ids'] ?? [];

            if ($scope === 'category') {
                $productIds = [];
                $locationIds = [];
            }

            if ($scope === 'product') {
                $categoryIds = [];
                $locationIds = [];
            }

            if ($scope === 'all') {
                $productIds = [];
                $categoryIds = [];
                $locationIds = [];
            }
            
            foreach ($validated['dates'] as $date) {

                if ($validated['is_all_day']) {

                    $blockedSlot = BlockedSlot::create([
                        'scope'       => $validated['scope'],
                        'block_type'  => $validated['block_type'],
                        'date'        => $date,
                        'start_time'  => null,
                        'end_time'    => null,
                        'is_all_day'  => true,
                        'group_id'    => $groupId,
                        'date_mode'   => $validated['date_mode']
                    ]);

                    // Attach products
                    if (!empty($productIds)) {
                        $blockedSlot->products()->sync($productIds);
                    }

                    // Attach categories
                    if (!empty($categoryIds)) {
                        $blockedSlot->categories()->sync($categoryIds);
                    }
                    
                    // Attach combo products
                    if (!empty($comboProductIds)) {
                        $blockedSlot->comboProducts()->sync($comboProductIds);
                    }

                    // Attach locations
                    if (!empty($locationIds)) {
                        $blockedSlot->locations()->sync($locationIds);
                    }

                    continue;
                }

                foreach ($validated['times'] as $time) {

                    $blockedSlot = BlockedSlot::create([
                        'scope'       => $validated['scope'],
                        'block_type'  => $validated['block_type'],
                        'date'        => $date,
                        'start_time'  => $time['start'],
                        'end_time'    => $time['end'],
                        'is_all_day'  => false,
                        'group_id'    => $groupId,
                        'date_mode'   => $validated['date_mode']
                    ]);

                    if (!empty($productIds)) {
                        $blockedSlot->products()->sync($productIds);
                    }

                    if (!empty($categoryIds)) {
                        $blockedSlot->categories()->sync($categoryIds);
                    }

                    if (!empty($locationIds)) {
                        $blockedSlot->locations()->sync($locationIds);
                    }

                    if (!empty($comboProductIds)) {
                        $blockedSlot->comboProducts()->sync($comboProductIds);
                    }
                }
            }
        });


        return response()->json([
            'message' => 'Blocked dates saved successfully'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'scope' => 'required|in:all,category,product,location',
            'block_type' => 'required|in:both,delivery,pickup',
            'is_all_day' => 'required|boolean',

            'product_ids' => 'nullable|array',
            'category_ids' => 'nullable|array',
            'location_ids' => 'nullable|array',
            'combo_product_ids' => 'nullable|array',

            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        $block = BlockedSlot::findOrFail($id);

        $scope = $validated['scope'];

        // FILTER BASED ON SCOPE
        $productIds  = $validated['product_ids'] ?? [];
        $categoryIds = $validated['category_ids'] ?? [];
        $locationIds = $validated['location_ids'] ?? [];

        if ($scope === 'category') {
            $productIds = [];
            $locationIds = [];
        }

        if ($scope === 'product') {
            $categoryIds = [];
            $locationIds = [];
        }

        if ($scope === 'all') {
            $productIds = [];
            $categoryIds = [];
            $locationIds = [];
        }

        // UPDATE BLOCK
        $block->update([
            'scope' => $scope,
            'block_type' => $validated['block_type'],
            'is_all_day' => $validated['is_all_day'],
            'start_time' => $validated['is_all_day'] ? null : ($validated['start_time'] ?? null),
            'end_time' => $validated['is_all_day'] ? null : ($validated['end_time'] ?? null),
        ]);

        // SYNC RELATIONS
        $block->products()->sync($productIds);
        $block->categories()->sync($categoryIds);
        $block->locations()->sync($locationIds);
        $block->comboProducts()->sync($validated['combo_product_ids'] ?? []);

        return response()->json([
            'message' => 'Block updated successfully',
            'data' => $block->load([
                'products:id,name',
                'categories:id,name',
                'locations:id,name',
                'comboProducts:id,name'
            ])
        ]);
    }

    public function destroy(Request $request, $groupId)
    {
        DB::transaction(function () use ($request, $groupId) {

            $query = BlockedSlot::where('group_id', $groupId);

            // If specific date clicked
            if ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            }

            // If specific time clicked (NOT all-day)
            if ($request->filled('start_time') && $request->filled('end_time')) {
                $query->where('start_time', $request->start_time)
                    ->where('end_time', $request->end_time);
            }

            $blocks = $query->get();

            foreach ($blocks as $block) {
                $block->products()->detach();
                $block->categories()->detach();
                $block->locations()->detach();
                $block->comboProducts()->detach();
                $block->delete();
            }
        });

        return response()->json([
            'message' => 'Block deleted successfully'
        ]);
    }

    public function destroyMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $monthStart = Carbon::createFromFormat('Y-m', $request['month'])->startOfMonth()->toDateString();
        $monthEnd = Carbon::createFromFormat('Y-m', $request['month'])->endOfMonth()->toDateString();

        DB::transaction(function () use ($monthStart, $monthEnd) {

            $blocks = BlockedSlot::whereBetween('date', [$monthStart, $monthEnd])->get();

            foreach ($blocks as $block) {
                $block->products()->detach();
                $block->categories()->detach();
                $block->locations()->detach();
                $block->comboProducts()->detach();
                $block->delete();
            }
        });

        return response()->json([
            'message' => 'Blocks for the month deleted successfully'
        ]);
    }

    private function formatEvent($b)
    {
        return [
            'id' => $b['id'],
            'title' => strtoupper($b['scope']) . ' BLOCKED',

            'start' => $b['is_all_day']
                ? $b['start']
                : $b['start'] . 'T' . $b['start_time'],

            'end' => $b['is_all_day']
                ? \Carbon\Carbon::parse($b['end'])->addDay()->toDateString()
                : $b['end'] . 'T' . $b['end_time'],

            'allDay' => $b['is_all_day'],

            'extendedProps' => [
                'group_id' => $b['group_id'],

                'date_start' => $b['start'],
                'date_end'   => $b['end'],

                'is_range' => $b['start'] !== $b['end'],

                'scope' => $b['scope'],
                'block_type' => $b['block_type'],
                'start_time' => $b['start_time'],
                'end_time' => $b['end_time'],
                'is_all_day' => $b['is_all_day'],
                'products' => $b['products'],
                'categories' => $b['categories'],
                'locations' => $b['locations'],
                'combo_products' => $b['combo_products']
            ],
        ];
    }

    public function getCheckoutBlocks(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',

            'location' => 'nullable|string',
        ]);

        $productIds = $validated['product_ids'];
        $location = $validated['location'] ?? null;

        // Get category IDs from cart products
        $categoryIds = Product::whereIn('id', $productIds)
            ->pluck('category_id')
            ->unique()
            ->values()
            ->toArray();

        $today = Carbon::today()->toDateString();

        $branch = Branch::where('name', $location)->first();
        $locationIds = $branch ? [$branch->id] : [];

        $blocks = BlockedSlot::with([
                'products:id',
                'categories:id',
                'comboProducts:id',
                'locations:id,name'
            ])
            ->whereDate('date', '>=', $today)
            ->where(function ($query) use ($productIds, $categoryIds, $locationIds) {

                // ALL scope
                $query->where('scope', 'all')

                // PRODUCT scope
                ->orWhere(function ($q) use ($productIds) {
                    $q->where('scope', 'product')
                    ->whereHas('products', function ($sub) use ($productIds) {
                        $sub->whereIn('products.id', $productIds);
                    });
                })

                // CATEGORY scope
                ->orWhere(function ($q) use ($categoryIds) {
                    $q->where('scope', 'category')
                    ->whereHas('categories', function ($sub) use ($categoryIds) {
                        $sub->whereIn('product_categories.id', $categoryIds);
                    });
                })

                // LOCATION scope
                ->orWhere(function ($q) use ($locationIds) {
                    $q->where('scope', 'location')
                    ->whereHas('locations', function ($sub) use ($locationIds) {
                        $sub->whereIn('branches.id', $locationIds);
                    });
                });

            })
            ->orderBy('date')
            ->get([
                'id',
                'date',
                'scope',
                'block_type',
                'is_all_day',
                'start_time',
                'end_time',
            ])
            ->filter(function ($block) use ($productIds, $categoryIds, $locationIds) {

                $matchProduct = $block->products->isEmpty()
                    || $block->products->pluck('id')->intersect($productIds)->isNotEmpty();

                $matchCategory = $block->categories->isEmpty()
                    || $block->categories->pluck('id')->intersect($categoryIds)->isNotEmpty();

                $matchLocation = $block->locations->isEmpty()
                    || $block->locations->pluck('id')->intersect($locationIds)->isNotEmpty();

                $hasCombo = $block->comboProducts->isNotEmpty();

                if ($hasCombo) {

                    $comboMatch = $block->comboProducts
                        ->pluck('id')
                        ->intersect($productIds)
                        ->isNotEmpty();

                    if ($comboMatch) {
                        return false;
                    }
                }
                
                return $matchProduct && $matchCategory && $matchLocation;
            })
            ->values();;

        return response()->json($blocks);
    }

    public function updateSingle(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'group_id' => 'required',
            'start_time' => 'nullable',
            'end_time' => 'nullable',

            'combo_product_ids' => 'nullable|array',
            'combo_product_ids.*' => 'integer|exists:products,id',
        ]);

        DB::transaction(function () use ($validated) {

            $query = BlockedSlot::where('group_id', $validated['group_id'])
                ->whereDate('date', $validated['date']);

            if (!empty($validated['start_time'])) {
                $query->where('start_time', $validated['start_time'])
                    ->where('end_time', $validated['end_time']);
            }

            $blocks = $query->get();

            foreach ($blocks as $block) {

                // combo products
                $block->comboProducts()->sync($validated['combo_product_ids'] ?? []);
            }
        });

        return response()->json(['message' => 'Block updated']);
    }

    public function updateGroup(Request $request, $groupId)
    {
        DB::transaction(function () use ($groupId, $request) {

            $validated = $request->validate([
                'scope' => 'required|in:all,category,product,location',
                'block_type' => 'required|in:both,delivery,pickup',

                'category_ids' => 'nullable|array',
                'product_ids' => 'nullable|array',
                'location_ids' => 'nullable|array',
                'combo_product_ids' => 'nullable|array',

                'dates' => 'required|array',
                'dates.*' => 'date',

                'is_all_day' => 'required|boolean',

                'times' => 'nullable|array',
                'times.*.start' => 'required_if:is_all_day,false',
                'times.*.end' => 'required_if:is_all_day,false',

                'date_mode' => 'required|in:range,multiple',
            ]);

            // DELETE EXISTING GROUP
            $blocks = BlockedSlot::where('group_id', $groupId)->get();

            foreach ($blocks as $block) {
                $block->products()->detach();
                $block->categories()->detach();
                $block->locations()->detach();
                $block->comboProducts()->detach();
                $block->delete();
            }

            // APPLY SCOPE FILTERING
            $scope = $validated['scope'];

            $productIds  = $validated['product_ids'] ?? [];
            $categoryIds = $validated['category_ids'] ?? [];
            $locationIds = $validated['location_ids'] ?? [];
            $comboIds    = $validated['combo_product_ids'] ?? [];

            if ($scope === 'category') {
                $productIds = [];
                $locationIds = [];
            }

            if ($scope === 'product') {
                $categoryIds = [];
                $locationIds = [];
            }

            if ($scope === 'all') {
                $productIds = [];
                $categoryIds = [];
                $locationIds = [];
            }

            // RECREATE BLOCKS
            foreach ($validated['dates'] as $date) {

                // ALL DAY
                if ($validated['is_all_day']) {

                    $block = BlockedSlot::create([
                        'scope'       => $scope,
                        'block_type'  => $validated['block_type'],
                        'date'        => $date,
                        'start_time'  => null,
                        'end_time'    => null,
                        'is_all_day'  => true,
                        'group_id'    => $groupId,
                        'date_mode'   => $validated['date_mode'],
                    ]);

                    $this->syncRelations($block, $productIds, $categoryIds, $locationIds, $comboIds);
                    continue;
                }

                // TIME SLOTS
                foreach ($validated['times'] ?? [] as $time) {

                    $block = BlockedSlot::create([
                        'scope'       => $scope,
                        'block_type'  => $validated['block_type'],
                        'date'        => $date,
                        'start_time'  => $time['start'],
                        'end_time'    => $time['end'],
                        'is_all_day'  => false,
                        'group_id'    => $groupId,
                        'date_mode'   => $validated['date_mode'],
                    ]);

                    $this->syncRelations($block, $productIds, $categoryIds, $locationIds, $comboIds);
                }
            }
        });

        return response()->json(['message' => 'Block updated']);
    }

    private function syncRelations($block, $productIds, $categoryIds, $locationIds, $comboIds)
    {
        $block->products()->sync($productIds);
        $block->categories()->sync($categoryIds);
        $block->locations()->sync($locationIds);
        $block->comboProducts()->sync($comboIds);
    }

    private function attachRelations($blockedSlot, $validated)
    {
        if (!empty($validated['product_ids'])) {
            $blockedSlot->products()->attach($validated['product_ids']);
        }

        if (!empty($validated['category_ids'])) {
            $blockedSlot->categories()->attach($validated['category_ids']);
        }

        if (!empty($validated['location_ids'])) {
            $blockedSlot->locations()->attach($validated['location_ids']);
        }

        if (!empty($validated['combo_product_ids'])) {
            $blockedSlot->comboProducts()->attach($validated['combo_product_ids']);
        }
    }

    public function show($id)
    {
        $block = BlockedSlot::with([
            'products:id,name',
            'categories:id,name',
            'locations:id,name',
            'comboProducts:id,name'
        ])->findOrFail($id);

        return response()->json($block);
    }

    public function showGroup($groupId)
    {
        $blocks = BlockedSlot::with([
            'products:id,name',
            'categories:id,name',
            'locations:id,name',
            'comboProducts:id,name'
        ])
        ->where('group_id', $groupId)
        ->orderBy('date')
        ->get();

        if ($blocks->isEmpty()) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $dates = $blocks->pluck('date')->unique()->values();

        $timeSlots = $blocks
            ->pluck('start_time')
            ->filter()
            ->unique()
            ->values();

        $first = $blocks->first();

        return response()->json([
            'group_id' => $groupId,
            'scope' => $first->scope,
            'block_type' => $first->block_type,
            'is_all_day' => $first->is_all_day,

            'products' => $first->products,
            'categories' => $first->categories,
            'locations' => $first->locations,
            'combo_products' => $first->comboProducts,

            'dates' => $dates,
            'time_slots' => $timeSlots,
            'date_mode' => $first->date_mode
        ]);
    }
    
}
