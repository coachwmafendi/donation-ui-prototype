<div class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900">
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Campaigns</h1>
                <p class="mt-1 text-sm text-slate-500">Manage fundraising campaigns</p>
            </div>

            <a href="/campaigns/create" wire:navigate class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition">
                Add campaign
            </a>
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
                                @click="Livewire.navigate('/campaigns/{{ $campaign->public_id }}')"
                                @keydown.enter="Livewire.navigate('/campaigns/{{ $campaign->public_id }}')"
                                @keydown.space.prevent="Livewire.navigate('/campaigns/{{ $campaign->public_id }}')"
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
</div>
