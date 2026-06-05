<div class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900">
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Campaigns</h1>
                <p class="mt-1 text-sm text-slate-500">Manage fundraising campaigns</p>
            </div>

            <button
                type="button"
                wire:click="openCreateModal"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition"
            >
                Add campaign
            </button>
        </div>

        {{-- Filters --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="sr-only">Search</label>
                    <div class="relative">
                        <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-5 text-slate-400" />
                        <input
                            wire:model.live.debounce.300ms="search"
                            type="text"
                            placeholder="Search by name or ID..."
                            class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        >
                    </div>
                </div>

                <select
                    wire:model.live="statusFilter"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                    <option value="">All statuses</option>
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Campaign</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date range</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($campaigns as $campaign)
                            <tr
                                wire:key="campaign-{{ $campaign->id }}"
                                @click="Livewire.navigate('{{ route('campaigns.edit', $campaign) }}')"
                                @keydown.enter="Livewire.navigate('{{ route('campaigns.edit', $campaign) }}')"
                                @keydown.space.prevent="Livewire.navigate('{{ route('campaigns.edit', $campaign) }}')"
                                role="link"
                                tabindex="0"
                                class="cursor-pointer hover:bg-slate-50 transition-colors"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm text-slate-600">{{ $campaign->public_id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">{{ $campaign->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $campaign->donor_count }} donors</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-40">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="font-medium text-slate-700">{{ $campaign->progress_percentage }}%</span>
                                            <span class="text-slate-500">{{ $campaign->raised_amount }}</span>
                                        </div>
                                        <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                            <div
                                                class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                                                style="width: {{ $campaign->progress_percentage }}%"
                                            ></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$campaign->status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($campaign->start_date && $campaign->end_date)
                                        <span class="text-sm text-slate-500">{{ $campaign->start_date->format('M d, Y') }} — {{ $campaign->end_date->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-sm text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm text-slate-400">&rarr;</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <x-icon name="inbox" class="mx-auto size-12 text-slate-400" />
                                    <h3 class="mt-4 text-sm font-medium text-slate-900">No campaigns found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Try adjusting your search or filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($campaigns->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- Create Campaign Modal --}}
    @if($showCreateModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-data="{ open: true }"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/50"
                @click="open = false; $wire.showCreateModal = false"
            ></div>

            {{-- Modal --}}
            <div
                class="relative w-full max-w-lg rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden"
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
                    <h2 class="text-xl font-semibold text-slate-900">Create a new campaign</h2>
                    <button
                        type="button"
                        @click="open = false; $wire.showCreateModal = false"
                        class="rounded-lg p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                    >
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-6 space-y-6">
                    <p class="text-sm text-slate-600">
                        Clone an existing campaign or create a new campaign with your default settings.
                    </p>

                    {{-- Mode selection --}}
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input
                                type="radio"
                                wire:model.live="createMode"
                                value="new"
                                class="size-4 text-slate-900 border-slate-300 focus:ring-slate-900"
                            >
                            <span class="text-sm text-slate-700 group-hover:text-slate-900">New campaign with default settings</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input
                                type="radio"
                                wire:model.live="createMode"
                                value="clone"
                                class="size-4 text-slate-900 border-slate-300 focus:ring-slate-900"
                            >
                            <span class="text-sm text-slate-700 group-hover:text-slate-900">Clone an existing campaign</span>
                        </label>
                    </div>

                    <div class="border-t border-slate-200"></div>

                    {{-- Clone source --}}
                    @if($createMode === 'clone')
                        <div>
                            <label for="cloneSourceId" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Clone settings from campaign
                            </label>
                            <select
                                wire:model.live="cloneSourceId"
                                id="cloneSourceId"
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                            >
                                <option value="">Select a campaign...</option>
                                @foreach($existingCampaigns as $existing)
                                    <option value="{{ $existing->id }}">{{ $existing->name }}</option>
                                @endforeach
                            </select>
                            @error('cloneSourceId')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    {{-- Name --}}
                    <div>
                        <label for="newCampaignName" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Name
                        </label>
                        <input
                            wire:model="newCampaignName"
                            id="newCampaignName"
                            type="text"
                            placeholder="My awesome campaign"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        >
                        @error('newCampaignName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <button
                        type="button"
                        @click="open = false; $wire.showCreateModal = false"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="createCampaign"
                        wire:loading.attr="disabled"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50"
                    >
                        <span wire:loading.remove>Create campaign</span>
                        <span wire:loading>Creating...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
