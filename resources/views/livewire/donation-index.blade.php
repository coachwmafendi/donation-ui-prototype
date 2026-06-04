<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Donations</h1>
            <p class="mt-1 text-sm text-slate-500">Manage and track all donations</p>
        </div>

        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
            Add donation
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
                        placeholder="Search by ID, donor, email, or campaign..."
                        class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>
            </div>

            <select 
                wire:model.live="statusFilter"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
            >
                <option value="">All statuses</option>
                <option value="succeeded">Succeeded</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Donor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($donations as $donation)
                        <tr
                            wire:key="donation-{{ $donation->id }}"
                            @click="Livewire.navigate('/donations/{{ $donation->public_id }}')"
                            @keydown.enter="Livewire.navigate('/donations/{{ $donation->public_id }}')"
                            @keydown.space.prevent="Livewire.navigate('/donations/{{ $donation->public_id }}')"
                            role="link"
                            tabindex="0"
                            class="cursor-pointer hover:bg-slate-50 transition-colors"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm text-slate-600">{{ $donation->public_id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-medium text-slate-600 mr-3">
                                        {{ substr($donation->profile->first_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">{{ $donation->profile->full_name }}</div>
                                        <div class="text-sm text-slate-500">{{ $donation->profile->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $donation->amount }}</div>
                                @if($donation->converted_amount)
                                    <div class="text-sm text-slate-500">≈ {{ $donation->converted_amount }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-700">{{ $donation->campaign }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$donation->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-500">{{ $donation->donation_date->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm text-slate-400">&rarr;</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <x-icon name="dollar-sign" class="mx-auto size-12 text-slate-400" />
                                <h3 class="mt-4 text-sm font-medium text-slate-900">No donations found</h3>
                                <p class="mt-1 text-sm text-slate-500">Try adjusting your search or filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($donations->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $donations->links() }}
            </div>
        @endif
    </div>
</div>
