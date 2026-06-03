<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Campaign::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('public_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.campaign-index', [
            'campaigns' => $query->paginate(10),
        ])->layout('components.layouts.admin');
    }
}
