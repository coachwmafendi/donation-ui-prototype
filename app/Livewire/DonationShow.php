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
        ['id' => 'donation', 'label' => 'Donation', 'icon' => '$'],
        ['id' => 'payment-fees', 'label' => 'Payment & fees', 'icon' => '%'],
        ['id' => 'recurring-plan', 'label' => 'Recurring plan', 'icon' => '↻'],
        ['id' => 'personal-information', 'label' => 'Personal information', 'icon' => '👤'],
        ['id' => 'tribute', 'label' => 'Tribute', 'icon' => '♡'],
        ['id' => 'comment', 'label' => 'Comment', 'icon' => '💬'],
        ['id' => 'source', 'label' => 'Source', 'icon' => '↗'],
        ['id' => 'insights', 'label' => 'Insights', 'icon' => '⌁'],
        ['id' => 'utm-parameters', 'label' => 'UTM parameters', 'icon' => '#'],
        ['id' => 'custom-fields', 'label' => 'Custom fields', 'icon' => '⚙'],
        ['id' => 'emails', 'label' => 'Emails', 'icon' => '✉'],
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
        
        $this->dispatch('refunded');
    }

    public function render()
    {
        return view('livewire.donation-show')->layout('components.layouts.admin');
    }
}
