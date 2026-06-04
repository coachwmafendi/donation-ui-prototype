<div class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900">
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Supporters</h1>
                <p class="mt-1 text-sm text-slate-500">Manage donors and supporter profiles</p>
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
                            placeholder="Search by name, email, or phone..."
                            class="w-full rounded-lg border border-slate-300 bg-white pl-10 pr-4 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        >
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Supporter</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tags</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Donations</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total donated</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Last donation</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($supporters as $supporter)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-medium text-slate-600 mr-3">
                                            {{ substr($supporter->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ $supporter->full_name }}</div>
                                            <div class="text-sm text-slate-500">ID {{ $supporter->public_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-700">{{ $supporter->email }}</div>
                                    @if($supporter->phone)
                                        <div class="text-sm text-slate-500">{{ $supporter->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-slate-700">
                                        {{ $supporter->country ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!empty($supporter->tags))
                                        <div class="flex flex-wrap gap-1.5 max-w-48">
                                            @foreach(array_slice($supporter->tags, 0, 3) as $tag)
                                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $tag }}</span>
                                            @endforeach
                                            @if(count($supporter->tags) > 3)
                                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-400">+{{ count($supporter->tags) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-slate-700">{{ $supporter->donations_count }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-slate-900">
                                        ${{ number_format(($supporter->donations_sum_amount_cents ?? 0) / 100, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($supporter->donations->first())
                                        <span class="text-sm text-slate-500">
                                            {{ $supporter->donations->first()->donation_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a
                                        href="/supporters/{{ $supporter->public_id }}"
                                        wire:navigate
                                        class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <x-icon name="user" class="mx-auto size-12 text-slate-400" />
                                    <h3 class="mt-4 text-sm font-medium text-slate-900">No supporters found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Try adjusting your search.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($supporters->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $supporters->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
