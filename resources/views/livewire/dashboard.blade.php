<div class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900">
    <div class="mx-auto max-w-7xl space-y-8">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500">Overview of your fundraising activity</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <div class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-slate-900">{{ $stat['value'] }}</span>
                        @if($stat['trend'])
                            <span class="text-xs font-medium {{ $stat['trendUp'] ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $stat['trend'] }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Main Row: Trend Chart + Quick Actions --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            {{-- Trend Chart (CSS Bar Chart) --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Donation trend</h2>
                            <p class="text-sm text-slate-500">Last 14 days</p>
                        </div>
                        <div class="text-sm font-medium text-slate-500">
                            Total: ${{ number_format(collect($trend)->sum('amount') / 100, 2) }}
                        </div>
                    </div>

                    {{-- CSS Bar Chart --}}
                    @php
                        $maxAmount = max(collect($trend)->max('amount'), 1);
                    @endphp
                    <div class="flex items-end justify-between gap-2 h-40">
                        @foreach($trend as $day)
                            @php
                                $height = max(($day['amount'] / $maxAmount) * 100, 4);
                            @endphp
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full flex-1 flex items-end">
                                    <div
                                        class="w-full rounded-t-md bg-slate-900 transition-all duration-500 hover:bg-blue-600 relative group"
                                        style="height: {{ $height }}%"
                                        title="${{ number_format($day['amount'] / 100, 2) }}"
                                    >
                                        {{-- Tooltip on hover --}}
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap pointer-events-none">
                                            ${{ number_format($day['amount'] / 100, 0) }}
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-500">{{ $day['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Top Campaigns --}}
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Top campaigns</h2>
                    <div class="space-y-4">
                        @forelse ($topCampaigns as $campaign)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-16 rounded-lg bg-slate-100 overflow-hidden">
                                        @if($campaign->cover_image)
                                            <img src="{{ $campaign->cover_image }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-slate-400 text-xs font-medium">
                                                {{ substr($campaign->name, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="/campaigns/{{ $campaign->public_id }}" wire:navigate class="text-sm font-medium text-slate-900 hover:text-blue-600">
                                            {{ $campaign->name }}
                                        </a>
                                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                                            <span>{{ $campaign->donor_count }} donors</span>
                                            <span>·</span>
                                            <x-status-badge :status="$campaign->status" />
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-slate-900">{{ $campaign->raised_amount }}</div>
                                    <div class="text-xs text-slate-500">of {{ $campaign->goal_amount }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-slate-500">No campaigns yet.</p>
                                <a href="#" class="mt-3 inline-block text-sm font-medium text-blue-600 hover:text-blue-800">Create your first campaign</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar: Quick Actions + Recent Activity --}}
            <div class="space-y-6">

                {{-- Quick Actions --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick actions</h2>
                    <div class="space-y-2">
                        <a href="#" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <x-icon name="settings" class="size-4 text-slate-500" />
                            Create campaign
                        </a>

                        <a href="/donations" wire:navigate class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <x-icon name="dollar-sign" class="size-4 text-slate-500" />
                            View donations
                        </a>

                        <a href="/users" wire:navigate class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <x-icon name="user" class="size-4 text-slate-500" />
                            View supporters
                        </a>

                        <button class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <x-icon name="download" class="size-4 text-slate-500" />
                            Export report
                        </button>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-slate-900">Recent activity</h2>
                        <a href="/donations" wire:navigate class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($recentDonations as $donation)
                            <a href="/donations/{{ $donation->public_id }}" wire:navigate class="block group">
                                <div class="flex items-start gap-3">
                                    <span class="mt-1.5 block size-2 rounded-full flex-shrink-0 {{ $donation->status === 'succeeded' ? 'bg-emerald-500' : ($donation->status === 'failed' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900 truncate">
                                            {{ $donation->profile?->full_name ?? 'Anonymous' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $donation->amount }}
                                            <span class="text-slate-400">·</span>
                                            {{ $donation->donation_date->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-sm text-slate-500">No recent donations.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
