<div class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900">
    <div class="mx-auto max-w-7xl space-y-8">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500">Overview of your fundraising activity</p>
        </div>

        {{-- Stats Cards with Sparklines --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <div class="flex items-start justify-between">
                        <div>
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
                        <x-charts.sparkline
                            :data="collect($sparklineData)->slice(-14)->values()->toArray()"
                            :width="100"
                            :height="32"
                            fill
                            class="opacity-60"
                        />
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Main Layout: Content (8 cols) + Sidebar (4 cols) --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">

            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-8">

                {{-- Trend Chart --}}
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

                    <div
                        class="relative"
                        x-data="{ activeIndex: null }"
                        style="height: 280px;"
                    >
                        <svg class="w-full h-full" viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="areaGradient" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.15"/>
                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                                </linearGradient>
                            </defs>

                            @foreach([0, 0.25, 0.5, 0.75, 1] as $grid)
                                @php $gridY = $paddingY + ($chartHeight * $grid); @endphp
                                <line x1="{{ $paddingX }}" y1="{{ $gridY }}" x2="{{ $chartWidth - $paddingX }}" y2="{{ $gridY }}" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 4"/>
                            @endforeach

                            <path d="{{ $areaPath }}" fill="url(#areaGradient)"/>
                            <path d="{{ $pathPoints }}" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

                            @foreach($chartPoints as $index => $point)
                                <circle
                                    cx="{{ $point['x'] }}"
                                    cy="{{ $point['y'] }}"
                                    r="4"
                                    fill="white"
                                    stroke="#10b981"
                                    stroke-width="2"
                                    class="transition-all duration-200 cursor-pointer"
                                    :class="activeIndex === {{ $index }} ? 'r-[6px] stroke-slate-900' : ''"
                                    @mouseenter="activeIndex = {{ $index }}"
                                    @mouseleave="activeIndex = null"
                                />
                            @endforeach
                        </svg>

                        @foreach($chartPoints as $index => $point)
                            <div
                                class="absolute pointer-events-none transition-opacity duration-200"
                                style="left: {{ ($point['x'] / $chartWidth) * 100 }}%; top: {{ ($point['y'] / $chartHeight) * 100 }}%; transform: translate(-50%, -130%);"
                                :class="activeIndex === {{ $index }} ? 'opacity-100' : 'opacity-0'"
                            >
                                <div class="bg-slate-900 text-white text-xs rounded-lg px-3 py-2 shadow-lg whitespace-nowrap">
                                    <div class="font-semibold">${{ number_format($point['amount'] / 100, 2) }}</div>
                                    <div class="text-slate-400 text-[10px]">{{ $point['date'] }}</div>
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-between px-10 mt-2">
                            @foreach($chartPoints as $index => $point)
                                <span class="text-[10px] text-slate-400 text-center @if($index % 2 !== 0) hidden lg:block @endif" style="width: 20px;">{{ $point['label'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Top Campaigns --}}
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Top campaigns</h2>
                    <x-charts.horizontal-bar :data="$campaignSplits" bar-height="32" />
                </div>

                {{-- Three Charts Row --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Doughnut --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6">
                        <h2 class="text-base font-semibold text-slate-900 mb-4">Donations by Campaign</h2>
                        <div class="flex items-center gap-4">
                            <x-charts.doughnut :data="$campaignSplits" size="100" />
                            <div class="space-y-1.5 flex-1">
                                @foreach($campaignSplits as $campaign)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="size-2 rounded-full flex-shrink-0" style="background-color: {{ $campaign['color'] }}"></span>
                                        <span class="text-slate-600 truncate">{{ $campaign['label'] }}</span>
                                        <span class="text-slate-900 font-medium ml-auto">${{ number_format($campaign['value'] / 100, 0) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Range Bar --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6">
                        <h2 class="text-base font-semibold text-slate-900 mb-4">Donation Sizes</h2>
                        <x-charts.range-bar :data="$donationRanges" bar-height="20" />
                    </div>

                    {{-- Funnel --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6">
                        <h2 class="text-base font-semibold text-slate-900 mb-4">Donation Pipeline</h2>
                        <x-charts.funnel :steps="$donationFunnel" height="28" />
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-4 space-y-8">

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
