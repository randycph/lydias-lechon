<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProcessingService
{
    public function resolveMinHours(Collection $carts, $settings): int
    {
        $default = $settings->minimum_processing_hours ?? 24;
        $misc = $settings->minimum_processing_hours_misc ?? 12;
        $baka = $settings->minimum_processing_hours_baka ?? 72;

        foreach ($carts as $cart) {
            $product = $cart->product ?? null;

            if (!$product) continue;

            if ($product->slug === 'lechon-baka') return $baka;
            if ($product->is_misc == 1) return $misc;
        }

        return $default;
    }

    public function validate($date, $time, $minHours): bool
    {
        $requested = Carbon::parse("$date $time");
        return now()->diffInHours($requested, false) >= $minHours;
    }
}