<?php

namespace App\View\Components\shared\form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextArea extends Component
{
    public $name, $placeholder;
    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $placeholder= "")
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared.form.text-area');
    }
}
