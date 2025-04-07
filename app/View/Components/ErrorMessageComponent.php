<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ErrorMessageComponent extends Component
{
    public $inputName;
    /**
     * Create a new component instance.
     */
    public function __construct($inputName)
    {
        $this->inputName = $inputName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.error-message-component');
    }
}
