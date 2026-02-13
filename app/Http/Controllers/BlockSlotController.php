<?php

namespace App\Http\Controllers;

use App\Models\BlockedSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockSlotController extends Controller
{
    public function events()
    {
        $blocks = BlockedSlot::orderBy('date')->get();

        // 1. Group by block signature
        $groups = $blocks->groupBy(function ($b) {
            return implode('|', [
                $b->scope,
                $b->category_id,
                $b->product_id,
                $b->is_all_day,
                $b->start_time,
                $b->end_time,
                $b->block_type,
            ]);
        });

        $events = [];

        // 2. Process each group independently
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
                    // Extend range
                    $current['end'] = $block->date;
                } else {
                    // Push previous
                    if ($current) {
                        $events[] = $this->formatEvent($current);
                    }

                    // Start new
                    $current = [
                        'id' => $block->id,
                        'scope' => $block->scope,
                        'category_id' => $block->category_id,
                        'product_id' => $block->product_id,
                        'start' => $block->date,
                        'end' => $block->date,
                        'start_time' => $block->start_time,
                        'end_time' => $block->end_time,
                        'is_all_day' => $block->is_all_day,
                        'block_type' => $block->block_type,
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

            'category_id' => 'nullable|required_if:scope,category',
            'product_id'  => 'nullable|required_if:scope,product',

            'dates' => 'required|array|min:1',
            'dates.*' => 'date',

            'is_all_day' => 'required|boolean',

            'times' => 'nullable|array',
            'times.*.start' => 'required_if:is_all_day,false|date_format:H:i',
            'times.*.end'   => 'required_if:is_all_day,false|date_format:H:i',
        ]);

        DB::transaction(function () use ($validated) {

            foreach ($validated['dates'] as $date) {

                // ALL DAY BLOCK
                if ($validated['is_all_day']) {
                    BlockedSlot::create([
                        'scope'       => $validated['scope'],
                        'block_type'  => $validated['block_type'],
                        'category_id' => $validated['category_id'] ?? null,
                        'product_id'  => $validated['product_id'] ?? null,
                        'date'        => $date,
                        'start_time'  => null,
                        'end_time'    => null,
                        'is_all_day'  => true,
                    ]);
                    continue;
                }

                // TIME SLOTS
                foreach ($validated['times'] as $time) {
                    BlockedSlot::create([
                        'scope'       => $validated['scope'],
                        'block_type'  => $validated['block_type'],
                        'category_id' => $validated['category_id'] ?? null,
                        'product_id'  => $validated['product_id'] ?? null,
                        'date'        => $date,
                        'start_time'  => $time['start'],
                        'end_time'    => $time['end'],
                        'is_all_day'  => false,
                    ]);
                }
            }
        });

        return response()->json([
            'message' => 'Blocked dates saved successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $block = BlockedSlot::findOrFail($id);
        $block->delete();

        return response()->json([
            'message' => 'Blocked slot deleted successfully'
        ]);
    }

    public function formatEvent($b)
    {
        return [
            'id' => $b['id'],
            'title' => strtoupper($b['scope']) . ' BLOCKED',
            'block_type' => $b['block_type'],
            'start' => $b['is_all_day']
                ? $b['start']
                : $b['start'] . 'T' . $b['start_time'],
            'end' => $b['is_all_day']
                ? Carbon::parse($b['end'])->addDay()->toDateString()
                : $b['end'] . 'T' . $b['end_time'],
            'allDay' => $b['is_all_day'],

            'extendedProps' => [
                'scope' => $b['scope'],
                'category_id' => $b['category_id'],
                'product_id' => $b['product_id'],
                'start_date' => $b['start'],
                'end_date' => $b['end'],
                'start_time' => $b['start_time'],
                'end_time' => $b['end_time'],
                'is_all_day' => $b['is_all_day'],
            ]
        ];
    }
}
