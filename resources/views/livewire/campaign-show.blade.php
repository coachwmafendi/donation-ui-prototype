<div
    x-data="{
        active: 'overview',
        showArchiveModal: false,

        init() {
            const sections = document.querySelectorAll('[data-section]')

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.active = entry.target.id
                    }
                })
            }, {
                rootMargin: '-25% 0px -65% 0px',
                threshold: 0
            })

            sections.forEach((section) => {
                observer.observe(section)
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
            <a href="/campaigns" class="hover:text-slate-900">Campaigns</a> >
        </div>

        {{-- Header --}}
        <div class="mb-10 border-b border-slate-200 pb-8">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight">
                        {{ $campaign->name }}
                    </h1>
                    <div class="mt-3 flex items-center gap-3 text-slate-600">
                        <span>ID {{ $campaign->public_id }}</span>
                        <x-copy-button :text="$campaign->public_id" class="ml-2">Copy</x-copy-button>
                        <span>·</span>
                        <x-status-badge :status="$campaign->status" />
                    </div>
                </div>
                <a href="#" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Edit campaign
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">

            {{-- MAIN CONTENT --}}
            <main class="space-y-6 lg:col-span-9">

                {{-- Overview Section --}}
                <section
                    id="overview"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="hash" />
                            <h2 class="text-xl font-semibold">Overview</h2>
                        </div>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <x-detail-row label="Campaign name">
                            {{ $campaign->name }}
                        </x-detail-row>

                        <x-detail-row label="Public ID">
                            <span class="font-mono text-sm">{{ $campaign->public_id }}</span>
                            <x-copy-button :text="$campaign->public_id" class="ml-2">Copy</x-copy-button>
                        </x-detail-row>

                        <x-detail-row label="Slug">
                            {{ $campaign->slug }}
                        </x-detail-row>

                        <x-detail-row label="Status">
                            <x-status-badge :status="$campaign->status" />
                        </x-detail-row>

                        <x-detail-row label="Description">
                            {{ $campaign->description ?? 'No description provided.' }}
                        </x-detail-row>

                        <x-detail-row label="Date range">
                            @if($campaign->start_date && $campaign->end_date)
                                {{ $campaign->start_date->format('M d, Y') }} — {{ $campaign->end_date->format('M d, Y') }}
                            @else
                                <span class="text-slate-400">No date range set</span>
                            @endif
                        </x-detail-row>

                        <x-detail-row label="Currency">
                            {{ $campaign->currency }}
                        </x-detail-row>
                    </div>
                </section>

                {{-- Progress Section --}}
                <section
                    id="progress"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="zap" />
                            <h2 class="text-xl font-semibold">Progress</h2>
                        </div>
                    </div>

                    <div class="space-y-6 px-6 py-6">
                        {{-- Progress bar --}}
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-900">{{ $campaign->progress_percentage }}% of goal</span>
                                <span class="text-slate-500">{{ $campaign->raised_amount }} / {{ $campaign->goal_amount }}</span>
                            </div>
                            <div class="mt-3 h-3 w-full overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                                    style="width: {{ $campaign->progress_percentage }}%"
                                ></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
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
                </section>

                {{-- Settings Section --}}
                <x-simple-section id="settings" title="Settings" icon="<x-icon name='settings' />">
                    <x-detail-row label="Allow recurring donations">
                        {{ $campaign->settings['allow_recurring'] ?? true ? 'Yes' : 'No' }}
                    </x-detail-row>

                    <x-detail-row label="Allow tribute gifts">
                        {{ $campaign->settings['allow_tribute'] ?? false ? 'Yes' : 'No' }}
                    </x-detail-row>

                    <x-detail-row label="Suggested amounts">
                        @if(isset($campaign->settings['suggested_amounts']))
                            {{ collect($campaign->settings['suggested_amounts'])->map(fn($cents) => '$' . number_format($cents / 100, 2))->join(', ') }}
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </x-detail-row>
                </x-simple-section>

                {{-- Donations Section --}}
                <section
                    id="donations"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="banknote" />
                            <h2 class="text-xl font-semibold">Recent donations</h2>
                        </div>
                        <span class="text-sm text-slate-500">{{ $campaign->donations_count }} total</span>
                    </div>

                    <div class="divide-y divide-slate-200">
                        @forelse ($recentDonations as $donation)
                            <a href="/donations/{{ $donation->public_id }}" wire:navigate class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="size-9 rounded-full bg-slate-200 flex items-center justify-center text-sm font-medium text-slate-600">
                                        {{ substr($donation->profile?->first_name ?? '?', 0, 1) }}
                                    </div>
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
                            <div class="px-6 py-12 text-center">
                                <p class="text-sm text-slate-500">No donations yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

            </main>

            {{-- RIGHT SIDEBAR --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-6 space-y-6">

                    {{-- Actions --}}
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <a href="#" class="flex w-full items-center gap-3 border-b border-slate-200 px-5 py-4 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">
                            <x-icon name="settings" class="size-4 text-slate-500" />
                            <span>Edit campaign</span>
                        </a>

                        <button
                            @click="showArchiveModal = true"
                            class="flex w-full items-center gap-3 px-5 py-4 text-left text-sm font-medium text-slate-600 hover:bg-slate-50"
                        >
                            <x-icon name="inbox" class="size-4" />
                            <span>Archive campaign</span>
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
                <p class="text-sm text-slate-700">
                    Are you sure you want to archive <strong>{{ $campaign->name }}</strong>? This will hide the campaign from public view but keep all donation records.
                </p>
                <p class="text-sm text-slate-500">
                    You can reactivate the campaign at any time.
                </p>
            </div>

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                <button
                    @click="showArchiveModal = false"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                >
                    Cancel
                </button>
                <button
                    wire:click="archive"
                    wire:loading.attr="disabled"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50"
                >
                    <span wire:loading.remove>Archive campaign</span>
                    <span wire:loading>Archiving...</span>
                </button>
            </div>
        </div>
    </div>
</div>
