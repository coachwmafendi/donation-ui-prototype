<div class="mx-auto max-w-2xl px-6 py-10">

    {{-- Success State --}}
    @if($showSuccess)
        <div class="text-center py-16">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-emerald-100">
                <x-icon name="check" class="size-8 text-emerald-600" />
            </div>
            <h1 class="mt-6 text-3xl font-bold tracking-tight text-slate-900">
                Thank you!
            </h1>
            <p class="mt-3 text-lg text-slate-600">
                Your donation has been received.
            </p>
            <p class="mt-2 text-sm text-slate-500">
                Donation ID: <span class="font-mono">{{ $donationPublicId }}</span>
            </p>
            <div class="mt-8">
                <a href="/donate" wire:navigate class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition">
                    Make another donation
                </a>
            </div>
        </div>
    @else

    {{-- Header --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
            Make a donation
        </h1>
        <p class="mt-2 text-slate-600">
            Your contribution makes a difference.
        </p>
    </div>

    <form wire:submit.prevent="submit" class="space-y-8">

        {{-- Campaign Selection --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold">Choose a campaign</h2>
            </div>
            <div class="px-6 py-5">
                <label class="sr-only" for="campaign">Campaign</label>
                <select
                    wire:model="campaignId"
                    id="campaign"
                    class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                    <option value="">Select a campaign...</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">
                            {{ $campaign->name }}
                            ({{ $campaign->raised_amount }} / {{ $campaign->goal_amount }})
                        </option>
                    @endforeach
                </select>
                @error('campaignId')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        {{-- Amount Selection --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold">Donation amount</h2>
            </div>
            <div class="px-6 py-5 space-y-5">

                {{-- Preset buttons --}}
                <div class="grid grid-cols-3 sm:grid-cols-3 gap-3">
                    @foreach($presets as $preset)
                        <button
                            type="button"
                            wire:click="selectPreset({{ $preset }})"
                            class="rounded-lg border-2 px-4 py-3 text-sm font-semibold transition {{ $amount == $preset && !$customAmount ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 text-slate-700 hover:border-slate-300' }}"
                        >
                            ${{ $preset }}
                        </button>
                    @endforeach
                </div>

                {{-- Custom amount --}}
                <div>
                    <button
                        type="button"
                        wire:click="selectCustom"
                        class="w-full rounded-lg border-2 px-4 py-3 text-sm font-semibold transition {{ $customAmount ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 text-slate-700 hover:border-slate-300' }}"
                    >
                        Custom amount
                    </button>

                    @if($customAmount)
                        <div class="mt-3">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">$</span>
                                <input
                                    wire:model="amount"
                                    type="number"
                                    step="0.01"
                                    min="1"
                                    class="block w-full rounded-lg border border-slate-300 bg-white pl-8 pr-4 py-3 text-lg text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                    placeholder="0.00"
                                    autofocus
                                >
                            </div>
                        </div>
                    @endif
                </div>

                @error('amount')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- Currency & Frequency --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="currency" class="block text-sm font-medium text-slate-700 mb-1.5">Currency</label>
                        <select
                            wire:model="currency"
                            id="currency"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        >
                            <option value="USD">USD — US Dollar</option>
                            <option value="EUR">EUR — Euro</option>
                            <option value="GBP">GBP — British Pound</option>
                            <option value="SGD">SGD — Singapore Dollar</option>
                            <option value="MYR">MYR — Malaysian Ringgit</option>
                        </select>
                    </div>

                    <div>
                        <label for="frequency" class="block text-sm font-medium text-slate-700 mb-1.5">Frequency</label>
                        <select
                            wire:model="frequency"
                            id="frequency"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        >
                            <option value="one-time">One-time</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- Personal Information --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold">Personal information</h2>
            </div>
            <div class="px-6 py-5 space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-slate-700">First name <span class="text-red-500">*</span></label>
                        <input wire:model="firstName" id="firstName" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="e.g. Linda">
                        @error('firstName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-slate-700">Last name <span class="text-red-500">*</span></label>
                        <input wire:model="lastName" id="lastName" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="e.g. Ahmad">
                        @error('lastName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                        <input wire:model="email" id="email" type="email" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="you@example.com">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                        <input wire:model="phone" id="phone" type="tel" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="+1 234 567 890">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Address toggle --}}
                <div class="pt-2">
                    <button
                        type="button"
                        wire:click="$toggle('showAddress')"
                        class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition"
                    >
                        <x-icon name="settings" class="size-4" />
                        {{ $showAddress ? 'Hide address' : 'Add address (optional)' }}
                    </button>

                    @if($showAddress)
                        <div class="mt-4 space-y-4 rounded-lg bg-slate-50 p-4">
                            <div>
                                <label for="country" class="block text-sm font-medium text-slate-700">Country</label>
                                <input wire:model="country" id="country" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            </div>
                            <div>
                                <label for="addressLine1" class="block text-sm font-medium text-slate-700">Address line 1</label>
                                <input wire:model="addressLine1" id="addressLine1" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            </div>
                            <div>
                                <label for="addressLine2" class="block text-sm font-medium text-slate-700">Address line 2</label>
                                <input wire:model="addressLine2" id="addressLine2" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-slate-700">City</label>
                                    <input wire:model="city" id="city" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                </div>
                                <div>
                                    <label for="state" class="block text-sm font-medium text-slate-700">State</label>
                                    <input wire:model="state" id="state" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                </div>
                                <div>
                                    <label for="postalCode" class="block text-sm font-medium text-slate-700">Postal code</label>
                                    <input wire:model="postalCode" id="postalCode" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Payment Method --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold">Payment method</h2>
            </div>
            <div class="px-6 py-5">
                <div class="space-y-2">
                    <label class="flex items-center gap-3 rounded-lg border-2 px-4 py-3 cursor-pointer transition {{ $paymentMethod === 'credit_card' ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" wire:model="paymentMethod" value="credit_card" class="size-4 text-slate-900 focus:ring-slate-500">
                        <span class="text-sm font-medium text-slate-700">Credit card</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-lg border-2 px-4 py-3 cursor-pointer transition {{ $paymentMethod === 'paypal' ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" wire:model="paymentMethod" value="paypal" class="size-4 text-slate-900 focus:ring-slate-500">
                        <span class="text-sm font-medium text-slate-700">PayPal</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-lg border-2 px-4 py-3 cursor-pointer transition {{ $paymentMethod === 'bank_transfer' ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:border-slate-300' }}">
                        <input type="radio" wire:model="paymentMethod" value="bank_transfer" class="size-4 text-slate-900 focus:ring-slate-500">
                        <span class="text-sm font-medium text-slate-700">Bank transfer</span>
                    </label>
                </div>
                @error('paymentMethod')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- Optional: Comment & Tribute --}}
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold">Additional information</h2>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label for="comment" class="block text-sm font-medium text-slate-700">Comment (optional)</label>
                    <textarea wire:model="comment" id="comment" rows="3" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Leave a message..."></textarea>
                </div>
                <div>
                    <label for="tributeInfo" class="block text-sm font-medium text-slate-700">Tribute (optional)</label>
                    <input wire:model="tributeInfo" id="tributeInfo" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="In memory of...">
                </div>
            </div>
        </section>

        {{-- Agreement & Submit --}}
        <section class="space-y-5">
            <label class="flex items-start gap-3 cursor-pointer">
                <input wire:model="agreed" type="checkbox" class="mt-0.5 size-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500">
                <span class="text-sm text-slate-600">
                    I agree to the terms and conditions. My donation will be processed securely.
                </span>
            </label>
            @error('agreed')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full rounded-xl bg-slate-900 px-6 py-4 text-lg font-semibold text-white hover:bg-slate-800 transition disabled:opacity-50 shadow-lg shadow-slate-900/10"
            >
                <span wire:loading.remove>
                    Donate {{ $amount ? '$'.number_format($amount, 2).' '.strtoupper($currency) : 'now' }}
                </span>
                <span wire:loading>Processing...</span>
            </button>

            <p class="text-center text-xs text-slate-400">
                Secure donation. Your information is encrypted and never shared.
            </p>
        </section>

    </form>
    @endif
</div>
