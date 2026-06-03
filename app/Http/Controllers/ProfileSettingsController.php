<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Illuminate\Support\Str;

class ProfileSettingsController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        
        $status = null;
        $twoFactorQrCode = null;
        $twoFactorRecoveryCodes = null;
        
        if ($request->session()->get('status')) {
            $status = $request->session()->get('status');
        }
        
        if ($user->two_factor_confirmed_at) {
            $twoFactorRecoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        }
        
        return view('profile.settings', compact('user', 'status', 'twoFactorRecoveryCodes'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ])->validateWithBag('updateProfile');
        
        $user->forceFill([
            'name' => $request->name,
            'email' => $request->email,
        ])->save();
        
        return back()->with('status', 'Profile updated successfully.');
    }
    
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        
        Validator::make($request->all(), [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validateWithBag('updatePassword');
        
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();
        
        return back()->with('status', 'Password updated successfully.');
    }
    
    public function enableTwoFactor(Request $request)
    {
        $user = $request->user();
        
        $provider = app(TwoFactorAuthenticationProvider::class);
        
        $user->forceFill([
            'two_factor_secret' => encrypt($provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(
                collect(range(1, 8))->map(function () {
                    return Str::random(10).'-'.Str::random(10);
                })->all()
            )),
        ])->save();
        
        return back()->with('status', 'Two-factor authentication enabled. Scan the QR code with your authenticator app.');
    }
    
    public function confirmTwoFactor(Request $request)
    {
        $user = $request->user();
        $provider = app(TwoFactorAuthenticationProvider::class);
        
        if (empty($user->two_factor_secret) ||
            ! $provider->verify(decrypt($user->two_factor_secret), $request->code)) {
            return back()->withErrors(['code' => 'The provided code was invalid.'], 'confirmTwoFactor');
        }
        
        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();
        
        return back()->with('status', 'Two-factor authentication confirmed successfully.');
    }
    
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();
        
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
        
        return back()->with('status', 'Two-factor authentication disabled.');
    }
    
    public function regenerateRecoveryCodes(Request $request)
    {
        $user = $request->user();
        
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(
                collect(range(1, 8))->map(function () {
                    return Str::random(10).'-'.Str::random(10);
                })->all()
            )),
        ])->save();
        
        return back()->with('status', 'Recovery codes regenerated.');
    }
}
