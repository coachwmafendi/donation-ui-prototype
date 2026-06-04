<?php

namespace App\Livewire;

use App\Models\Campaign;
use Illuminate\Support\Str;
use Livewire\Component;

class CampaignCreate extends Component
{
    public string $name = '';

    public string $slug = '';

    public string $status = 'active';

    public ?float $goalAmount = null;

    public string $description = '';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public string $currency = 'USD';

    public bool $showCancelDialog = false;

    public function updatedName(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:campaigns,slug',
            'status' => 'required|in:active,paused,archived',
            'goalAmount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'currency' => 'required|string|size:3',
        ]);

        $campaign = Campaign::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'status' => $validated['status'],
            'goal_amount_cents' => (int) round($validated['goalAmount'] * 100),
            'description' => $validated['description'] ?: null,
            'start_date' => $validated['startDate'] ?: null,
            'end_date' => $validated['endDate'] ?: null,
            'currency' => strtoupper($validated['currency']),
        ]);

        $this->dispatch('toast', message: 'Campaign created successfully.', type: 'success');
        $this->redirect('/campaigns/'.$campaign->public_id.'/edit', navigate: true);
    }

    public function render()
    {
        return view('livewire.campaign-create')->layout('components.layouts.admin');
    }
}
