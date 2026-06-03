<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileSettingsController;
use App\Livewire\CampaignIndex;
use App\Livewire\CampaignShow;
use App\Livewire\DonationIndex;
use App\Livewire\DonationShow;
use App\Livewire\UserIndex;
use App\Livewire\UserShow;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/donations', DonationIndex::class);
    Route::get('/donations/{donation:public_id}', DonationShow::class);
    Route::get('/users', UserIndex::class);
    Route::get('/users/{user}', UserShow::class);
    Route::get('/campaigns', CampaignIndex::class);
    Route::get('/campaigns/{campaign:public_id}', CampaignShow::class);
    
    Route::get('/settings', [ProfileSettingsController::class, 'show'])->name('settings');
    Route::put('/settings/profile', [ProfileSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [ProfileSettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/two-factor', [ProfileSettingsController::class, 'enableTwoFactor'])->name('settings.two-factor.enable');
    Route::post('/settings/two-factor/confirm', [ProfileSettingsController::class, 'confirmTwoFactor'])->name('settings.two-factor.confirm');
    Route::delete('/settings/two-factor', [ProfileSettingsController::class, 'disableTwoFactor'])->name('settings.two-factor.disable');
    Route::post('/settings/two-factor/recovery-codes', [ProfileSettingsController::class, 'regenerateRecoveryCodes'])->name('settings.two-factor.recovery-codes');
});
