<?php

namespace App\View\Components\Forms\Bs4\Horizontal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Radios extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public array $radios = [],
        public ?string $value = null,
        public ?string $label = null,
        public array $inputAttributes = [],
        public string $separator = 'inline',
        public ?string $formGroupClasses = null,
        public ?string $formLabelClasses = null,
        public ?string $formItemClasses = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.bs4.horizontal.radios');
    }
}
