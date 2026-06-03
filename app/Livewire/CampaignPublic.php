<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class CampaignPublic extends Component
{
    public Campaign $campaign;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign->loadCount('donations');
    }

    public function render()
    {
        return view('livewire.campaign-public', [
            'recentDonations' => $this->campaign->donations()
                ->with('profile')
                ->latest()
                ->limit(10)
                ->get(),
        ])->layout('components.layouts.public');
    }
}
