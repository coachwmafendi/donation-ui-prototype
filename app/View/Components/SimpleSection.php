<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SimpleSection extends Component
{
    public function __construct(
        public string $id,
        public string $title,
        public string $icon = ''
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.simple-section');
    }
}