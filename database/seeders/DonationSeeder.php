<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $linda = Profile::where('email', 'linda@example.com')->first();
        $muhammad = Profile::where('email', 'muhammad@example.com')->first();
        $sarah = Profile::where('email', 'sarah.tan@example.com')->first();

        $donations = [
            [
                'profile_id' => $linda->id,
                'amount_cents' => 1020,
                'currency' => 'SGD',
                'converted_amount_cents' => 3162,
                'converted_currency' => 'MYR',
                'status' => 'succeeded',
                'campaign' => 'MTMT DEVELOPMENT FUND',
                'designation' => 'General designation',
                'frequency' => 'monthly',
                'donation_date' => Carbon::parse('2026-06-03 08:50:00'),
                'success_date' => Carbon::parse('2026-06-03 08:50:00'),
                'payment_amount_cents' => 1020,
                'processing_fee_cents' => 120,
                'net_amount_cents' => 900,
                'payment_method' => 'credit_card',
                'source' => 'donation_form',
                'device' => 'desktop',
                'donor_type' => 'returning',
                'tribute_info' => null,
                'comment' => null,
                'utm_source' => 'facebook',
                'utm_campaign' => 'mtmt-development-fund',
                'receipt_email_sent' => true,
                'thank_you_email_sent' => true,
            ],
            [
                'profile_id' => $muhammad->id,
                'amount_cents' => 5000,
                'currency' => 'MYR',
                'converted_amount_cents' => null,
                'converted_currency' => null,
                'status' => 'succeeded',
                'campaign' => 'RAMADAN APPEAL 2026',
                'designation' => 'Zakat',
                'frequency' => 'one-time',
                'donation_date' => Carbon::parse('2026-05-15 14:30:00'),
                'success_date' => Carbon::parse('2026-05-15 14:31:00'),
                'payment_amount_cents' => 5000,
                'processing_fee_cents' => 150,
                'net_amount_cents' => 4850,
                'payment_method' => 'bank_transfer',
                'source' => 'donation_form',
                'device' => 'mobile',
                'donor_type' => 'new',
                'tribute_info' => 'In memory of my father',
                'comment' => 'May Allah accept this donation',
                'utm_source' => null,
                'utm_campaign' => null,
                'receipt_email_sent' => true,
                'thank_you_email_sent' => true,
            ],
            [
                'profile_id' => $sarah->id,
                'amount_cents' => 2500,
                'currency' => 'SGD',
                'converted_amount_cents' => 7750,
                'converted_currency' => 'MYR',
                'status' => 'pending',
                'campaign' => 'EDUCATION FUND',
                'designation' => 'Student sponsorship',
                'frequency' => 'monthly',
                'donation_date' => Carbon::parse('2026-06-01 09:00:00'),
                'success_date' => null,
                'payment_amount_cents' => null,
                'processing_fee_cents' => null,
                'net_amount_cents' => null,
                'payment_method' => 'credit_card',
                'source' => 'api',
                'device' => 'tablet',
                'donor_type' => 'new',
                'tribute_info' => null,
                'comment' => null,
                'utm_source' => 'google',
                'utm_campaign' => 'education-2026',
                'receipt_email_sent' => false,
                'thank_you_email_sent' => false,
            ],
            [
                'profile_id' => $linda->id,
                'amount_cents' => 10000,
                'currency' => 'SGD',
                'converted_amount_cents' => 31000,
                'converted_currency' => 'MYR',
                'status' => 'succeeded',
                'campaign' => 'MTMT DEVELOPMENT FUND',
                'designation' => 'Building fund',
                'frequency' => 'one-time',
                'donation_date' => Carbon::parse('2026-04-20 16:45:00'),
                'success_date' => Carbon::parse('2026-04-20 16:45:00'),
                'payment_amount_cents' => 10000,
                'processing_fee_cents' => 350,
                'net_amount_cents' => 9650,
                'payment_method' => 'credit_card',
                'source' => 'donation_form',
                'device' => 'desktop',
                'donor_type' => 'returning',
                'tribute_info' => null,
                'comment' => 'Keep up the good work!',
                'utm_source' => 'email',
                'utm_campaign' => 'april-newsletter',
                'receipt_email_sent' => true,
                'thank_you_email_sent' => true,
            ],
        ];

        foreach ($donations as $donation) {
            Donation::create($donation);
        }
    }
}
