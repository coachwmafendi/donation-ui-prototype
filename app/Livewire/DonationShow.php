<?php

namespace App\Livewire;

use App\Models\Donation;
use Livewire\Component;

class DonationShow extends Component
{
    public string $donationId;

    public Donation $donationModel;

    public bool $showRefundModal = false;

    public string $refundReason = '';

    public array $sections = [
        ['id' => 'donation', 'label' => 'Donation', 'icon' => 'banknote'],
        ['id' => 'payment-fees', 'label' => 'Payment & fees', 'icon' => 'percent'],
        ['id' => 'recurring-plan', 'label' => 'Recurring plan', 'icon' => 'refresh-cw'],
        ['id' => 'personal-information', 'label' => 'Personal information', 'icon' => 'user'],
        ['id' => 'tribute', 'label' => 'Tribute', 'icon' => 'heart'],
        ['id' => 'comment', 'label' => 'Comment', 'icon' => 'message-square'],
        ['id' => 'source', 'label' => 'Source', 'icon' => 'external-link'],
        ['id' => 'insights', 'label' => 'Insights', 'icon' => 'zap'],
        ['id' => 'utm-parameters', 'label' => 'UTM parameters', 'icon' => 'hash'],
        ['id' => 'custom-fields', 'label' => 'Custom fields', 'icon' => 'settings'],
        ['id' => 'emails', 'label' => 'Emails', 'icon' => 'mail'],
    ];

    public function mount(Donation $donation)
    {
        $this->donationId = $donation->public_id;
        $this->donationModel = $donation->load('profile');
    }

    public function refund()
    {
        $this->validate([
            'refundReason' => 'required|string',
        ]);

        $this->donationModel->update([
            'status' => 'refunded',
            'refund_reason' => $this->refundReason,
        ]);

        $this->showRefundModal = false;
        $this->refundReason = '';

        $this->dispatch('toast', message: 'Donation refunded successfully.', type: 'success');
    }

    public function render()
    {
        return view('livewire.donation-show')->layout('components.layouts.admin');
    }
}
