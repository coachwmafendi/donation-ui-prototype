<?php

use App\Http\Controllers\ProfileSettingsController;
use App\Livewire\CampaignIndex;
use App\Livewire\CampaignPublic;
use App\Livewire\CampaignShow;
use App\Livewire\Dashboard;
use App\Livewire\DonationEmbed;
use App\Livewire\DonationForm;
use App\Livewire\DonationIndex;
use App\Livewire\DonationShow;
use App\Livewire\HomePage;
use App\Livewire\RecurringIndex;
use App\Livewire\SupporterIndex;
use App\Livewire\SupporterShow;
use App\Livewire\UserIndex;
use App\Livewire\UserShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }

    return redirect('/welcome');
});

Route::get('/welcome', HomePage::class)->name('welcome');
Route::get('/c/{campaign:slug}', CampaignPublic::class)->name('campaigns.public');
use App\Models\Campaign;

Route::get('/donate', function () {
    $campaign = Campaign::where('status', 'active')->first();

    if (! $campaign) {
        abort(404, 'No active campaigns available.');
    }

    return redirect()->route('donate.campaign', ['campaign' => $campaign->public_id]);
})->name('donate');
Route::get('/donate/{campaign}', DonationForm::class)->name('donate.campaign');

// Embed routes for external websites
Route::get('/embed', DonationEmbed::class)->name('donate.embed');
Route::get('/embed/{campaign:slug}', DonationEmbed::class)->name('donate.campaign.embed');

// Embed instructions (admin only)
Route::get('/embed-instructions', function () {
    return view('embed-instructions');
})->middleware('auth')->name('embed.instructions');

use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/donations', DonationIndex::class);
    Route::get('/donations/{donation:public_id}', DonationShow::class);
    Route::get('/donations/{donation:public_id}/receipt', function (Donation $donation) {
        $pdf = Pdf::loadView('receipts.donation', ['donation' => $donation->load('profile')])
            ->setPaper('a4', 'portrait');

        return $pdf->download("receipt-{$donation->public_id}.pdf");
    })->name('donations.receipt');
    Route::get('/users', UserIndex::class);
    Route::get('/users/{user}', UserShow::class);
    Route::get('/supporters', SupporterIndex::class)->name('supporters.index');
    Route::get('/supporters/{supporter:public_id}', SupporterShow::class)->name('supporters.show');
    Route::get('/recurring', RecurringIndex::class)->name('recurring.index');
    Route::get('/campaigns', CampaignIndex::class)->name('campaigns.index');
    Route::get('/campaigns/{campaign:public_id}', CampaignShow::class)->name('campaigns.edit');

    Route::get('/settings', [ProfileSettingsController::class, 'show'])->name('settings');
    Route::put('/settings/profile', [ProfileSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [ProfileSettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/two-factor', [ProfileSettingsController::class, 'enableTwoFactor'])->name('settings.two-factor.enable');
    Route::post('/settings/two-factor/confirm', [ProfileSettingsController::class, 'confirmTwoFactor'])->name('settings.two-factor.confirm');
    Route::delete('/settings/two-factor', [ProfileSettingsController::class, 'disableTwoFactor'])->name('settings.two-factor.disable');
    Route::post('/settings/two-factor/recovery-codes', [ProfileSettingsController::class, 'regenerateRecoveryCodes'])->name('settings.two-factor.recovery-codes');
});
