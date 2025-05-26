<?php

namespace App\View\Components;

use App\Models\PopupMessage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;


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
        $routeName = Route::currentRouteName();
        $popupMessage = PopupMessage::where('is_active', 1)
            ->where('url', $routeName)
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
