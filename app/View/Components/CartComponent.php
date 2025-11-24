<?php

namespace App\View\Components;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartComponent extends Component
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
        $setting = Setting::first();
        $minimum_order_amount_door_to_door = $setting ? $setting->minimum_order : 0;
        $minimum_order_amount_pickup = $setting ? $setting->minimum_order_pickup : 0;
        $minimum_order_misc = $setting ? $setting->minimum_order_misc : 0;
        return view('components.cart-component', [
            'minimum_order_amount_door_to_door' => $minimum_order_amount_door_to_door,
            'minimum_order_amount_pickup' => $minimum_order_amount_pickup,
            'minimum_order_misc' => $minimum_order_misc,
        ]);
    }
}
