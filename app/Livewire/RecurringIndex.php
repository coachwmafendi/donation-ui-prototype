<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class RecurringIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $frequencyFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedFrequencyFilter(): void
    {
        $this->resetPage();
    }

    public function cancel(string $publicId): void
    {
        Donation::where('public_id', $publicId)->update(['status' => 'cancelled']);
    }

    public function pause(string $publicId): void
    {
        Donation::where('public_id', $publicId)->update(['status' => 'paused']);
    }

    public function resume(string $publicId): void
    {
        Donation::where('public_id', $publicId)->update(['status' => 'succeeded']);
    }

    public function render()
    {
        $query = Donation::query()
            ->with('profile', 'campaign')
            ->where('frequency', '!=', 'one-time')
            ->when($this->search, function ($query) {
                $query->whereHas('profile', function ($q) {
                    $q->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->frequencyFilter, function ($query) {
                $query->where('frequency', $this->frequencyFilter);
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.recurring-index', [
            'recurring' => $query->paginate(10),
        ])->layout('components.layouts.admin');
    }
}
