<div class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900">
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Recurring donations</h1>
                <p class="mt-1 text-sm text-slate-500">Manage recurring plans</p>
            </div>
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
                            placeholder="Search by donor name or email..."
                            class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        >
                    </div>
                </div>

                <select
                    wire:model.live="statusFilter"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                    <option value="">All statuses</option>
                    <option value="succeeded">Active</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="paused">Paused</option>
                </select>

                <select
                    wire:model.live="frequencyFilter"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                    <option value="">All frequencies</option>
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Donor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Campaign</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Frequency</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Next payment</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($recurring as $plan)
                            <tr
                                wire:key="recurring-{{ $plan->id }}"
                                @click="!$event.target.closest('button') && Livewire.navigate('/donations/{{ $plan->public_id }}')"
                                @keydown.enter="!$event.target.closest('button') && Livewire.navigate('/donations/{{ $plan->public_id }}')"
                                @keydown.space.prevent="!$event.target.closest('button') && Livewire.navigate('/donations/{{ $plan->public_id }}')"
                                role="link"
                                tabindex="0"
                                class="cursor-pointer hover:bg-slate-50 transition-colors"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-medium text-slate-600 mr-3">
                                            {{ substr($plan->profile->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ $plan->profile->full_name }}</div>
                                            <div class="text-sm text-slate-500">{{ $plan->profile->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-slate-700">{{ $plan->campaign }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">{{ $plan->amount }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-slate-700 capitalize">{{ $plan->frequency }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$plan->status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($plan->status === 'succeeded' || $plan->status === 'active')
                                        <span class="text-sm text-slate-500">
                                            @if($plan->frequency === 'monthly')
                                                {{ $plan->donation_date->addMonth()->format('M d, Y') }}
                                            @elseif($plan->frequency === 'weekly')
                                                {{ $plan->donation_date->addWeek()->format('M d, Y') }}
                                            @elseif($plan->frequency === 'yearly')
                                                {{ $plan->donation_date->addYear()->format('M d, Y') }}
                                            @else
                                                —
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($plan->status === 'succeeded')
                                        <button
                                            wire:click="pause('{{ $plan->public_id }}')"
                                            class="ml-4 text-sm font-medium text-amber-600 hover:text-amber-800"
                                        >
                                            Pause
                                        </button>
                                        <button
                                            wire:click="cancel('{{ $plan->public_id }}')"
                                            wire:confirm="Cancel this recurring plan?"
                                            class="ml-4 text-sm font-medium text-red-600 hover:text-red-800"
                                        >
                                            Cancel
                                        </button>
                                    @elseif($plan->status === 'paused')
                                        <button
                                            wire:click="resume('{{ $plan->public_id }}')"
                                            class="ml-4 text-sm font-medium text-emerald-600 hover:text-emerald-800"
                                        >
                                            Resume
                                        </button>
                                        <button
                                            wire:click="cancel('{{ $plan->public_id }}')"
                                            wire:confirm="Cancel this recurring plan?"
                                            class="ml-4 text-sm font-medium text-red-600 hover:text-red-800"
                                        >
                                            Cancel
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <x-icon name="inbox" class="mx-auto size-12 text-slate-400" />
                                    <h3 class="mt-4 text-sm font-medium text-slate-900">No recurring plans found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Try adjusting your search or filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($recurring->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $recurring->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
