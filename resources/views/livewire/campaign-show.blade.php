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
                <a href="/campaigns/{{ $campaign->public_id }}/edit" wire:navigate class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Edit campaign
                </a>
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
        <div x-show="activeTab === 'settings'" class="max-w-2xl" x-cloak>
            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Campaign settings</h2>
                </div>
                <div class="space-y-4 px-6 py-5">
                    <x-detail-row label="Allow recurring donations">
                        {{ ($campaign->settings['allow_recurring'] ?? true) ? 'Yes' : 'No' }}
                    </x-detail-row>
                    <x-detail-row label="Allow tribute gifts">
                        {{ ($campaign->settings['allow_tribute'] ?? false) ? 'Yes' : 'No' }}
                    </x-detail-row>
                    <x-detail-row label="Suggested amounts">
                        @if(isset($campaign->settings['suggested_amounts']))
                            {{ collect($campaign->settings['suggested_amounts'])->map(fn($cents) => '$' . number_format($cents / 100, 2))->join(', ') }}
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </x-detail-row>
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
                            <button onclick="navigator.clipboard.writeText(document.getElementById('embed-code').innerText).then(() => alert('Copied!'))" class="text-xs font-medium text-blue-600 hover:text-blue-800">Copy</button>
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
        <div x-show="activeTab === 'page'" class="max-w-2xl" x-cloak>
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
