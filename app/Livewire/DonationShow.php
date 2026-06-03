<?php

namespace App\Livewire;

use Livewire\Component;

class DonationShow extends Component
{
    public string $donationId;

    public array $donation = [
        'amount' => '$10.20 SGD',
        'converted_amount' => 'MYR 31.62',
        'id' => 'DGRLMQTG',
        'status' => 'Succeeded',
        'supporter' => 'Linda Ahmad',
        'campaign' => 'MTMT DEVELOPMENT FUND',
        'designation' => 'General designation',
        'donation_date' => 'Jun 3, 2026, 8:50 AM',
        'success_date' => 'Jun 3, 2026, 8:50 AM',
        'frequency' => 'Monthly',
    ];

    public array $sections = [
        [
            'id' => 'donation',
            'label' => 'Donation',
            'icon' => '$',
        ],
        [
            'id' => 'payment-fees',
            'label' => 'Payment & fees',
            'icon' => '%',
        ],
        [
            'id' => 'recurring-plan',
            'label' => 'Recurring plan',
            'icon' => '↻',
        ],
        [
            'id' => 'personal-information',
            'label' => 'Personal information',
            'icon' => '👤',
        ],
        [
            'id' => 'tribute',
            'label' => 'Tribute',
            'icon' => '♡',
        ],
        [
            'id' => 'comment',
            'label' => 'Comment',
            'icon' => '💬',
        ],
        [
            'id' => 'source',
            'label' => 'Source',
            'icon' => '↗',
        ],
        [
            'id' => 'insights',
            'label' => 'Insights',
            'icon' => '⌁',
        ],
        [
            'id' => 'utm-parameters',
            'label' => 'UTM parameters',
            'icon' => '#',
        ],
        [
            'id' => 'custom-fields',
            'label' => 'Custom fields',
            'icon' => '⚙',
        ],
        [
            'id' => 'emails',
            'label' => 'Emails',
            'icon' => '✉',
        ],
    ];

    public function mount(string $id)
    {
        $this->donationId = $id;
    }

    public function render()
    {
        return view('livewire.donation-show')->layout('components.layouts.admin');
    }
}