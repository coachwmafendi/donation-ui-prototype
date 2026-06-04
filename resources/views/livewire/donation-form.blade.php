<div class="mx-auto max-w-2xl px-6 py-10">


    {{-- Success State --}}
    @if($showSuccess)
        <div class="text-center py-16">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-emerald-100">
                <x-icon name="check" class="size-8 text-emerald-600" />
            </div>
            <h1 class="mt-6 text-3xl font-bold tracking-tight text-slate-900">Thank you!</h1>
            <p class="mt-3 text-lg text-slate-600">Your donation has been received.</p>
            <div class="mt-8">
                <a href="/donate" wire:navigate class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition">
                    Make another donation
                </a>
            </div>
        </div>
    @else

    {{-- Campaign Info --}}
    @if($campaignName)
    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="size-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="size-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.81-4.5 2.09C10.832 3.81 9.26 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Campaign</p>
                <p class="text-sm font-semibold text-slate-900 truncate">{{ $campaignName }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Stepper --}}
    <div class="mb-10">
        <div class="flex items-center justify-between">
            @foreach([
                1 => 'Amount',
                2 => 'Details',
                3 => 'Payment',
                4 => 'Review'
            ] as $num => $label)
                <div class="flex flex-col items-center">
                <button
                    type="button"
                    wire:click="goToStep({{ $num }})"
                    class="flex size-10 items-center justify-center rounded-full text-sm font-bold transition {{ $step >= $num ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }} {{ $step > $num || $step === $num ? 'cursor-pointer' : 'cursor-default' }}"
                    {{ $step < $num ? 'disabled' : '' }}
                >
                    @if($step > $num)
                        <x-icon name="check" class="size-5" />
                    @else
                        {{ $num }}
                    @endif
                </button>
                <span class="mt-2 text-xs font-medium {{ $step >= $num ? 'text-emerald-600' : 'text-slate-400' }}">{{ $label }}</span>
            </div>
            @if($num < 4)
                <div class="flex-1 h-px mx-2 {{ $step > $num ? 'bg-emerald-600' : 'bg-slate-200' }}"></div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Step 1: Amount --}}
    @if($step === 1)
    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold">Step 1: Select donation amount</h2>
        </div>
        <div class="px-6 py-5 space-y-5">

            {{-- Frequency tabs --}}
            <div class="flex gap-3 justify-center">
                @foreach($campaignFrequencies as $freq)
                    @php
                        $label = $freq === 'one-time' ? 'One Time' : 'Monthly';
                        $isActive = $frequency === $freq;
                    @endphp
                    <button
                        type="button"
                        wire:click="$set('frequency', '{{ $freq }}')"
                        @class([
                            'flex items-center gap-2.5 rounded-full px-7 py-3 text-base font-bold transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] transform',
                            'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25 scale-100' => $isActive,
                            'bg-white text-slate-600 border-2 border-slate-200 hover:border-emerald-400 hover:text-emerald-600 hover:shadow-md scale-[0.92] hover:scale-100' => !$isActive,
                        ])
                        @if($freq === 'monthly')
                            x-data="{ floatHearts() { const c = this.$el.querySelector('.heart-container'); c.querySelectorAll('.floating-heart').forEach(h => h.remove()); for(let i=0;i<7;i++){const h=document.createElement('div');h.className='floating-heart';h.innerHTML='<svg style=\"width:100%;height:100%\" viewBox=\"0 0 24 24\" fill=\"currentColor\"><path d=\"M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z\"/></svg>';h.style.cssText='position:absolute;width:'+(10+Math.random()*14)+'px;height:'+(10+Math.random()*14)+'px;pointer-events:none;opacity:0;color:#10b981;';c.appendChild(h);const x=(Math.random()-0.5)*50,y=-30-Math.random()*40,r=(Math.random()-0.5)*40,d=900+Math.random()*600;h.animate([{transform:'translateX(-50%)translateY(0)scale(0.3)rotate(0deg)',opacity:0},{transform:'translateX(calc(-50% + '+x+'px))translateY('+(y*0.4)+'px)scale(1)rotate('+(r*0.3)+'deg)',opacity:1,offset:0.25},{transform:'translateX(calc(-50% + '+(x*1.5)+'px))translateY('+y+'px)scale(0.2)rotate('+r+'deg)',opacity:0}],{duration:d,easing:'cubic-bezier(0.25,0.46,0.45,0.94)',fill:'forwards'});setTimeout(()=>h.remove(),d+50);} } }"
                            @click="floatHearts()"
                        @endif
                    >
                        @if($freq === 'monthly')
                            <span class="relative flex items-center heart-container">
                                <svg class="size-5 text-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </span>
                        @endif
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="grid grid-cols-3 gap-3">
                @foreach($this->currentPresets as $preset)
                    <button type="button" wire:click="selectPreset({{ $preset }})" class="rounded-lg border-2 px-4 py-3 text-sm font-semibold transition {{ $amount == $preset && !$customAmount ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-200 text-slate-700 hover:border-slate-300' }}">
                        {{ $this->currencySymbol }}{{ $preset }}
                    </button>
                @endforeach
            </div>

            <div class="relative flex items-stretch rounded-xl border border-slate-300 bg-white overflow-hidden focus-within:border-slate-500 focus-within:ring-2 focus-within:ring-slate-200">
                <span class="flex items-center pl-4 text-slate-400 text-lg select-none">{{ $this->currencySymbol }}</span>
                <input 
                    wire:model="amount" 
                    type="text" 
                    inputmode="decimal"
                    @input="
                        let el = $event.target;
                        let val = el.value.replace(/[^\d.]/g, '');
                        let parts = val.split('.');
                        
                        if (parts.length > 2) {
                            val = parts[0] + '.' + parts.slice(1).join('');
                            parts = val.split('.');
                        }
                        
                        if (parts[0].length > 5) {
                            parts[0] = parts[0].substring(0, 5);
                        }
                        
                        if (parts[1] && parts[1].length > 2) {
                            parts[1] = parts[1].substring(0, 2);
                        }
                        
                        let formatted = parts.join('.');
                        if (el.value !== formatted) {
                            el.value = formatted;
                            $wire.amount = formatted;
                        }
                    "
                    class="block w-full border-0 bg-transparent py-3.5 pl-4 pr-4 text-lg text-slate-900 focus:outline-none focus:ring-0" 
                    placeholder="0.00"
                >
                <select :value="$wire.currency" wire:change="setCurrency($event.target.value)" class="border-0 border-l border-slate-200 bg-transparent px-4 py-3.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-0 cursor-pointer hover:bg-slate-50">
                    <option value="USD">🇺🇸 USD</option>
                    <option value="EUR">🇪🇺 EUR</option>
                    <option value="GBP">🇬🇧 GBP</option>
                    <option value="SGD">🇸🇬 SGD</option>
                    <option value="MYR">🇲🇾 MYR</option>
                </select>
            </div>

            @error('amount')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-end px-6 py-4 bg-slate-50 border-t border-slate-200">
            <button wire:click="nextStep" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">Continue</button>
        </div>
    </div>
    @endif

    {{-- Step 3: Personal Information --}}
    @if($step === 2)
    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold">Step 2: Your information</h2>
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
                    <label for="phone" class="block text-sm font-medium text-slate-700">Phone (optional)</label>
                    <input wire:model="phone" id="phone" type="tel" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="+1 234 567 890">
                </div>
            </div>

            {{-- Address toggle --}}
            <div class="pt-2">
                <button type="button" wire:click="$toggle('showAddress')" class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                    <x-icon name="settings" class="size-4" />
                    {{ $showAddress ? 'Hide address' : 'Add address (optional)' }}
                </button>
                @if($showAddress)
                    <div class="mt-4 space-y-4 rounded-lg bg-slate-50 p-4">
                        <div><label for="country" class="block text-sm font-medium text-slate-700">Country</label><input wire:model="country" id="country" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"></div>
                        <div><label for="addressLine1" class="block text-sm font-medium text-slate-700">Address line 1</label><input wire:model="addressLine1" id="addressLine1" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"></div>
                        <div><label for="addressLine2" class="block text-sm font-medium text-slate-700">Address line 2</label><input wire:model="addressLine2" id="addressLine2" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div><label for="city" class="block text-sm font-medium text-slate-700">City</label><input wire:model="city" id="city" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"></div>
                            <div><label for="state" class="block text-sm font-medium text-slate-700">State</label><input wire:model="state" id="state" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"></div>
                            <div><label for="postalCode" class="block text-sm font-medium text-slate-700">Postal code</label><input wire:model="postalCode" id="postalCode" type="text" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex justify-between px-6 py-4 bg-slate-50 border-t border-slate-200">
            <button wire:click="prevStep" class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Back</button>
            <button wire:click="nextStep" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">Continue</button>
        </div>
    </div>
    @endif

    {{-- Step 3: Payment --}}
    @if($step === 3)
    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold">Step 3: Payment method</h2>
        </div>
        <div class="px-6 py-5 space-y-2">
            <label class="flex items-center gap-3 rounded-lg border-2 px-4 py-3 cursor-pointer transition {{ $paymentMethod === 'credit_card' ? 'border-emerald-600 bg-emerald-50' : 'border-slate-200 hover:border-slate-300' }}">
                <input type="radio" wire:model="paymentMethod" value="credit_card" class="size-4 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm font-medium text-slate-700">Credit card</span>
            </label>
            <label class="flex items-center gap-3 rounded-lg border-2 px-4 py-3 cursor-pointer transition {{ $paymentMethod === 'paypal' ? 'border-emerald-600 bg-emerald-50' : 'border-slate-200 hover:border-slate-300' }}">
                <input type="radio" wire:model="paymentMethod" value="paypal" class="size-4 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm font-medium text-slate-700">PayPal</span>
            </label>
            <label class="flex items-center gap-3 rounded-lg border-2 px-4 py-3 cursor-pointer transition {{ $paymentMethod === 'bank_transfer' ? 'border-emerald-600 bg-emerald-50' : 'border-slate-200 hover:border-slate-300' }}">
                <input type="radio" wire:model="paymentMethod" value="bank_transfer" class="size-4 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm font-medium text-slate-700">Bank transfer</span>
            </label>
            @error('paymentMethod')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror

            {{-- Cover transaction cost --}}
            <label class="flex items-start gap-3 cursor-pointer rounded-lg border border-slate-200 px-4 py-3 hover:bg-slate-50 transition mt-2">
                <input wire:model="coverTransactionFee" type="checkbox" class="mt-0.5 size-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <div>
                    <span class="text-sm font-medium text-slate-700 block">Cover transaction cost</span>
                    <span class="text-xs text-slate-500">Add {{ $this->currencySymbol }}{{ number_format($this->processingFee, 2) }} (3%) to cover the transaction cost so 100% of your donation goes to the campaign.</span>
                </div>
            </label>
        </div>
        <div class="flex justify-between px-6 py-4 bg-slate-50 border-t border-slate-200">
            <button wire:click="prevStep" class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Back</button>
            <button wire:click="nextStep" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">Continue</button>
        </div>
    </div>
    @endif

    {{-- Step 4: Review --}}
    @if($step === 4)
    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold">Step 4: Review your donation</h2>
        </div>
        <div class="px-6 py-5 space-y-6">

            {{-- Summary Card --}}
            <div class="rounded-lg bg-slate-50 p-5 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-slate-500">Campaign</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $campaignName ?? '—' }}</span>
                </div>
                @if($coverTransactionFee)
                <div class="flex justify-between">
                    <span class="text-sm text-slate-500">Donation</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $this->currencySymbol }}{{ number_format($amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-slate-500">Transaction cost (3%)</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $this->currencySymbol }}{{ number_format($this->processingFee, 2) }}</span>
                </div>
                <div class="border-t border-slate-200 pt-2 mt-1 flex justify-between">
                    <span class="text-sm font-semibold text-slate-900">Total</span>
                    <span class="text-sm font-bold text-slate-900">{{ $this->currencySymbol }}{{ number_format($this->paymentTotal, 2) }}</span>
                </div>
                @else
                <div class="flex justify-between">
                    <span class="text-sm text-slate-500">Amount</span>
                    <span class="text-sm font-semibold text-slate-900">{{ match(strtoupper($currency)) { 'USD' => '🇺🇸', 'EUR' => '🇪🇺', 'GBP' => '🇬🇧', 'SGD' => '🇸🇬', 'MYR' => '🇲🇾', default => '' } }} {{ $this->currencySymbol }}{{ number_format($amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-sm text-slate-500">Frequency</span>
                    <span class="text-sm font-semibold text-slate-900">{{ ucfirst($frequency) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-slate-500">Payment method</span>
                    <span class="text-sm font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $paymentMethod)) }}</span>
                </div>
                <div class="border-t border-slate-200 pt-3 mt-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500">Donor</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $firstName }} {{ $lastName }}</span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-sm text-slate-500">Email</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $email }}</span>
                    </div>
                </div>
            </div>

            {{-- Agreement --}}
            <label class="flex items-start gap-3 cursor-pointer">
                <input wire:model="agreed" type="checkbox" class="mt-0.5 size-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-slate-600">I agree to the terms and conditions. My donation will be processed securely.</span>
            </label>
            @error('agreed')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-between px-6 py-4 bg-slate-50 border-t border-slate-200">
            <button wire:click="prevStep" class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Back</button>
            <button
                wire:click="submit"
                wire:loading.attr="disabled"
                class="rounded-lg bg-emerald-600 px-8 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition disabled:opacity-50"
            >
                <span wire:loading.remove>Complete donation</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </div>
    @endif

    @endif
</div>
