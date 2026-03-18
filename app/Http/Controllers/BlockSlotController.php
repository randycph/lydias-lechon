<?php

namespace App\Http\Controllers;

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
                'categories:id,name'
            ])
            ->orderBy('date')
            ->get();

        // Group by full block signature (including pivot sets)
        $groups = $blocks->groupBy(function ($b) {

            $productIds = $b->products->pluck('id')->sort()->implode(',');
            $categoryIds = $b->categories->pluck('id')->sort()->implode(',');

            return implode('|', [
                $b->scope,
                $b->block_type,
                $b->is_all_day,
                $b->start_time,
                $b->end_time,
                $productIds,
                $categoryIds,
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
            'scope' => 'required|in:all,category,product',
            'block_type' => 'required|in:both,delivery,pickup',

            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:product_categories,id',

            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',

            'dates' => 'required|array|min:1',
            'dates.*' => 'date',

            'is_all_day' => 'required|boolean',

            'times' => 'nullable|array',
            'times.*.start' => 'required_if:is_all_day,false|date_format:H:i',
            'times.*.end' => 'required_if:is_all_day,false|date_format:H:i',
        ]);

        DB::transaction(function () use ($validated) {

            $groupId = (string) Str::uuid();

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
                    ]);

                    // Attach products
                    if (!empty($validated['product_ids'])) {
                        $blockedSlot->products()->attach($validated['product_ids']);
                    }

                    // Attach categories
                    if (!empty($validated['category_ids'])) {
                        $blockedSlot->categories()->attach($validated['category_ids']);
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
                    ]);

                    if (!empty($validated['product_ids'])) {
                        $blockedSlot->products()->attach($validated['product_ids']);
                    }

                    if (!empty($validated['category_ids'])) {
                        $blockedSlot->categories()->attach($validated['category_ids']);
                    }
                }
            }
        });


        return response()->json([
            'message' => 'Blocked dates saved successfully'
        ], 201);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $blocks = BlockedSlot::where('group_id', $id)->get();

            foreach ($blocks as $block) {
                $block->products()->detach();
                $block->categories()->detach();
                $block->delete();
            }
        });

        return response()->json([
            'message' => 'Block group deleted successfully'
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
                'scope' => $b['scope'],
                'block_type' => $b['block_type'],
                'start_time' => $b['start_time'],
                'end_time' => $b['end_time'],
                'is_all_day' => $b['is_all_day'],
                'products' => $b['products'],
                'categories' => $b['categories'],
            ]
        ];
    }

    public function getCheckoutBlocks(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $productIds = $validated['product_ids'];

        // Get category IDs from cart products
        $categoryIds = Product::whereIn('id', $productIds)
            ->pluck('category_id')
            ->unique()
            ->values()
            ->toArray();

        $today = Carbon::today()->toDateString();

        $blocks = BlockedSlot::with([
                'products:id',
                'categories:id'
            ])
            ->whereDate('date', '>=', $today)
            ->where(function ($query) use ($productIds, $categoryIds) {

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
            ]);

        return response()->json($blocks);
    }
}
