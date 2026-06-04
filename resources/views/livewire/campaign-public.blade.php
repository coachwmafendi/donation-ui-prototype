<div class="min-h-screen bg-white">

    {{-- Hero Section --}}
    <div class="relative bg-slate-900 text-white">
        @if($campaign->cover_image)
            <div class="absolute inset-0 opacity-40">
                <img src="{{ $campaign->cover_image }}" alt="{{ $campaign->name }}" class="h-full w-full object-cover">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        @endif
        
        <div class="relative mx-auto max-w-4xl px-6 py-20 md:py-28">
            <div class="mx-auto max-w-2xl text-center">
                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80 backdrop-blur-sm">
                    {{ ucfirst($campaign->status) }}
                </span>
                <h1 class="mt-6 text-4xl font-bold tracking-tight md:text-5xl">
                    {{ $campaign->name }}
                </h1>
                <p class="mt-4 text-lg text-white/70">
                    {{ $campaign->description ?? 'Your support makes a lasting impact.' }}
                </p>
                <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <a href="/donate/{{ $campaign->public_id }}" class="rounded-lg bg-white px-8 py-3.5 text-base font-semibold text-slate-900 shadow-lg hover:bg-slate-100 transition">
                        Donate Now
                    </a>
                    <a href="#about" class="rounded-lg border border-white/20 bg-white/5 px-8 py-3.5 text-base font-medium text-white backdrop-blur-sm hover:bg-white/10 transition">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-6 py-12">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-10">

                {{-- Progress Card --}}
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between text-sm mb-3">
                        <span class="font-medium text-slate-500">
                            {{ $campaign->progress_percentage }}% of goal reached
                        </span>
                        <span class="font-semibold text-slate-900">
                            {{ $campaign->raised_amount }} / {{ $campaign->goal_amount }}
                        </span>
                    </div>
                    <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100">
                        <div
                            class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                            style="width: {{ $campaign->progress_percentage }}%"
                        ></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-6">
                            <div>
                                <div class="text-2xl font-bold text-slate-900">{{ $campaign->donor_count }}</div>
                                <div class="text-xs text-slate-500">Donors</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-slate-900">{{ $campaign->goal_amount }}</div>
                                <div class="text-xs text-slate-500">Goal</div>
                            </div>
                            @if($campaign->end_date)
                            <div>
                                <div class="text-2xl font-bold text-slate-900">{{ max(0, round(now()->diffInDays($campaign->end_date, false))) }}</div>
                                <div class="text-xs text-slate-500">Days Left</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- About Campaign --}}
                <section id="about">
                    <h2 class="text-2xl font-bold text-slate-900">About this campaign</h2>
                    <div class="mt-4 space-y-4 text-slate-600 leading-relaxed">
                        @if($campaign->description)
                            <p>{{ $campaign->description }}</p>
                        @else
                            <p>This campaign is dedicated to making a meaningful impact through the generous support of donors like you. Every contribution, no matter the size, helps us move closer to our goal.</p>
                            <p>Your donation will go directly toward funding the initiatives outlined in this campaign, ensuring transparency and maximum impact.</p>
                        @endif
                    </div>
                </section>

                {{-- Donate Form --}}
                <section id="donate">
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <iframe
                            src="{{ config('app.url') }}/embed/{{ $campaign->slug }}"
                            width="100%"
                            height="650"
                            frameborder="0"
                            style="border: none; display: block;"
                            title="Donate to {{ $campaign->name }}"
                        ></iframe>
                    </div>
                </section>

                {{-- Recent Donors --}}
                @if($recentDonations->count() > 0)
                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Recent supporters</h2>
                    <div class="space-y-3">
                        @foreach($recentDonations as $donation)
                            <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50/50 px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-10 rounded-full bg-emerald-100 flex items-center justify-center text-sm font-bold text-emerald-700">
                                        {{ substr($donation->profile?->first_name ?? 'A', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $donation->profile?->full_name ?? 'Anonymous' }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $donation->donation_date->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-slate-900">{{ $donation->amount }}</div>
                                    @if($donation->frequency !== 'one-time')
                                        <div class="text-xs text-slate-500">monthly</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

            </div>

            {{-- Sidebar Sticky --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">

                    {{-- Campaign Meta --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Campaign details</h3>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Started</span>
                                <span class="font-medium text-slate-900">{{ $campaign->start_date?->format('M d, Y') ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Ends</span>
                                <span class="font-medium text-slate-900">{{ $campaign->end_date?->format('M d, Y') ?? 'Ongoing' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Campaign ID</span>
                                <span class="font-mono text-xs text-slate-900">{{ $campaign->public_id }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

        </div>
    </div>

    {{-- Bottom CTA --}}
    <div class="border-t border-slate-100 bg-slate-50/50 py-16">
        <div class="mx-auto max-w-2xl px-6 text-center">
            <h2 class="text-3xl font-bold text-slate-900">Ready to make an impact?</h2>
            <p class="mt-4 text-lg text-slate-600">Join {{ $campaign->donor_count }} donors who have already supported this campaign.</p>
            <a href="/donate/{{ $campaign->public_id }}" class="mt-8 inline-block rounded-lg bg-slate-900 px-8 py-3.5 text-base font-semibold text-white shadow-lg hover:bg-slate-800 transition">
                Donate Now
            </a>
        </div>
    </div>

</div>
