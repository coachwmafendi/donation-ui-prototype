<div class="min-h-screen bg-white">

    @if($showSuccess)
        <div class="text-center py-10 px-6">
            <div class="mx-auto flex size-14 items-center justify-center rounded-full bg-emerald-100">
                <svg class="size-7 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h2 class="mt-4 text-xl font-bold text-slate-900">Thank you!</h2>
            <p class="mt-2 text-sm text-slate-600">Your donation has been received.</p>
            <p class="mt-1 text-xs text-slate-500 font-mono">ID: {{ $donationPublicId }}</p>
        </div>
    @else

    <div class="p-5">

        @if($campaign)
            <div class="mb-5 text-center">
                <h2 class="text-lg font-bold text-slate-900">{{ $campaign->name }}</h2>
                @if($campaign->description)
                    <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ $campaign->description }}</p>
                @endif
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-emerald-500" style="width: {{ $campaign->progress_percentage }}%"></div>
                </div>
                <p class="mt-1 text-xs text-slate-500">{{ $campaign->raised_amount }} raised</p>
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-5">

            {{-- Campaign (if not pre-selected) --}}
            @if(!$campaign)
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Campaign</label>
                <select wire:model="campaignId" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <option value="">Select campaign...</option>
                    @foreach($campaigns as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('campaignId')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            @endif

            {{-- Amount --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Amount</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($presets as $preset)
                        <button type="button" wire:click="selectPreset({{ $preset }})" class="rounded-lg border-2 py-2 text-sm font-semibold transition {{ $amount == $preset && !$customAmount ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 text-slate-700 hover:border-slate-300' }}">
                            ${{ $preset }}
                        </button>
                    @endforeach
                </div>
                <button type="button" wire:click="selectCustom" class="mt-2 w-full rounded-lg border-2 py-2 text-sm font-semibold transition {{ $customAmount ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 text-slate-700 hover:border-slate-300' }}">
                    Custom
                </button>
                @if($customAmount)
                    <div class="relative mt-2">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                        <input wire:model="amount" type="number" step="0.01" min="1" class="block w-full rounded-lg border border-slate-300 bg-white pl-7 pr-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="0.00" autofocus>
                    </div>
                @endif
                @error('amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                <div class="mt-3 grid grid-cols-2 gap-3">
                    <select wire:model="currency" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="SGD">SGD</option>
                        <option value="MYR">MYR</option>
                    </select>
                    <select wire:model="frequency" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        <option value="one-time">One-time</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
            </div>

            {{-- Personal Info --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-700">First name</label>
                    <input wire:model="firstName" type="text" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    @error('firstName')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700">Last name</label>
                    <input wire:model="lastName" type="text" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    @error('lastName')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-700">Email</label>
                    <input wire:model="email" type="email" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700">Phone</label>
                    <input wire:model="phone" type="tel" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                </div>
            </div>

            {{-- Payment --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Payment method</label>
                <div class="space-y-1.5">
                    <label class="flex items-center gap-3 rounded-lg border-2 px-3 py-2 cursor-pointer {{ $paymentMethod === 'credit_card' ? 'border-slate-900 bg-slate-50' : 'border-slate-200' }}">
                        <input type="radio" wire:model="paymentMethod" value="credit_card" class="size-4">
                        <span class="text-sm">Credit card</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-lg border-2 px-3 py-2 cursor-pointer {{ $paymentMethod === 'paypal' ? 'border-slate-900 bg-slate-50' : 'border-slate-200' }}">
                        <input type="radio" wire:model="paymentMethod" value="paypal" class="size-4">
                        <span class="text-sm">PayPal</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-lg border-2 px-3 py-2 cursor-pointer {{ $paymentMethod === 'bank_transfer' ? 'border-slate-900 bg-slate-50' : 'border-slate-200' }}">
                        <input type="radio" wire:model="paymentMethod" value="bank_transfer" class="size-4">
                        <span class="text-sm">Bank transfer</span>
                    </label>
                </div>
            </div>

            {{-- Comment + Tribute --}}
            <div class="grid grid-cols-1 gap-3">
                <input wire:model="comment" type="text" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Comment (optional)">
                <input wire:model="tributeInfo" type="text" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" placeholder="Tribute (optional)">
            </div>

            {{-- Terms + Submit --}}
            <div>
                <label class="flex items-start gap-2 cursor-pointer">
                    <input wire:model="agreed" type="checkbox" class="mt-0.5 size-4 rounded">
                    <span class="text-xs text-slate-600">I agree to the terms.</span>
                </label>
                @error('agreed')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <button wire:loading.attr="disabled" type="submit" class="w-full rounded-lg bg-slate-900 py-3 text-sm font-bold text-white hover:bg-slate-800 transition disabled:opacity-50">
                <span wire:loading.remove>Donate {{ $amount ? '$'.number_format($amount, 2) : 'now' }}</span>
                <span wire:loading>Processing...</span>
            </button>

            <p class="text-center text-[10px] text-slate-400">Secure donation · {{ config('app.name') }}</p>
        </form>
    </div>
    @endif

</div>
