<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BukuCardComponent extends Component
{
    /**
     * Buku model instance
     */
    public $buku;

    /**
     * Show action buttons
     */
    public $showActions;

    /**
     * Create a new component instance.
     */
    public function __construct($buku, $showActions = true)
    {
        $this->buku = $buku;
        $this->showActions = $showActions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buku-card-component');
    }
}
