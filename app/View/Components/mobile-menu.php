<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class mobile_menu extends Component
{
    /**
     * Create a new component instance.
     */
    public $breadcrum;
    public function __construct($breadcrum)
    {
        $this->breadcrum = $breadcrum;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mobile-menu');
    }
}
