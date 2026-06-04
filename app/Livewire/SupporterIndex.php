<?php

namespace App\Livewire;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithPagination;

class SupporterIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Profile::query()
            ->withCount('donations')
            ->withSum('donations', 'amount_cents')
            ->with(['donations' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.supporter-index', [
            'supporters' => $query->paginate(10),
        ])->layout('components.layouts.admin');
    }
}
