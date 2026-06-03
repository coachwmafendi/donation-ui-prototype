<div class="min-h-screen bg-white">

    {{-- Hero --}}
    <div class="bg-slate-900 text-white">
        <div class="mx-auto max-w-5xl px-6 py-20 md:py-28 text-center">
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight">
                Make a difference today
            </h1>
            <p class="mt-6 text-lg md:text-xl text-white/70 max-w-2xl mx-auto">
                Support causes you care about. Every donation, big or small, creates lasting change.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#campaigns" class="rounded-lg bg-white px-8 py-3.5 text-base font-semibold text-slate-900 hover:bg-slate-100 transition">
                    Browse campaigns
                </a>
                <a href="/donate" class="rounded-lg border border-white/20 bg-white/5 px-8 py-3.5 text-base font-medium text-white hover:bg-white/10 transition">
                    Make a donation
                </a>
            </div>
        </div>
    </div>

    {{-- Active Campaigns --}}
    <div id="campaigns" class="mx-auto max-w-5xl px-6 py-16 md:py-24">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900">Active campaigns</h2>
            <p class="mt-3 text-slate-500">Choose a cause to support</p>
        </div>

        @if($campaigns->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($campaigns as $campaign)
                    <a href="/c/{{ $campaign->slug }}" wire:navigate class="group block rounded-xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg transition">
                        {{-- Cover Image --}}
                        <div class="h-40 bg-slate-100 overflow-hidden">
                            @if($campaign->cover_image)
                                <img src="{{ $campaign->cover_image }}" alt="{{ $campaign->name }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                    <span class="text-4xl font-bold text-slate-300">{{ substr($campaign->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-600 transition">
                                {{ $campaign->name }}
                            </h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                {{ $campaign->description ?? 'Support this campaign and make a difference.' }}
                            </p>

                            {{-- Progress --}}
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <span class="font-semibold text-slate-700">{{ $campaign->progress_percentage }}%</span>
                                    <span class="text-slate-500">{{ $campaign->raised_amount }} raised</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div
                                        class="h-full rounded-full bg-emerald-500 transition-all"
                                        style="width: {{ $campaign->progress_percentage }}%"
                                    ></div>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                                <span>{{ $campaign->donor_count }} donors</span>
                                @if($campaign->end_date)
                                    <span>{{ max(0, now()->diffInDays($campaign->end_date, false)) }} days left</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-slate-100">
                    <svg class="size-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-slate-900">No active campaigns</h3>
                <p class="mt-2 text-sm text-slate-500">Check back soon for new campaigns.</p>
            </div>
        @endif
    </div>

    {{-- How it works --}}
    <div class="border-t border-slate-100 bg-slate-50/50 py-16 md:py-24">
        <div class="mx-auto max-w-5xl px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">How it works</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-slate-900 text-white text-xl font-bold">
                        1
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Choose a campaign</h3>
                    <p class="mt-2 text-sm text-slate-500">Browse our active campaigns and find a cause that resonates with you.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-slate-900 text-white text-xl font-bold">
                        2
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Make a donation</h3>
                    <p class="mt-2 text-sm text-slate-500">Select an amount and complete your secure donation in seconds.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-slate-900 text-white text-xl font-bold">
                        3
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">See your impact</h3>
                    <p class="mt-2 text-sm text-slate-500">Track the progress and see how your contribution makes a difference.</p>
                </div>
            </div>
        </div>
    </div>

</div>
