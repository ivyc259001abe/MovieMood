<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $popularMovies;

    /**
     * Create a new component instance.
     */
    public function __construct($popularMovies = [])
    {
        $this->popularMovies = $popularMovies;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}