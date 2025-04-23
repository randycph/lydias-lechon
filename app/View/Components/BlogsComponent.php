<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BlogsComponent extends Component
{
    public $blogs;

    /**
     * Create a new component instance.
     */
    public function __construct($blogs = null)
    {
        if (is_null($blogs)) {
            $this->blogs = collect();
        } elseif (!is_a($blogs, 'Illuminate\Support\Collection')) {
            $this->blogs = collect($blogs);
        } else {
            $this->blogs = $blogs;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.blogs-component', [
            'blogs' => $this->blogs,
        ]);
    }
}
