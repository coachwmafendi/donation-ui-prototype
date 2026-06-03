<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class CampaignShow extends Component
{
    public Campaign $campaign;

    public bool $showArchiveModal = false;

    public array $sections = [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => '◎'],
        ['id' => 'progress', 'label' => 'Progress', 'icon' => '▣'],
        ['id' => 'settings', 'label' => 'Settings', 'icon' => '⚙'],
        ['id' => 'donations', 'label' => 'Donations', 'icon' => '$'],
    ];

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign->loadCount('donations');
    }

    public function archive(): void
    {
        $this->campaign->update(['status' => 'archived']);
        $this->showArchiveModal = false;
        $this->dispatch('archived');
    }

    public function render()
    {
        return view('livewire.campaign-show', [
            'recentDonations' => $this->campaign->donations()
                ->with('profile')
                ->latest()
                ->limit(5)
                ->get(),
        ])->layout('components.layouts.admin');
    }
}
