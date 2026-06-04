<?php

namespace App\Livewire;

use App\Mail\DonationReceipt;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Profile;
use Livewire\Component;

class DonationForm extends Component
{
    // Wizard step
    public int $step = 1;

    public int $totalSteps = 4;

    // Campaign
    public ?string $campaignId = null;

    public ?Campaign $selectedCampaign = null;

    // Campaign settings (injected from settings JSON)
    public array $campaignFrequencies = ['one-time', 'monthly'];

    public array $campaignPresets = [10, 25, 50, 100, 250, 500];

    public array $frequencyPresets = [];

    public ?float $campaignMinAmount = 1;

    public string $campaignDefaultFrequency = 'one-time';

    // Amount
    public ?float $amount = null;

    public string $currency = 'USD';

    public bool $customAmount = false;

    // Frequency
    public string $frequency = 'one-time';

    // Personal info
    public string $firstName = '';

    public string $lastName = '';

    public string $email = '';

    public string $phone = '';

    // Address (optional)
    public bool $showAddress = false;

    public string $country = '';

    public string $addressLine1 = '';

    public string $addressLine2 = '';

    public string $city = '';

    public string $state = '';

    public string $postalCode = '';

    // Payment
    public string $paymentMethod = 'credit_card';

    // Optional fields
    public string $comment = '';

    public string $tributeInfo = '';

    public bool $agreed = false;

    // UI state
    public bool $showSuccess = false;

    public ?string $donationPublicId = null;

    public bool $embed = false;

    public function mount(?string $campaign = null, bool $embed = false): void
    {
        $this->embed = $embed;

        if ($campaign) {
            $this->campaignId = $campaign;
            $this->loadCampaignSettings();
        }
    }

    protected function loadCampaignSettings(): void
    {
        $campaign = Campaign::where('public_id', $this->campaignId)
            ->orWhere('slug', $this->campaignId)
            ->first();

        if (! $campaign) {
            return;
        }

        $this->selectedCampaign = $campaign;
        $this->campaignId = $campaign->id;
        $this->currency = $campaign->currency ?? 'USD';

        $settings = $campaign->settings ?? [];

        if (! empty($settings['frequencies'])) {
            $this->campaignFrequencies = $settings['frequencies'];
        }

        // Load per-frequency presets, fallback to flat presets for backward compatibility
        if (! empty($settings['frequency_presets'])) {
            $this->frequencyPresets = $settings['frequency_presets'];
        } elseif (! empty($settings['presets'])) {
            // Backward compatibility: assign flat presets to all frequencies
            foreach ($this->campaignFrequencies as $freq) {
                $this->frequencyPresets[$freq] = $settings['presets'];
            }
        }

        if (isset($settings['min_amount'])) {
            $this->campaignMinAmount = (float) $settings['min_amount'];
        }

        if (! empty($settings['default_frequency']) && in_array($settings['default_frequency'], $this->campaignFrequencies)) {
            $this->campaignDefaultFrequency = $settings['default_frequency'];
        }

        $this->frequency = $this->campaignDefaultFrequency;

        // Auto-select default amount if it exists in the frequency's presets
        $defaultAmounts = $settings['default_amounts'] ?? [];
        if (isset($defaultAmounts[$this->frequency])) {
            $defaultAmount = (float) $defaultAmounts[$this->frequency];
            $currentPresets = $this->frequencyPresets[$this->frequency] ?? [];
            if (in_array($defaultAmount, $currentPresets)) {
                $this->amount = $defaultAmount;
            }
        }
    }

    public function getCurrentPresetsProperty(): array
    {
        return $this->frequencyPresets[$this->frequency] ?? $this->campaignPresets;
    }

    public function selectPreset(float $amount): void
    {
        $this->amount = $amount;
        $this->customAmount = false;
    }

    public function selectCustom(): void
    {
        $this->customAmount = true;
        $this->amount = null;
    }

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $min = $this->campaignMinAmount ?? 1;
            $this->validate([
                'amount' => 'required|numeric|min:'.$min,
                'currency' => 'required|string|size:3',
                'frequency' => 'required|in:'.implode(',', $this->campaignFrequencies),
            ], [
                'amount.min' => 'The minimum donation amount is '.number_format($min, 2).' '.strtoupper($this->currency).'.',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:50',
            ]);
        } elseif ($this->step === 3) {
            $this->validate([
                'paymentMethod' => 'required|in:credit_card,paypal,bank_transfer',
            ]);
        }

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->step) {
            $this->step = $step;
        }
    }

    public function submit(): void
    {
        $min = $this->campaignMinAmount ?? 1;

        $validated = $this->validate([
            'campaignId' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:'.$min,
            'currency' => 'required|string|size:3',
            'frequency' => 'required|in:'.implode(',', $this->campaignFrequencies),
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'addressLine1' => 'nullable|string|max:255',
            'addressLine2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postalCode' => 'nullable|string|max:50',
            'paymentMethod' => 'required|in:credit_card,paypal,bank_transfer',
            'comment' => 'nullable|string|max:1000',
            'tributeInfo' => 'nullable|string|max:1000',
            'agreed' => 'accepted',
        ], [
            'agreed.accepted' => 'You must agree to the terms to proceed.',
            'amount.min' => 'The minimum donation amount is '.number_format($min, 2).' '.strtoupper($this->currency).'.',
        ]);

        $profile = Profile::firstOrCreate(
            ['email' => $validated['email']],
            [
                'first_name' => $validated['firstName'],
                'last_name' => $validated['lastName'],
                'phone' => $validated['phone'] ?: null,
                'country' => $validated['country'] ?: null,
                'address_line_1' => $validated['addressLine1'] ?: null,
                'address_line_2' => $validated['addressLine2'] ?: null,
                'city' => $validated['city'] ?: null,
                'state' => $validated['state'] ?: null,
                'postal_code' => $validated['postalCode'] ?: null,
            ]
        );

        $campaign = Campaign::findOrFail($validated['campaignId']);

        $amountCents = (int) round($validated['amount'] * 100);
        $processingFeeCents = (int) round($amountCents * 0.03);
        $netAmountCents = $amountCents - $processingFeeCents;

        $donation = Donation::create([
            'profile_id' => $profile->id,
            'amount_cents' => $amountCents,
            'currency' => strtoupper($validated['currency']),
            'status' => 'succeeded',
            'campaign_id' => $campaign->id,
            'campaign' => $campaign->name,
            'designation' => 'General designation',
            'frequency' => $validated['frequency'],
            'donation_date' => now(),
            'success_date' => now(),
            'payment_amount_cents' => $amountCents,
            'processing_fee_cents' => $processingFeeCents,
            'net_amount_cents' => $netAmountCents,
            'payment_method' => $validated['paymentMethod'],
            'source' => 'donation_form',
            'device' => 'desktop',
            'donor_type' => 'new',
            'tribute_info' => $validated['tributeInfo'] ?: null,
            'comment' => $validated['comment'] ?: null,
        ]);

        $campaign->increment('raised_amount_cents', $amountCents);
        $campaign->increment('donor_count');

        \Mail::to($profile->email)
            ->send(new DonationReceipt($donation, $profile));

        $this->donationPublicId = $donation->public_id;
        $this->showSuccess = true;
    }

    public function render()
    {
        if (! $this->selectedCampaign && $this->campaignId) {
            $this->selectedCampaign = Campaign::find($this->campaignId);
        }

        $view = view('livewire.donation-form', [
            'campaignName' => $this->selectedCampaign?->name,
            'campaignPublicId' => $this->selectedCampaign?->public_id,
        ]);

        if ($this->embed) {
            return $view->layout('layouts.embed');
        }

        return $view->layout('components.layouts.public');
    }
}
