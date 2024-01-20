<?php

namespace App\View\Components\shared;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class settings extends Component
{
    public $user;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->user=auth()->user();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared.settings');
    }
}
