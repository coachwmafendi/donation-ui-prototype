<div
    x-data="{
        active: localStorage.getItem('donationLastSection') || 'donation',
        showRefundModal: false,

        init() {
            const sections = document.querySelectorAll('[data-section]')

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.active = entry.target.id
                        localStorage.setItem('donationLastSection', this.active)
                    }
                })
            }, {
                rootMargin: '-25% 0px -65% 0px',
                threshold: 0
            })

            sections.forEach((section) => {
                observer.observe(section)
            })

            this.$nextTick(() => {
                const last = localStorage.getItem('donationLastSection')
                if (last && last !== 'donation') {
                    this.scrollToSection(last)
                }
            })
        },

        scrollToSection(id) {
            document.getElementById(id)?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            })
        }
    }"
    x-on:refunded.window="showRefundModal = false"
    class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900"
>
    <div class="mx-auto max-w-7xl">

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm font-medium text-slate-500">
            <a href="/donations" class="hover:text-slate-900">Donations</a> >
        </div>

        {{-- Header --}}
        <div class="mb-10 border-b border-slate-200 pb-8">
            <h1 class="text-4xl font-bold tracking-tight">
                {{ $donationModel->amount }} donation
            </h1>

            <div class="mt-3 flex items-center gap-3 text-slate-600">
                <span>ID {{ $donationModel->public_id }}</span>
                <x-copy-button :text="$donationModel->public_id" class="ml-2">Copy</x-copy-button>
                <span>·</span>
                @if($donationModel->converted_amount)
                    <span>≈ {{ $donationModel->converted_amount }}</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">

            {{-- LEFT CONTENT --}}
            <main class="space-y-6 lg:col-span-9">

                {{-- Donation Section --}}
                <section
                    id="donation"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">$</span>
                            <h2 class="text-xl font-semibold">Donation</h2>
                        </div>
                        <button class="text-sm text-slate-400 hover:text-slate-700">Edit</button>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <x-detail-row label="Donation amount">
                            {{ $donationModel->amount }}
                            @if($donationModel->converted_amount)
                                <span class="ml-2 text-slate-400">≈ {{ $donationModel->converted_amount }}</span>
                            @endif
                        </x-detail-row>

                        <x-detail-row label="Donation ID">
                            <span class="font-mono">{{ $donationModel->public_id }}</span>
                            <x-copy-button :text="$donationModel->public_id" class="ml-2">Copy</x-copy-button>
                        </x-detail-row>

                        <x-detail-row label="Status">
                            <x-status-badge :status="$donationModel->status" />
                        </x-detail-row>

                        <x-detail-row label="Supporter">
                            <a href="#" class="font-medium text-blue-600 hover:underline">
                                {{ $donationModel->profile->full_name ?? 'Unknown' }}
                            </a>
                        </x-detail-row>

                        <x-detail-row label="Campaign">
                            <a href="#" class="font-medium text-blue-600 hover:underline">
                                {{ $donationModel->campaign }}
                            </a>
                        </x-detail-row>

                        <x-detail-row label="Designation">
                            {{ $donationModel->designation ?? '—' }}
                        </x-detail-row>

                        <x-detail-row label="Donation date">
                            {{ $donationModel->donation_date->format('M d, Y, g:i A') }}
                        </x-detail-row>

                        <x-detail-row label="Success date">
                            {{ $donationModel->success_date ? $donationModel->success_date->format('M d, Y, g:i A') : '—' }}
                        </x-detail-row>

                        <x-detail-row label="Frequency">
                            {{ ucfirst($donationModel->frequency) }}
                        </x-detail-row>
                    </div>
                </section>

                {{-- Payment Fees Section --}}
                @if($donationModel->payment_amount_cents || $donationModel->processing_fee_cents || $donationModel->net_amount_cents)
                <section
                    id="payment-fees"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">%</span>
                            <h2 class="text-xl font-semibold">Payment & fees</h2>
                        </div>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        @if($donationModel->payment_amount_cents)
                        <x-detail-row label="Payment amount">
                            {{ $donationModel->payment_amount }}
                            @if($donationModel->converted_amount)
                                <span class="ml-2 text-slate-400">≈ {{ $donationModel->converted_amount }}</span>
                            @endif
                        </x-detail-row>
                        @endif

                        @if($donationModel->processing_fee_cents)
                        <x-detail-row label="Processing fee">
                            {{ $donationModel->processing_fee }}
                        </x-detail-row>
                        @endif

                        @if($donationModel->net_amount_cents)
                        <x-detail-row label="Net amount">
                            {{ $donationModel->net_amount }}
                        </x-detail-row>
                        @endif
                    </div>
                </section>
                @endif

                {{-- Recurring Plan Section --}}
                @if($donationModel->frequency !== 'one-time')
                <x-simple-section id="recurring-plan" title="Recurring plan" icon="↻">
                    <x-detail-row label="Plan">
                        {{ ucfirst($donationModel->frequency) }} giving
                    </x-detail-row>
                    <x-detail-row label="Next payment">
                        {{ $donationModel->donation_date->addMonth()->format('M d, Y') }}
                    </x-detail-row>
                </x-simple-section>
                @endif

                {{-- Personal Information Section --}}
                @if($donationModel->profile)
                <x-simple-section id="personal-information" title="Personal information" icon="👤">
                    <x-detail-row label="Full name">
                        {{ $donationModel->profile->full_name }}
                    </x-detail-row>

                    <x-detail-row label="Email">
                        {{ $donationModel->profile->email }}
                    </x-detail-row>

                    @if($donationModel->profile->phone)
                    <x-detail-row label="Phone">
                        {{ $donationModel->profile->phone }}
                    </x-detail-row>
                    @endif

                    @if($donationModel->profile->country)
                    <x-detail-row label="Country">
                        {{ $donationModel->profile->country }}
                    </x-detail-row>
                    @endif

                    @if($donationModel->profile->address_line_1)
                    <x-detail-row label="Address">
                        <p>{{ $donationModel->profile->address_line_1 }}</p>
                        @if($donationModel->profile->address_line_2)
                            <p>{{ $donationModel->profile->address_line_2 }}</p>
                        @endif
                        <p class="text-slate-500">
                            {{ collect([$donationModel->profile->city, $donationModel->profile->state, $donationModel->profile->postal_code])->filter()->join(', ') }}
                        </p>
                    </x-detail-row>
                    @endif
                </x-simple-section>
                @endif

                {{-- Tribute Section --}}
                <x-simple-section id="tribute" title="Tribute" icon="♡">
                    @if($donationModel->tribute_info)
                        <p class="text-slate-700">{{ $donationModel->tribute_info }}</p>
                    @else
                        <p class="text-slate-500">No tribute information.</p>
                    @endif
                </x-simple-section>

                {{-- Comment Section --}}
                <x-simple-section id="comment" title="Comment" icon="💬">
                    @if($donationModel->comment)
                        <p class="text-slate-700">{{ $donationModel->comment }}</p>
                    @else
                        <p class="text-slate-500">No comment from supporter.</p>
                    @endif
                </x-simple-section>

                {{-- Source Section --}}
                <x-simple-section id="source" title="Source" icon="↗">
                    <x-detail-row label="Source">
                        {{ $donationModel->source ? ucfirst(str_replace('_', ' ', $donationModel->source)) : '—' }}
                    </x-detail-row>

                    <x-detail-row label="Device">
                        {{ $donationModel->device ? ucfirst($donationModel->device) : '—' }}
                    </x-detail-row>

                    <x-detail-row label="Payment method">
                        {{ $donationModel->payment_method ? ucfirst(str_replace('_', ' ', $donationModel->payment_method)) : '—' }}
                    </x-detail-row>
                </x-simple-section>

                {{-- Insights Section --}}
                <x-simple-section id="insights" title="Insights" icon="⌁">
                    <x-detail-row label="Donor type">
                        {{ $donationModel->donor_type ? ucfirst(str_replace('_', ' ', $donationModel->donor_type)) : '—' }}
                    </x-detail-row>
                </x-simple-section>

                {{-- UTM Parameters Section --}}
                @if($donationModel->utm_source || $donationModel->utm_campaign)
                <x-simple-section id="utm-parameters" title="UTM parameters" icon="#">
                    @if($donationModel->utm_source)
                    <x-detail-row label="utm_source">
                        {{ $donationModel->utm_source }}
                    </x-detail-row>
                    @endif

                    @if($donationModel->utm_campaign)
                    <x-detail-row label="utm_campaign">
                        {{ $donationModel->utm_campaign }}
                    </x-detail-row>
                    @endif
                </x-simple-section>
                @endif

                {{-- Custom Fields Section --}}
                <x-simple-section id="custom-fields" title="Custom fields" icon="⚙">
                    @if($donationModel->custom_fields && count($donationModel->custom_fields) > 0)
                        @foreach($donationModel->custom_fields as $key => $value)
                        <x-detail-row label="{{ $key }}">
                            {{ $value }}
                        </x-detail-row>
                        @endforeach
                    @else
                        <p class="text-slate-500">No custom fields.</p>
                    @endif
                </x-simple-section>

                {{-- Emails Section --}}
                <x-simple-section id="emails" title="Emails" icon="✉">
                    <x-detail-row label="Receipt email">
                        {{ $donationModel->receipt_email_sent ? 'Sent' : 'Not sent' }}
                    </x-detail-row>

                    <x-detail-row label="Thank you email">
                        {{ $donationModel->thank_you_email_sent ? 'Sent' : 'Not sent' }}
                    </x-detail-row>
                </x-simple-section>

            </main>

            {{-- RIGHT SIDEBAR --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-6 space-y-6">

                    {{-- Action Box --}}
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <button class="flex w-full items-center gap-3 border-b border-slate-200 px-5 py-4 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">
                            <svg class="size-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            <span>Download receipt</span>
                        </button>

                        <button 
                            @click="showRefundModal = true"
                            class="flex w-full items-center gap-3 px-5 py-4 text-left text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="1 4 1 10 7 10"/>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                            </svg>
                            <span>Refund donation</span>
                        </button>
                    </div>

                    {{-- Navigation --}}
                    <nav class="rounded-xl border border-slate-200 bg-white p-2">
                        @foreach ($sections as $section)
                            <button
                                type="button"
                                @click="scrollToSection('{{ $section['id'] }}')"
                                class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-sm transition"
                                :class="active === '{{ $section['id'] }}'
                                    ? 'bg-slate-100 font-semibold text-slate-900'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            >
                                <span class="w-5 text-center">{{ $section['icon'] }}</span>
                                <span>{{ $section['label'] }}</span>
                            </button>
                        @endforeach
                    </nav>

                </div>
            </aside>

        </div>
    </div>

    {{-- Refund Modal --}}
    <div
        x-show="showRefundModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
        @click.self="showRefundModal = false"
    >
        <div
            x-show="showRefundModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Refund donation</h3>
                <button @click="showRefundModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-6 space-y-5">
                <p class="text-sm text-slate-700">
                    Are you sure you want to refund 100% of the donation to the supporter?
                </p>

                <div>
                    <label for="refund_reason" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Refund reason
                    </label>
                    <select
                        wire:model="refundReason"
                        id="refund_reason"
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                        <option value="">Select reason</option>
                        <option value="duplicate">Duplicate donation</option>
                        <option value="fraud">Fraud</option>
                        <option value="supporter_request">Requested by supporter</option>
                        <option value="other">Other</option>
                    </select>
                    @error('refundReason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <a href="#" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800">
                    Learn more about refunds
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                </a>
            </div>

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                <button
                    @click="showRefundModal = false"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                >
                    Cancel
                </button>
                <button
                    wire:click="refund"
                    wire:loading.attr="disabled"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition disabled:opacity-50"
                >
                    <span wire:loading.remove>Refund donation</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </div>
    </div>
</div>
