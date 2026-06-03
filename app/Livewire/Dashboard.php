<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $now = now();
        $thirtyDaysAgo = now()->subDays(30);

        // Stats
        $totalDonations = Donation::count();
        $totalRaised = Donation::where('status', 'succeeded')->sum('amount_cents');
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $newDonors = Donation::where('created_at', '>=', $thirtyDaysAgo)->count();

        // Recent donations (last 5)
        $recentDonations = Donation::with('profile')
            ->orderBy('donation_date', 'desc')
            ->limit(5)
            ->get();

        // Daily trend (last 14 days)
        $trendDays = collect(range(0, 13))->map(function ($daysAgo) use ($now) {
            $date = $now->copy()->subDays($daysAgo);
            return [
                'label' => $date->format('D'),
                'date' => $date->format('M d'),
                'amount' => Donation::where('status', 'succeeded')
                    ->whereDate('donation_date', $date->format('Y-m-d'))
                    ->sum('amount_cents'),
            ];
        })->reverse()->values();

        // Top campaigns by raised amount
        $topCampaigns = Campaign::orderByDesc('raised_amount_cents')
            ->limit(4)
            ->get();

        return view('livewire.dashboard', [
            'stats' => [
                ['label' => 'Total donations', 'value' => number_format($totalDonations), 'trend' => '+12%', 'trendUp' => true],
                ['label' => 'Total raised', 'value' => '$' . number_format($totalRaised / 100, 0), 'trend' => '+8%', 'trendUp' => true],
                ['label' => 'Active campaigns', 'value' => number_format($activeCampaigns), 'trend' => null],
                ['label' => 'New donors (30d)', 'value' => number_format($newDonors), 'trend' => '+23%', 'trendUp' => true],
            ],
            'recentDonations' => $recentDonations,
            'trend' => $trendDays,
            'topCampaigns' => $topCampaigns,
        ])->layout('components.layouts.admin');
    }
}
