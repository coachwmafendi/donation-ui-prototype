<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class CampaignEdit extends Component
{
    public Campaign $campaign;

    public string $name = '';

    public string $slug = '';

    public string $status = 'active';

    public ?float $goalAmount = null;

    public string $description = '';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public string $currency = 'USD';

    public bool $showDeleteModal = false;

    public bool $showCancelDialog = false;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
        $this->name = $campaign->name;
        $this->slug = $campaign->slug;
        $this->status = $campaign->status;
        $this->goalAmount = $campaign->goal_amount_cents / 100;
        $this->description = $campaign->description ?? '';
        $this->startDate = $campaign->start_date?->format('Y-m-d');
        $this->endDate = $campaign->end_date?->format('Y-m-d');
        $this->currency = $campaign->currency;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:campaigns,slug,'.$this->campaign->id,
            'status' => 'required|in:active,paused,archived',
            'goalAmount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'currency' => 'required|string|size:3',
        ]);

        $this->campaign->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'status' => $validated['status'],
            'goal_amount_cents' => (int) round($validated['goalAmount'] * 100),
            'description' => $validated['description'] ?: null,
            'start_date' => $validated['startDate'] ?: null,
            'end_date' => $validated['endDate'] ?: null,
            'currency' => strtoupper($validated['currency']),
        ]);

        $this->dispatch('toast', message: 'Campaign updated successfully.', type: 'success');
        $this->redirect('/campaigns/'.$this->campaign->public_id, navigate: true);
    }

    public function delete(): void
    {
        $this->campaign->delete();
        $this->dispatch('toast', message: 'Campaign deleted.', type: 'success');
        $this->redirect('/campaigns', navigate: true);
    }

    public function render()
    {
        return view('livewire.campaign-edit')->layout('components.layouts.admin');
    }
}
