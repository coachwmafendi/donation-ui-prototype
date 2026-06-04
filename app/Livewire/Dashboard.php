<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Donation;
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

        // Daily trend (last 14 days) — format for chart
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

        // Chart data prep
        $maxAmount = max($trendDays->max('amount'), 1);
        $chartHeight = 220;
        $chartWidth = 640;
        $paddingX = 40;
        $paddingY = 30;

        $chartPoints = $trendDays->map(function ($day, $index) use ($maxAmount, $chartWidth, $chartHeight, $paddingX, $paddingY) {
            $x = $paddingX + ($index * ($chartWidth / 13));
            $y = $paddingY + $chartHeight - (($day['amount'] / $maxAmount) * $chartHeight);

            return [
                'x' => round($x, 1),
                'y' => round($y, 1),
                'amount' => $day['amount'],
                'label' => $day['label'],
                'date' => $day['date'],
            ];
        });

        // Build smooth SVG path using quadratic bezier
        $pathPoints = $chartPoints->map(function ($point, $index) use ($chartPoints) {
            if ($index === 0) {
                return "M {$point['x']} {$point['y']}";
            }

            $prev = $chartPoints[$index - 1];
            $midX = ($prev['x'] + $point['x']) / 2;

            return "Q {$midX} {$prev['y']}, {$point['x']} {$point['y']}";
        })->implode(' ');

        // Area path (close the bottom)
        $lastPoint = $chartPoints->last();
        $firstPoint = $chartPoints->first();
        $baselineY = $paddingY + $chartHeight;
        $areaPath = $pathPoints . " L {$lastPoint['x']} {$baselineY} L {$firstPoint['x']} {$baselineY} Z";

        // Top campaigns by raised amount
        $topCampaigns = Campaign::orderByDesc('raised_amount_cents')
            ->limit(4)
            ->get();

        return view('livewire.dashboard', [
            'stats' => [
                ['label' => 'Total donations', 'value' => number_format($totalDonations), 'trend' => '+12%', 'trendUp' => true],
                ['label' => 'Total raised', 'value' => '$'.number_format($totalRaised / 100, 0), 'trend' => '+8%', 'trendUp' => true],
                ['label' => 'Active campaigns', 'value' => number_format($activeCampaigns), 'trend' => null],
                ['label' => 'New donors (30d)', 'value' => number_format($newDonors), 'trend' => '+23%', 'trendUp' => true],
            ],
            'recentDonations' => $recentDonations,
            'chartPoints' => $chartPoints,
            'pathPoints' => $pathPoints,
            'areaPath' => $areaPath,
            'chartMax' => $maxAmount,
            'chartWidth' => $chartWidth + ($paddingX * 2),
            'chartHeight' => $chartHeight + ($paddingY * 2),
            'paddingX' => $paddingX,
            'paddingY' => $paddingY,
            'baselineY' => $baselineY,
            'trend' => $trendDays,
            'topCampaigns' => $topCampaigns,
        ])->layout('components.layouts.admin');
    }
}
