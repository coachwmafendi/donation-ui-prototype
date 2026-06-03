<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Profile;
use Livewire\Component;

class DonationForm extends Component
{
    // Campaign selection
    public ?string $campaignId = null;

    // Amount
    public ?float $amount = null;

    public string $currency = 'USD';

    public array $presets = [10, 25, 50, 100, 250, 500];

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

    public function mount(?string $campaign = null): void
    {
        if ($campaign) {
            $this->campaignId = $campaign;
        }
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

    public function submit(): void
    {
        $validated = $this->validate([
            'campaignId' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'frequency' => 'required|in:one-time,monthly,weekly,yearly',
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
            'campaignId.required' => 'Please select a campaign.',
        ]);

        // Find or create profile
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

        // Get campaign name
        $campaign = Campaign::findOrFail($validated['campaignId']);

        // Create donation
        $amountCents = (int) round($validated['amount'] * 100);
        $processingFeeCents = (int) round($amountCents * 0.03); // 3% fee estimation
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

        // Update campaign stats
        $campaign->increment('raised_amount_cents', $amountCents);
        $campaign->increment('donor_count');

        // Send receipt email
        \Mail::to($profile->email)
            ->send(new \App\Mail\DonationReceipt($donation, $profile));

        $this->donationPublicId = $donation->public_id;
        $this->showSuccess = true;
    }

    public function render()
    {
        $campaigns = Campaign::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'goal_amount_cents', 'raised_amount_cents', 'currency']);

        return view('livewire.donation-form', [
            'campaigns' => $campaigns,
        ])->layout('components.layouts.public');
    }
}
