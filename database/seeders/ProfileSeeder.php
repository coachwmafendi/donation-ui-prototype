<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'first_name' => 'Linda',
                'last_name' => 'Ahmad',
                'email' => 'linda@example.com',
                'phone' => '+65 9123 4567',
                'country' => 'Singapore',
                'address_line_1' => '123 Orchard Road',
                'city' => 'Singapore',
                'postal_code' => '238863',
            ],
            [
                'first_name' => 'Muhammad',
                'last_name' => 'Ibrahim',
                'email' => 'muhammad@example.com',
                'phone' => '+60 12-345 6789',
                'country' => 'Malaysia',
                'address_line_1' => '45 Jalan Bukit Bintang',
                'city' => 'Kuala Lumpur',
                'postal_code' => '55100',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Tan',
                'email' => 'sarah.tan@example.com',
                'phone' => '+65 8765 4321',
                'country' => 'Singapore',
                'address_line_1' => '78 Serangoon Garden',
                'city' => 'Singapore',
                'postal_code' => '555123',
            ],
        ];

        foreach ($profiles as $profile) {
            Profile::create($profile);
        }
    }
}
