<?php

namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;

class CampaignShow extends Component
{
    public Campaign $campaign;

    public bool $showArchiveModal = false;

    // Settings: Payment Methods
    public array $paymentMethods = [];

    // Settings: Currency
    public string $currency = 'USD';

    // Settings: Frequencies
    public array $frequencies = [];

    public string $defaultFrequency = 'one-time';

    // Settings: Suggested Amounts (presets shared across frequencies for now)
    public array $presets = [200, 100, 50, 30, 10, 5];

    public array $defaultAmounts = [];

    // Settings: Minimum Amounts
    public array $minAmounts = [];

    // Settings: Transaction Cost
    public bool $coverFee = true;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign->loadCount('donations');

        $settings = $campaign->settings ?? [];

        $this->currency = $campaign->currency ?? 'USD';

        $this->paymentMethods = $settings['payment_methods'] ?? ['credit_card', 'paypal'];

        $this->frequencies = $settings['frequencies'] ?? ['one-time', 'monthly'];
        $this->defaultFrequency = $settings['default_frequency'] ?? 'one-time';

        $this->presets = $settings['presets'] ?? [200, 100, 50, 30, 10, 5];
        $this->defaultAmounts = $settings['default_amounts'] ?? [
            'one-time' => 50,
            'monthly' => 25,
            'yearly' => 300,
            'weekly' => 10,
            'quarterly' => 75,
        ];

        $this->minAmounts = $settings['min_amounts'] ?? [
            'one-time' => 10,
            'monthly' => 10,
            'yearly' => 100,
            'weekly' => 5,
            'quarterly' => 25,
        ];

        $this->coverFee = $settings['cover_fee'] ?? true;
    }

    public function archive(): void
    {
        $this->campaign->update(['status' => 'archived']);
        $this->showArchiveModal = false;
        $this->dispatch('toast', message: 'Campaign archived.', type: 'success');
    }

    public function savePaymentMethods(): void
    {
        $this->saveSetting('payment_methods', $this->paymentMethods);
    }

    public function saveCurrency(): void
    {
        $this->campaign->update(['currency' => $this->currency]);
        $this->dispatch('toast', message: 'Currency saved.', type: 'success');
    }

    public function saveFrequencies(): void
    {
        $this->saveSetting('frequencies', $this->frequencies);
        $this->saveSetting('default_frequency', $this->defaultFrequency);
    }

    public function saveAmounts(): void
    {
        $this->saveSetting('presets', array_values(array_filter($this->presets, fn ($v) => $v !== null && $v !== '')));
        $this->saveSetting('default_amounts', $this->defaultAmounts);
    }

    public function saveMinimums(): void
    {
        $this->saveSetting('min_amounts', $this->minAmounts);
    }

    public function saveTransactionCost(): void
    {
        $this->saveSetting('cover_fee', $this->coverFee);
    }

    protected function saveSetting(string $key, mixed $value): void
    {
        $settings = $this->campaign->settings ?? [];
        $settings[$key] = $value;
        $this->campaign->update(['settings' => $settings]);
        $this->dispatch('toast', message: str_replace('_', ' ', ucfirst($key)).' saved.', type: 'success');
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
