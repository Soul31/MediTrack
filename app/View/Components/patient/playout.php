<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Playout extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public $breadcrum;
    public $activePage;
    public function __construct($title, $breadcrum, $activePage)
    {
        $this->breadcrum = $breadcrum;
        $this->title = $title;
        $this->activePage = $activePage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.patient.playout');
    }
}
