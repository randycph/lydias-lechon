<?php

namespace App\View\Components;

use App\EcommerceModel\Branch;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HotlineComponent extends Component
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
        $headOffices = Branch::where('status', 1)->where('is_head_office', 1)->get();
        $branches = Branch::with('numbers')->where('status', 1)->where('is_head_office', 0)->get();

        return view('components.hotline-component', [
            'branches' => $branches,
            'headOffices' => $headOffices,
        ]);
    }
}
