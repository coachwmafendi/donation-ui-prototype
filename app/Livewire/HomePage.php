<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        return view('livewire.home-page', [
            'campaigns' => Campaign::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get(),
        ])->layout('components.layouts.public');
    }
}
