<div
    x-data="{
        activeTab: 'overview',
        showArchiveModal: false,
    }"
    class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900"
>
    <div class="mx-auto max-w-5xl">

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm font-medium text-slate-500">
            <a href="/campaigns" class="hover:text-slate-900">Campaigns</a> >
        </div>

        {{-- Header --}}
        <div class="mb-6 border-b border-slate-200 pb-8">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight">{{ $campaign->name }}</h1>
                    <div class="mt-3 flex items-center gap-3 text-slate-600">
                        <span>ID {{ $campaign->public_id }}</span>
                        <x-copy-button :text="$campaign->public_id" class="ml-2">Copy</x-copy-button>
                        <span>·</span>
                        <x-status-badge :status="$campaign->status" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="mb-8 border-b border-slate-200">
            <nav class="flex gap-1 -mb-px" aria-label="Tabs">
                @foreach([
                    ['id' => 'overview', 'label' => 'Overview'],
                    ['id' => 'settings', 'label' => 'Settings'],
                    ['id' => 'embed', 'label' => 'Embed Code'],
                    ['id' => 'page', 'label' => 'Campaign Page'],
                    ['id' => 'actions', 'label' => 'Actions'],
                ] as $tab)
                    <button
                        type="button"
                        @click="activeTab = '{{ $tab['id'] }}'"
                        class="px-5 py-3 text-sm font-medium border-b-2 transition"
                        :class="activeTab === '{{ $tab['id'] }}'
                            ? 'border-slate-900 text-slate-900'
                            : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    >
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Tab: Overview --}}
        <div x-show="activeTab === 'overview'" class="space-y-6">

            {{-- Progress Card --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-semibold mb-4">Fundraising progress</h2>
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="font-medium text-slate-900">{{ $campaign->progress_percentage }}% of goal</span>
                    <span class="text-slate-500">{{ $campaign->raised_amount }} / {{ $campaign->goal_amount }}</span>
                </div>
                <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $campaign->progress_percentage }}%"></div>
                </div>
                <div class="mt-5 grid grid-cols-3 gap-4">
                    <div class="rounded-lg bg-slate-50 p-4 text-center">
                        <div class="text-2xl font-bold text-slate-900">{{ $campaign->goal_amount }}</div>
                        <div class="mt-1 text-xs font-medium text-slate-500 uppercase tracking-wider">Goal</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4 text-center">
                        <div class="text-2xl font-bold text-slate-900">{{ $campaign->raised_amount }}</div>
                        <div class="mt-1 text-xs font-medium text-slate-500 uppercase tracking-wider">Raised</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4 text-center">
                        <div class="text-2xl font-bold text-slate-900">{{ $campaign->donor_count }}</div>
                        <div class="mt-1 text-xs font-medium text-slate-500 uppercase tracking-wider">Donors</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Campaign Info --}}
                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Campaign details</h2>
                    </div>
                    <div class="space-y-4 px-6 py-5">
                        <x-detail-row label="Campaign name">{{ $campaign->name }}</x-detail-row>
                        <x-detail-row label="Public ID"><span class="font-mono text-sm">{{ $campaign->public_id }}</span> <x-copy-button :text="$campaign->public_id" class="ml-2">Copy</x-copy-button></x-detail-row>
                        <x-detail-row label="Slug">{{ $campaign->slug }}</x-detail-row>
                        <x-detail-row label="Status"><x-status-badge :status="$campaign->status" /></x-detail-row>
                        <x-detail-row label="Description">{{ $campaign->description ?? 'No description provided.' }}</x-detail-row>
                        <x-detail-row label="Date range">
                            @if($campaign->start_date && $campaign->end_date)
                                {{ $campaign->start_date->format('M d, Y') }} — {{ $campaign->end_date->format('M d, Y') }}
                            @else
                                <span class="text-slate-400">No date range set</span>
                            @endif
                        </x-detail-row>
                        <x-detail-row label="Currency">{{ $campaign->currency }}</x-detail-row>
                    </div>
                </div>

                {{-- Recent Donations --}}
                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Recent donations</h2>
                        <span class="text-sm text-slate-500">{{ $campaign->donations_count }} total</span>
                    </div>
                    <div class="divide-y divide-slate-200">
                        @forelse ($recentDonations as $donation)
                            <a href="/donations/{{ $donation->public_id }}" wire:navigate class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-medium text-slate-600">{{ substr($donation->profile?->first_name ?? '?', 0, 1) }}</div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">{{ $donation->profile?->full_name ?? 'Anonymous' }}</div>
                                        <div class="text-xs text-slate-500">{{ $donation->donation_date->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-slate-900">{{ $donation->amount }}</div>
                                    <x-status-badge :status="$donation->status" />
                                </div>
                            </a>
                        @empty
                            <div class="px-6 py-10 text-center">
                                <p class="text-sm text-slate-500">No donations yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Settings --}}
        <div
            x-show="activeTab === 'settings'"
            x-data="{
                settingTab: 'general',
                allOptions: [
                    { id: 'one-time', label: 'Once' },
                    { id: 'monthly', label: 'Monthly' },
                    { id: 'weekly', label: 'Weekly' },
                    { id: 'yearly', label: 'Yearly' },
                    { id: 'quarterly', label: 'Quarterly' },
                ],
                get frequencies() {
                    return $wire.frequencies.map(id => {
                        const opt = this.allOptions.find(o => o.id === id)
                        return opt || { id, label: id }
                    })
                },
                set frequencies(val) {
                    $wire.frequencies = val.map(f => f.id)
                },
                amountsFreq: $wire.frequencies[0] || 'one-time',

                addFrequency() {
                    const used = $wire.frequencies
                    const available = this.allOptions.filter(o => !used.includes(o.id))
                    if (available.length > 0) {
                        $wire.frequencies.push(available[0].id)
                    }
                },

                removeFrequency(index) {
                    const removedId = $wire.frequencies[index]
                    $wire.frequencies.splice(index, 1)
                    if ($wire.defaultFrequency === removedId && $wire.frequencies.length > 0) {
                        $wire.defaultFrequency = $wire.frequencies[0]
                    }
                    if ($wire.frequencies.length > 0 && !$wire.frequencies.includes(this.amountsFreq)) {
                        this.amountsFreq = $wire.frequencies[0]
                    }
                },

                changeFrequency(index, newId) {
                    const oldId = $wire.frequencies[index]
                    $wire.frequencies[index] = newId
                    if ($wire.defaultFrequency === oldId) {
                        $wire.defaultFrequency = newId
                    }
                },

                availableOptions(currentId) {
                    const used = $wire.frequencies.filter(id => id !== currentId)
                    return this.allOptions.filter(o => !used.includes(o.id))
                },
            }"
            class="grid grid-cols-1 lg:grid-cols-12 gap-6"
            x-cloak
        >
            {{-- Vertical Tabs --}}
            <nav class="lg:col-span-3 space-y-1">
                @php
                    $settingTabs = [
                        ['id' => 'general', 'label' => 'General'],
                        ['id' => 'payment', 'label' => 'Payment Method'],
                        ['id' => 'currency', 'label' => 'Currency'],
                        ['id' => 'frequency', 'label' => 'Frequency'],
                        ['id' => 'amounts', 'label' => 'Suggested Amounts'],
                        ['id' => 'minimum', 'label' => 'Minimum Amount'],
                        ['id' => 'cost', 'label' => 'Transaction Cost'],
                    ];
                @endphp

                @foreach($settingTabs as $item)
                    <button
                        type="button"
                        @click="settingTab = '{{ $item['id'] }}'"
                        class="w-full text-left rounded-lg px-4 py-3 text-sm font-medium transition flex items-center gap-3"
                        :class="settingTab === '{{ $item['id'] }}'
                            ? 'bg-slate-100 text-slate-900'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        @if($item['id'] === 'general')
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l-.22.38a2 2 0 0 0 2.73-.73l.15.08a2 2 0 0 1 2 0l.43-.25a2 2 0 0 1 1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                        @elseif($item['id'] === 'payment')
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        @elseif($item['id'] === 'currency')
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        @elseif($item['id'] === 'frequency')
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                        @elseif($item['id'] === 'amounts')
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        @elseif($item['id'] === 'minimum')
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 9-5 5-5-5"/><path d="M12 4v10"/></svg>
                        @elseif($item['id'] === 'cost')
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" x2="5" y1="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </button>
                @endforeach
            </nav>

            {{-- Setting Panels --}}
            <div class="lg:col-span-9">

                {{-- General --}}
                <div x-show="settingTab === 'general'" class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">General</h2>
                    </div>
                    <div class="px-6 py-5 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Campaign name</label>
                            <input type="text" value="{{ $campaign->name }}" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Slug</label>
                            <input type="text" value="{{ $campaign->slug }}" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Description</label>
                            <textarea rows="3" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" readonly>{{ $campaign->description }}</textarea>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition">Save Changes</button>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div x-show="settingTab === 'payment'" class="rounded-xl border border-slate-200 bg-white overflow-hidden" x-cloak>
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Payment Method</h2>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <label class="flex items-center gap-3 rounded-lg border-2 border-slate-200 px-4 py-3 cursor-pointer">
                            <input type="checkbox" wire:model="paymentMethods" value="credit_card" class="size-4 rounded border-slate-300 text-slate-900">
                            <span class="text-sm font-medium text-slate-700">Credit card</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-lg border-2 border-slate-200 px-4 py-3 cursor-pointer">
                            <input type="checkbox" wire:model="paymentMethods" value="paypal" class="size-4 rounded border-slate-300 text-slate-900">
                            <span class="text-sm font-medium text-slate-700">PayPal</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-lg border-2 border-slate-200 px-4 py-3 cursor-pointer">
                            <input type="checkbox" wire:model="paymentMethods" value="bank_transfer" class="size-4 rounded border-slate-300 text-slate-900">
                            <span class="text-sm font-medium text-slate-700">Bank transfer</span>
                        </label>
                        <div class="flex justify-end pt-2">
                            <button wire:click="savePaymentMethods" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Currency --}}
                <div x-show="settingTab === 'currency'" class="rounded-xl border border-slate-200 bg-white overflow-hidden" x-cloak>
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Currency</h2>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Default currency</label>
                            <select wire:model="currency" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="USD">USD — US Dollar</option>
                                <option value="EUR">EUR — Euro</option>
                                <option value="GBP">GBP — British Pound</option>
                                <option value="SGD">SGD — Singapore Dollar</option>
                                <option value="MYR">MYR — Malaysian Ringgit</option>
                            </select>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button wire:click="saveCurrency" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Frequency --}}
                <div
                    x-show="settingTab === 'frequency'"
                    class="rounded-xl border border-slate-200 bg-white overflow-hidden"
                    x-cloak
                >
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Frequencies</h2>
                    </div>
                    <div class="px-6 py-5 space-y-4">

                        <template x-for="(freq, index) in frequencies" :key="index">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 relative">
                                    <select
                                        x-model="freq.id"
                                        @change="changeFrequency(index, $event.target.value)"
                                        class="block w-full appearance-none rounded-xl border-2 border-slate-200 bg-white px-4 py-3.5 pr-10 text-base text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 transition cursor-pointer"
                                    >
                                        <template x-for="opt in availableOptions(freq.id)" :key="opt.id">
                                            <option
                                                :value="opt.id"
                                                :selected="opt.id === freq.id"
                                                x-text="opt.label"
                                            ></option>
                                        </template>
                                    </select>
                                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 size-5 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                        </template>

                        <template x-if="frequencies.length === 0">
                            <p class="text-sm text-slate-500 py-4">No frequencies selected.</p>
                        </template>

                        <hr class="border-slate-200">

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 mb-3">Default frequency</h3>
                            <div class="space-y-2">
                                <template x-for="(freq, index) in frequencies" :key="freq.id">
                                    <label class="flex items-center gap-3 rounded-lg border-2 px-4 py-3 cursor-pointer transition"
                                        :class="$wire.defaultFrequency === freq.id ? 'border-slate-900 bg-slate-50' : 'border-slate-200'"
                                    >
                                        <input type="radio" x-model="$wire.defaultFrequency" :value="freq.id" class="size-4 text-slate-900 focus:ring-slate-500">
                                        <span class="text-sm font-medium text-slate-700" x-text="freq.label"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button wire:click="saveFrequencies" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Suggested Amounts --}}
                <div
                    x-show="settingTab === 'amounts'"
                    class="rounded-xl border border-slate-200 bg-white overflow-hidden"
                    x-cloak
                >
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Suggested Amounts</h2>
                    </div>
                    <div class="px-6 py-5 space-y-6">

                        {{-- Frequency sub-tabs --}}
                        <div class="flex gap-4 border-b border-slate-200">
                            <template x-for="freq in frequencies" :key="freq.id">
                                <button
                                    type="button"
                                    @click="amountsFreq = freq.id"
                                    class="pb-3 text-sm font-semibold tracking-wide border-b-2 transition -mb-px uppercase"
                                    :class="amountsFreq === freq.id
                                        ? 'border-blue-600 text-blue-600'
                                        : 'border-transparent text-slate-500 hover:text-slate-700'"
                                    x-text="freq.label"
                                ></button>
                            </template>
                        </div>

                         {{-- Preset inputs per frequency --}}
                         @foreach($frequencies as $freqId)
                             <div x-show="amountsFreq === '{{ $freqId }}'" x-cloak>
                                 <h3 class="text-base font-semibold text-slate-900 mb-3">Suggested donation amount presets</h3>
                                 <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                     @php
                                         $freqPresets = $frequencyPresets[$freqId] ?? [50, 25, 10];
                                     @endphp
                                     @foreach($freqPresets as $index => $preset)
                                         <div class="relative">
                                             <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">$</span>
                                             <input
                                                 type="number"
                                                 wire:model="frequencyPresets.{{ $freqId }}.{{ $index }}"
                                                 class="block w-full rounded-lg border border-slate-300 bg-white pl-7 pr-3 py-2.5 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                                 placeholder="0"
                                             >
                                         </div>
                                     @endforeach
                                 </div>
                             </div>
                         @endforeach

                        {{-- Default amount --}}
                        <template x-for="freq in frequencies" :key="freq.id">
                            <div x-show="amountsFreq === freq.id" x-cloak>
                                <h3 class="text-base font-semibold text-slate-900 mb-3">
                                    Default <span x-text="freq.label.toLowerCase()"></span> suggested amount
                                </h3>
                                <div class="relative max-w-xs">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">$</span>
                                    <input
                                        type="number"
                                        x-model.number="$wire.defaultAmounts[freq.id]"
                                        class="block w-full rounded-lg border border-slate-300 bg-white pl-7 pr-3 py-2.5 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                        placeholder="0"
                                    >
                                </div>
                            </div>
                        </template>

                        <div class="flex justify-end pt-2">
                            <button wire:click="saveAmounts" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Minimum Amount --}}
                <div x-show="settingTab === 'minimum'" class="rounded-xl border border-slate-200 bg-white overflow-hidden" x-cloak>
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Minimum Amount</h2>
                    </div>
                    <div class="px-6 py-5 space-y-6">
                        <template x-for="freq in frequencies" :key="freq.id">
                            <div>
                                <label class="block text-base font-semibold text-slate-900">
                                    Minimum <span x-text="freq.label.toLowerCase()"></span> donation
                                </label>
                                <div class="relative mt-2 max-w-xs">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-lg font-medium">$</span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        x-model.number="$wire.minAmounts[freq.id]"
                                        class="block w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-4 py-3 text-lg text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 transition"
                                    >
                                </div>
                            </div>
                        </template>
                        <div class="flex justify-end pt-2">
                            <button wire:click="saveMinimums" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Transaction Cost --}}
                <div x-show="settingTab === 'cost'" class="rounded-xl border border-slate-200 bg-white overflow-hidden" x-cloak>
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Transaction Cost</h2>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="coverFee" wire:model="coverFee" class="size-4 rounded border-slate-300 text-slate-900">
                            <label for="coverFee" class="text-sm font-medium text-slate-700">Ask donor to cover transaction fee</label>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button wire:click="saveTransactionCost" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Tab: Embed Code --}}
        <div x-show="activeTab === 'embed'" class="max-w-2xl" x-cloak>
            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Embed on your website</h2>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <p class="text-sm text-slate-600">Copy this code and paste it into your website HTML to embed a donation form for this campaign.</p>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">iframe code</label>
                            <div class="relative inline-block" x-data="{ copied: false, timer: null }">
                                <button
                                    type="button"
                                    @click="
                                        navigator.clipboard.writeText(document.getElementById('embed-code').innerText).then(() => {
                                            copied = true;
                                            clearTimeout(timer);
                                            timer = setTimeout(() => copied = false, 2000);
                                        })
                                    "
                                    class="text-xs font-medium text-blue-600 hover:text-blue-800"
                                >Copy</button>
                                <div
                                    x-show="copied"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-75 -translate-y-1"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 scale-75 -translate-y-1"
                                    class="pointer-events-none absolute bottom-full left-1/2 mb-2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-2.5 py-1 text-xs font-medium text-white shadow-lg"
                                    style="display: none;"
                                >
                                    Copied!
                                    <div class="absolute left-1/2 top-full -translate-x-1/2 border-4 border-transparent border-t-slate-900"></div>
                                </div>
                            </div>
                        </div>
                        <pre id="embed-code" class="rounded-lg bg-slate-50 border border-slate-200 text-slate-700 p-4 text-xs overflow-x-auto whitespace-pre-wrap">&lt;iframe
  src="{{ config('app.url') }}/embed/{{ $campaign->slug }}"
  width="100%"
  height="650"
  frameborder="0"
  style="border: none; border-radius: 12px;"
  title="Donate to {{ $campaign->name }}"
&gt;&lt;/iframe&gt;</pre>
                    </div>

                    <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                        <p class="text-sm text-amber-800"><strong>Note:</strong> Make sure <code class="bg-amber-100 px-1 rounded">APP_URL</code> is set to your production domain before sharing this embed code.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Campaign Page --}}
        <div x-show="activeTab === 'page'" class="max-w-5xl" x-cloak>
            <div class="space-y-6">

                {{-- Preview & URL Card --}}
                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Public campaign page</h2>
                    </div>
                    <div class="px-6 py-5 space-y-5">
                         <p class="text-sm text-slate-600">This is the public-facing page for donors. Share this link on social media, email, or your website.</p>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Public URL</label>
                            <div class="mt-2 flex items-center gap-3">
                                <code class="flex-1 rounded-lg bg-slate-50 border border-slate-200 px-4 py-3 text-sm text-slate-700">{{ config('app.url') }}/c/{{ $campaign->slug }}</code>
                                <x-copy-button :text="config('app.url').'/c/'.$campaign->slug">Copy</x-copy-button>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="/c/{{ $campaign->slug }}" target="_blank" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition">
                                View public page
                            </a>
                            <a href="/donate/{{ $campaign->public_id }}" target="_blank" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                                View donation form
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Settings Form --}}
                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-lg font-semibold">Page Settings</h2>
                    </div>
                    <div class="divide-y divide-slate-200">

                        {{-- Section: Appearance --}}
                        <div class="px-6 py-5">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Appearance</h3>
                            <p class="text-xs text-slate-500 mb-4">Customize the visual style of your campaign page.</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Primary color</label>
                                    <select wire:model="pagePrimaryColor" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                        <option value="emerald">Emerald (default)</option>
                                        <option value="blue">Blue</option>
                                        <option value="amber">Amber</option>
                                        <option value="rose">Rose</option>
                                        <option value="violet">Violet</option>
                                        <option value="cyan">Cyan</option>
                                    </select>
                                </div>
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" wire:model="pageDarkHero" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Use dark hero section</span>
                                </label>
                            </div>
                        </div>

                        {{-- Section: Visibility Toggles --}}
                        <div class="px-6 py-5">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Content Visibility</h3>
                            <p class="text-xs text-slate-500 mb-4">Choose which sections to show on the public page.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="checkbox" wire:model="pageShowProgressBar" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Progress bar</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="checkbox" wire:model="pageShowDonorCount" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Donor count</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="checkbox" wire:model="pageShowGoalAmount" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Goal amount</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="checkbox" wire:model="pageShowDaysLeft" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Days left</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="checkbox" wire:model="pageShowRecentSupporters" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Recent supporters</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="checkbox" wire:model="pageShowCampaignDetails" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Campaign details sidebar</span>
                                </label>
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="checkbox" wire:model="pageShowBottomCta" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Bottom CTA section</span>
                                </label>
                            </div>
                        </div>

                        {{-- Section: Text Customization --}}
                        <div class="px-6 py-5">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Text Customization</h3>
                            <p class="text-xs text-slate-500 mb-4">Customize headings, buttons, and labels across the page. Leave blank to use defaults.</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Hero headline</label>
                                    <input type="text" wire:model="pageHeroHeadline" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="{{ $campaign->name }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Hero subheadline</label>
                                    <textarea wire:model="pageHeroSubheadline" rows="2" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Your support makes a lasting impact."></textarea>
                                    @error('pageHeroSubheadline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Primary CTA text</label>
                                        <input type="text" wire:model="pagePrimaryCtaText" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Donate Now">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Secondary CTA text</label>
                                        <input type="text" wire:model="pageSecondaryCtaText" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Learn More">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Secondary CTA link</label>
                                    <input type="text" wire:model="pageSecondaryCtaLink" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="#about">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">About section heading</label>
                                    <input type="text" wire:model="pageAboutHeading" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="About this campaign">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Recent supporters heading</label>
                                    <input type="text" wire:model="pageRecentSupportersHeading" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Recent supporters">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Bottom CTA headline</label>
                                    <input type="text" wire:model="pageBottomCtaHeadline" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Ready to make an impact?">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Bottom CTA body text</label>
                                    <textarea wire:model="pageBottomCtaBody" rows="2" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Leave blank to use donor count"></textarea>
                                    <p class="mt-1 text-xs text-slate-500">Leave blank to auto-generate from donor count.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Supporter Privacy --}}
                        <div class="px-6 py-5">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">Supporter Privacy</h3>
                            <p class="text-xs text-slate-500 mb-4">Control what donor information is visible on the public page.</p>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" wire:model="pageShowAnonymousDonors" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Show anonymous donors in recent supporters list</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" wire:model="pageShowDonationAmounts" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Show donation amounts in recent supporters list</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" wire:model="pageShowDonorAvatars" class="size-4 rounded border-slate-300 text-slate-900">
                                    <span class="text-sm text-slate-700">Show donor initials/avatars in recent supporters list</span>
                                </label>
                            </div>
                        </div>

                        {{-- Section: SEO / Sharing --}}
                        <div class="px-6 py-5">
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">SEO & Social Sharing</h3>
                            <p class="text-xs text-slate-500 mb-4">Optimize how your campaign appears in search engines and social media.</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">SEO Title</label>
                                    <input type="text" wire:model="pageSeoTitle" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Leave blank to use campaign name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Meta Description</label>
                                    <textarea wire:model="pageSeoDescription" rows="2" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="Brief description for search engines and social cards"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Social Share Image URL</label>
                                    <input type="text" wire:model="pageSocialShareImage" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="https://example.com/image.jpg">
                                    <p class="mt-1 text-xs text-slate-500">Recommended size: 1200 x 630 pixels.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Advanced --}}
                        <div class="px-6 py-5" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="flex w-full items-center justify-between">
                                <div class="text-left">
                                    <h3 class="text-sm font-semibold text-slate-900 mb-1">Advanced</h3>
                                    <p class="text-xs text-slate-500">For advanced users and integrations.</p>
                                </div>
                                <svg class="size-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Custom CSS</label>
                                    <textarea wire:model="pageCustomCss" rows="4" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-mono text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder=".my-class { color: red; }"></textarea>
                                    <p class="mt-1 text-xs text-slate-500">Injected into the public campaign page head.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Custom JavaScript</label>
                                    <textarea wire:model="pageCustomJs" rows="4" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-mono text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="console.log('Hello');"></textarea>
                                    <p class="mt-1 text-xs text-slate-500">Useful for tracking pixels (Meta Pixel, Google Analytics, etc.).</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Redirect after donation</label>
                                    <input type="text" wire:model="pageRedirectAfterDonation" class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200" placeholder="https://yoursite.com/thank-you">
                                    <p class="mt-1 text-xs text-slate-500">Where donors go after completing a donation. Leave blank for default thank-you page.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Save Button --}}
                        <div class="px-6 py-5 bg-slate-50 flex justify-end">
                            <button wire:click="savePageConfig" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                                <span wire:loading.remove>Save Page Settings</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Actions --}}
        <div x-show="activeTab === 'actions'" class="max-w-2xl" x-cloak>
            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Actions</h2>
                </div>
                <div class="divide-y divide-slate-200">
                    <div class="flex items-center justify-between px-6 py-5">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">Edit campaign</h3>
                            <p class="text-sm text-slate-500">Update campaign name, description, goal, and date range.</p>
                        </div>
                        <a href="/campaigns/{{ $campaign->public_id }}/edit" wire:navigate class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            Edit
                        </a>
                    </div>

                    <div class="flex items-center justify-between px-6 py-5">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">Archive campaign</h3>
                            <p class="text-sm text-slate-500">Hide from public view but keep all donation records. Can be reactivated later.</p>
                        </div>
                        <button @click="showArchiveModal = true" class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100 transition">
                            Archive
                        </button>
                    </div>

                    <div class="flex items-center justify-between px-6 py-5">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">Export donations</h3>
                            <p class="text-sm text-slate-500">Download CSV of all donations for this campaign.</p>
                        </div>
                        <button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition opacity-50 cursor-not-allowed" disabled>
                            Coming soon
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Archive Modal --}}
    <div
        x-show="showArchiveModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
        @click.self="showArchiveModal = false"
    >
        <div
            x-show="showArchiveModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Archive campaign</h3>
                <button @click="showArchiveModal = false" class="text-slate-400 hover:text-slate-600">
                    <x-icon name="x" class="size-5" />
                </button>
            </div>
            <div class="px-6 py-6 space-y-4">
                <p class="text-sm text-slate-700">Are you sure you want to archive <strong>{{ $campaign->name }}</strong>? This will hide the campaign from public view but keep all donation records.</p>
                <p class="text-sm text-slate-500">You can reactivate the campaign at any time.</p>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                <button @click="showArchiveModal = false" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Cancel</button>
                <button wire:click="archive" wire:loading.attr="disabled" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50">
                    <span wire:loading.remove>Archive campaign</span>
                    <span wire:loading>Archiving...</span>
                </button>
            </div>
        </div>
    </div>
</div>
