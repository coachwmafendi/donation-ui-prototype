<?php

namespace App\Livewire;

use App\Models\Campaign;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    // Create modal
    public bool $showCreateModal = false;

    public string $createMode = 'new';

    public ?string $cloneSourceId = null;

    public string $newCampaignName = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedCloneSourceId(): void
    {
        if ($this->cloneSourceId) {
            $source = Campaign::find($this->cloneSourceId);
            if ($source) {
                $this->newCampaignName = $source->name.' Copy';
            }
        } else {
            $this->newCampaignName = '';
        }
    }

    public function openCreateModal(): void
    {
        $this->reset(['createMode', 'cloneSourceId', 'newCampaignName']);
        $this->createMode = 'new';
        $this->showCreateModal = true;
    }

    public function createCampaign(): void
    {
        if ($this->createMode === 'new') {
            $this->validate([
                'newCampaignName' => 'required|string|max:255',
            ]);

            $campaign = Campaign::create([
                'name' => $this->newCampaignName,
                'slug' => $this->generateUniqueSlug($this->newCampaignName),
                'status' => 'active',
                'goal_amount_cents' => 0,
                'description' => null,
                'start_date' => null,
                'end_date' => null,
                'currency' => 'USD',
                'settings' => null,
            ]);
        } else {
            $this->validate([
                'cloneSourceId' => 'required|exists:campaigns,id',
                'newCampaignName' => 'required|string|max:255',
            ]);

            $source = Campaign::find($this->cloneSourceId);

            $campaign = Campaign::create([
                'name' => $this->newCampaignName,
                'slug' => $this->generateUniqueSlug($this->newCampaignName),
                'status' => $source->status,
                'goal_amount_cents' => $source->goal_amount_cents,
                'description' => $source->description,
                'start_date' => $source->start_date,
                'end_date' => $source->end_date,
                'currency' => $source->currency,
                'settings' => $source->settings,
            ]);
        }

        $this->showCreateModal = false;
        $this->reset(['createMode', 'cloneSourceId', 'newCampaignName']);
        $this->dispatch('toast', message: 'Campaign created.', type: 'success');
        $this->redirect(route('campaigns.edit', $campaign), navigate: true);
    }

    protected function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (Campaign::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function render()
    {
        $query = Campaign::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('public_id', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.campaign-index', [
            'campaigns' => $query->paginate(10),
            'existingCampaigns' => Campaign::orderBy('name')->get(['id', 'name']),
        ])->layout('components.layouts.admin');
    }
}
