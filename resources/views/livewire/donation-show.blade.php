<div
    x-data="{
        active: localStorage.getItem('donationLastSection') || 'donation',
        copiedSection: null,

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

            // Restore scroll position on page load
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
    class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900"
>
    <div class="mx-auto max-w-7xl">

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm font-medium text-slate-500">
            Donations >
        </div>

        {{-- Header --}}
        <div class="mb-10 border-b border-slate-200 pb-8">
            <h1 class="text-4xl font-bold tracking-tight">
                {{ $donation['amount'] }} donation
            </h1>

            <div class="mt-3 flex items-center gap-3 text-slate-600">
                <span>ID {{ $donation['id'] }}</span>

                <x-copy-button :text="$donation['id']">Copy</x-copy-button>

                <span>·</span>

                <span>≈ {{ $donation['converted_amount'] }}</span>
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

                        <button class="text-sm text-slate-400 hover:text-slate-700">
                            Edit
                        </button>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <x-detail-row label="Donation amount">
                            {{ $donation['amount'] }}
                            <span class="ml-2 text-slate-400">≈ {{ $donation['converted_amount'] }}</span>
                        </x-detail-row>

                        <x-detail-row label="Donation ID">
                            <span class="font-mono">{{ $donation['id'] }}</span>
                            <x-copy-button :text="$donation['id']" class="ml-2">Copy</x-copy-button>
                        </x-detail-row>

                        <x-detail-row label="Status">
                            <span class="rounded-lg bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-700">
                                {{ $donation['status'] }}
                            </span>
                        </x-detail-row>

                        <x-detail-row label="Supporter">
                            <a href="#" class="font-medium text-blue-600 hover:underline">
                                {{ $donation['supporter'] }}
                            </a>
                        </x-detail-row>

                        <x-detail-row label="Campaign">
                            <a href="#" class="font-medium text-blue-600 hover:underline">
                                {{ $donation['campaign'] }}
                            </a>
                        </x-detail-row>

                        <x-detail-row label="Designation">
                            {{ $donation['designation'] }}
                        </x-detail-row>

                        <x-detail-row label="Donation date">
                            {{ $donation['donation_date'] }}
                        </x-detail-row>

                        <x-detail-row label="Success date">
                            {{ $donation['success_date'] }}
                        </x-detail-row>

                        <x-detail-row label="Frequency">
                            {{ $donation['frequency'] }}
                        </x-detail-row>
                    </div>
                </section>

                {{-- Payment Fees Section --}}
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
                        <x-detail-row label="Payment amount">
                            $10.20 SGD
                            <span class="ml-2 text-slate-400">≈ MYR 31.62</span>
                        </x-detail-row>

                        <x-detail-row label="Before fees covered">
                            $9.00 SGD
                            <span class="ml-2 text-slate-400">≈ MYR 27.91</span>
                        </x-detail-row>

                        <x-detail-row label="Processing fee">
                            $1.20 SGD
                        </x-detail-row>

                        <x-detail-row label="Net amount">
                            $9.00 SGD
                        </x-detail-row>
                    </div>
                </section>

                {{-- Other Sections --}}
                <x-simple-section id="recurring-plan" title="Recurring plan" icon="↻">
                    <x-detail-row label="Plan">
                        Monthly giving
                    </x-detail-row>

                    <x-detail-row label="Next payment">
                        Jul 3, 2026
                    </x-detail-row>
                </x-simple-section>

                <x-simple-section id="personal-information" title="Personal information" icon="👤">
                    <x-detail-row label="Full name">
                        Linda Ahmad
                    </x-detail-row>

                    <x-detail-row label="Email">
                        linda@example.com
                    </x-detail-row>

                    <x-detail-row label="Country">
                        Singapore
                    </x-detail-row>
                </x-simple-section>

                <x-simple-section id="tribute" title="Tribute" icon="♡">
                    <p class="text-slate-500">No tribute information.</p>
                </x-simple-section>

                <x-simple-section id="comment" title="Comment" icon="💬">
                    <p class="text-slate-500">No comment from supporter.</p>
                </x-simple-section>

                <x-simple-section id="source" title="Source" icon="↗">
                    <x-detail-row label="Source">
                        Donation form
                    </x-detail-row>

                    <x-detail-row label="Device">
                        Desktop
                    </x-detail-row>
                </x-simple-section>

                <x-simple-section id="insights" title="Insights" icon="⌁">
                    <x-detail-row label="Donor type">
                        Returning donor
                    </x-detail-row>
                </x-simple-section>

                <x-simple-section id="utm-parameters" title="UTM parameters" icon="#">
                    <x-detail-row label="utm_source">
                        facebook
                    </x-detail-row>

                    <x-detail-row label="utm_campaign">
                        mtmt-development-fund
                    </x-detail-row>
                </x-simple-section>

                <x-simple-section id="custom-fields" title="Custom fields" icon="⚙">
                    <p class="text-slate-500">No custom fields.</p>
                </x-simple-section>

                <x-simple-section id="emails" title="Emails" icon="✉">
                    <x-detail-row label="Receipt email">
                        Sent
                    </x-detail-row>

                    <x-detail-row label="Thank you email">
                        Sent
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
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            <span>Download receipt</span>
                        </button>

                        <button class="flex w-full items-center gap-3 px-5 py-4 text-left text-sm font-medium text-red-600 hover:bg-red-50">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
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
                                <span class="w-5 text-center">
                                    {{ $section['icon'] }}
                                </span>

                                <span>
                                    {{ $section['label'] }}
                                </span>
                            </button>
                        @endforeach
                    </nav>

                </div>
            </aside>

        </div>
    </div>
</div>
