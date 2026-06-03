<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Profile;
use Livewire\Component;

class DonationEmbed extends Component
{
    public ?string $campaignId = null;

    public ?float $amount = null;
    public string $currency = 'USD';
    public array $presets = [25, 50, 100, 250];
    public bool $customAmount = false;
    public string $frequency = 'one-time';

    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $phone = '';

    public bool $showAddress = false;
    public string $country = '';
    public string $addressLine1 = '';
    public string $addressLine2 = '';
    public string $city = '';
    public string $state = '';
    public string $postalCode = '';

    public string $paymentMethod = 'credit_card';
    public string $comment = '';
    public string $tributeInfo = '';
    public bool $agreed = false;

    public bool $showSuccess = false;
    public ?string $donationPublicId = null;
    public ?Campaign $campaign = null;

    public function mount(?string $campaign = null): void
    {
        if ($campaign) {
            $this->campaign = Campaign::where('public_id', $campaign)->orWhere('slug', $campaign)->first();
            if ($this->campaign) {
                $this->campaignId = $this->campaign->id;
            }
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
            'frequency' => 'required|in:one-time,monthly,yearly',
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
            'designation' => 'Embed donation',
            'frequency' => $validated['frequency'],
            'donation_date' => now(),
            'success_date' => now(),
            'payment_amount_cents' => $amountCents,
            'processing_fee_cents' => $processingFeeCents,
            'net_amount_cents' => $netAmountCents,
            'payment_method' => $validated['paymentMethod'],
            'source' => 'embed',
            'device' => 'desktop',
            'donor_type' => 'new',
            'tribute_info' => $validated['tributeInfo'] ?: null,
            'comment' => $validated['comment'] ?: null,
        ]);

        $campaign->increment('raised_amount_cents', $amountCents);
        $campaign->increment('donor_count');

        \Mail::to($profile->email)
            ->send(new \App\Mail\DonationReceipt($donation, $profile));

        $this->donationPublicId = $donation->public_id;
        $this->showSuccess = true;
    }

    public function render()
    {
        return view('livewire.donation-embed', [
            'campaigns' => Campaign::where('status', 'active')->orderBy('name')->get(),
        ])->layout('layouts.embed');
    }
}
