<?php

namespace App\View\Components\Forms\Bs4\Horizontal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Password extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public array $inputAttributes = [],
        public ?string $label = null,
        public string $helpText = 'default',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.bs4.horizontal.password');
    }
}
