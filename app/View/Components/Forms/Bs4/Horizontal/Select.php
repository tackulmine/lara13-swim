<?php

namespace App\View\Components\Forms\Bs4\Horizontal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public array|object $options = [],
        public ?string $value = null,
        public ?string $label = null,
        public array $inputAttributes = [],
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.bs4.horizontal.select');
    }
}
