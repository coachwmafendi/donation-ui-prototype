<div
    x-data="{
        active: 'overview',
        showDeleteModal: false,
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
            sections.forEach((section) => { observer.observe(section) })
        },
        scrollToSection(id) {
            document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
    }"
    class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900"
>
    <div class="mx-auto max-w-7xl">

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm font-medium text-slate-500">
            <a href="/supporters" class="hover:text-slate-900">Supporters</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900">{{ $supporter->full_name }}</span>
        </div>

        {{-- Header --}}
        <div class="mb-10 border-b border-slate-200 pb-8">
            <div class="flex items-center gap-4">
                <div class="size-14 rounded-full bg-slate-200 flex items-center justify-center text-xl font-bold text-slate-700">
                    {{ substr($supporter->first_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">{{ $supporter->full_name }}</h1>
                    <div class="mt-2 flex items-center gap-3 text-slate-600">
                        <span>{{ $supporter->email }}</span>
                        <span>·</span>
                        <span>ID {{ $supporter->public_id }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">

            {{-- Main content --}}
            <main class="space-y-6 lg:col-span-9">

                {{-- Overview --}}
                <section id="overview" data-section class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="user" />
                            <h2 class="text-xl font-semibold">Overview</h2>
                        </div>
                    </div>
                    <div class="space-y-5 px-6 py-6">
                        <x-detail-row label="Full name">{{ $supporter->full_name }}</x-detail-row>
                        <x-detail-row label="Email">{{ $supporter->email }}</x-detail-row>
                        <x-detail-row label="Phone">{{ $supporter->phone ?? '—' }}</x-detail-row>
                        <x-detail-row label="Country">{{ $supporter->country ?? '—' }}</x-detail-row>
                        @if($supporter->address_line_1)
                            <x-detail-row label="Address">
                                <p>{{ $supporter->address_line_1 }}</p>
                                @if($supporter->address_line_2)
                                    <p>{{ $supporter->address_line_2 }}</p>
                                @endif
                                <p class="text-slate-500">
                                    {{ collect([$supporter->city, $supporter->state, $supporter->postal_code])->filter()->join(', ') }}
                                </p>
                            </x-detail-row>
                        @endif
                        <x-detail-row label="Total donated">{{ $totalDonated }}</x-detail-row>
                        <x-detail-row label="Donations count">{{ $supporter->donations_count }}</x-detail-row>
                        <x-detail-row label="Member since">{{ $supporter->created_at->format('M d, Y') }}</x-detail-row>

                        {{-- Tags --}}
                        <div class="grid grid-cols-1 gap-1 md:grid-cols-3 md:gap-6">
                            <div class="text-slate-500">Tags</div>
                            <div class="font-medium text-slate-900 md:col-span-2">
                                @if(!empty($supporter->tags))
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($supporter->tags as $tag)
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-sm text-slate-700">
                                                {{ $tag }}
                                                <button
                                                    wire:click="removeTag('{{ $tag }}')"
                                                    class="text-slate-400 hover:text-red-500 transition-colors"
                                                    aria-label="Remove tag {{ $tag }}"
                                                >
                                                    <x-icon name="circle-x" class="size-3.5" />
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-400 mb-3">No tags</p>
                                @endif

                                <div class="flex items-center gap-2">
                                    <input
                                        wire:model="newTag"
                                        wire:keydown.enter="addTag"
                                        type="text"
                                        placeholder="Add a tag..."
                                        class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                    >
                                    <button
                                        wire:click="addTag"
                                        class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                                    >
                                        Add
                                    </button>
                                </div>
                                @error('newTag') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Donations --}}
                <section id="donations" data-section class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="dollar-sign" />
                            <h2 class="text-xl font-semibold">Donation history</h2>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        @if($donations->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="border-b border-slate-200">
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Campaign</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Amount</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($donations as $donation)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-4 py-3 text-sm text-slate-700">{{ $donation->campaign }}</td>
                                                <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $donation->amount }}</td>
                                                <td class="px-4 py-3"><x-status-badge :status="$donation->status" /></td>
                                                <td class="px-4 py-3 text-sm text-slate-500">{{ $donation->donation_date->format('M d, Y') }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <a href="/donations/{{ $donation->public_id }}" wire:navigate class="text-sm font-medium text-blue-600 hover:text-blue-800">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $donations->links() }}
                            </div>
                        @else
                            <div class="text-center py-10">
                                <x-icon name="inbox" class="mx-auto size-10 text-slate-300" />
                                <h3 class="mt-3 text-sm font-medium text-slate-900">No donations yet</h3>
                                <p class="mt-1 text-sm text-slate-500">This supporter has not made any donations.</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Recurring --}}
                <section id="recurring" data-section class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="refresh-cw" />
                            <h2 class="text-xl font-semibold">Recurring plans</h2>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        @if($recurring->count() > 0)
                            @foreach($recurring as $plan)
                                <div class="flex items-center justify-between rounded-lg border border-slate-200 p-4 {{ !$loop->first ? 'mt-3' : '' }}">
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">{{ $plan->campaign }}</div>
                                        <div class="mt-1 text-sm text-slate-500">
                                            {{ $plan->amount }} · {{ ucfirst($plan->frequency) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-status-badge :status="$plan->status" />
                                        <a href="/donations/{{ $plan->public_id }}" wire:navigate class="text-sm font-medium text-blue-600 hover:text-blue-800">View</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-10">
                                <x-icon name="inbox" class="mx-auto size-10 text-slate-300" />
                                <h3 class="mt-3 text-sm font-medium text-slate-900">No recurring plans</h3>
                                <p class="mt-1 text-sm text-slate-500">This supporter has no active recurring donations.</p>
                            </div>
                        @endif
                    </div>
                </section>

            </main>

            {{-- Sidebar --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-6 space-y-6">

                    {{-- Actions --}}
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <a href="mailto:{{ $supporter->email }}" class="flex w-full items-center gap-3 border-b border-slate-200 px-5 py-4 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">
                            <x-icon name="mail" class="size-4 text-slate-500" />
                            <span>Send email</span>
                        </a>

                        <button @click="showDeleteModal = true" class="flex w-full items-center gap-3 px-5 py-4 text-left text-sm font-medium text-red-600 hover:bg-red-50">
                            <x-icon name="trash-2" class="size-4" />
                            <span>Delete supporter</span>
                        </button>
                    </div>

                    {{-- Navigation --}}
                    <nav class="rounded-xl border border-slate-200 bg-white p-2">
                        @foreach ($sections as $section)
                            <button
                                type="button"
                                @click="scrollToSection('{{ $section['id'] }}')"
                                class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-sm transition"
                                :class="active === '{{ $section['id'] }}' ? 'bg-slate-100 font-semibold text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            >
                                <span class="w-5 text-center flex items-center justify-center">
                                    <x-icon name="{{ $section['icon'] }}" />
                                </span>
                                <span>{{ $section['label'] }}</span>
                            </button>
                        @endforeach
                    </nav>

                </div>
            </aside>

        </div>
    </div>

    {{-- Delete modal --}}
    <div
        x-show="showDeleteModal"
        x-transition
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
        @click.self="showDeleteModal = false"
    >
        <div class="w-full max-w-sm rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Delete supporter</h3>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-slate-600">
                    Are you sure you want to delete <strong>{{ $supporter->full_name }}</strong>? This will also delete all associated donation records.
                </p>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                <button @click="showDeleteModal = false" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>
                <button wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>

</div>
