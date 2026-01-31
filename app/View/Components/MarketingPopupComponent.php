<?php

namespace App\View\Components;

use App\Models\PopupMessage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;


class MarketingPopupComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {   
        $url = request()->url();
        $now = Carbon::now();

        $popupMessage = PopupMessage::where('is_active', 1)
            ->where('url', 'LIKE', "%{$url}%")
            ->where(function ($query) use ($now) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('expire_at')
                    ->orWhere('expire_at', '>=', $now);
            })
            ->first();

        if (!$popupMessage) {
            return '';
        }

        return view('components.marketing-popup-component', [
            'popupMessage' => $popupMessage,
            'delay' => $popupMessage?->start_to_show ?? 0,
        ]);
    }
}
