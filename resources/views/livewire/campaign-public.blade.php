<?php

$colorMap = [
    'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg-light' => 'bg-emerald-100'],
    'blue' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'bg-light' => 'bg-blue-100'],
    'amber' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-700', 'bg-light' => 'bg-amber-100'],
    'rose' => ['bg' => 'bg-rose-500', 'text' => 'text-rose-700', 'bg-light' => 'bg-rose-100'],
    'violet' => ['bg' => 'bg-violet-500', 'text' => 'text-violet-700', 'bg-light' => 'bg-violet-100'],
    'cyan' => ['bg' => 'bg-cyan-500', 'text' => 'text-cyan-700', 'bg-light' => 'bg-cyan-100'],
];
$pageConfig = $campaign->settings['page_config'] ?? [];
$color = $colorMap[$pageConfig['primary_color'] ?? 'emerald'] ?? $colorMap['emerald'];

$heroHeadline = $pageConfig['hero_headline'] ?? $campaign->name;
$heroSubheadline = $pageConfig['hero_subheadline'] ?? ($campaign->description ?? 'Your support makes a lasting impact.');
$primaryCta = $pageConfig['primary_cta_text'] ?? 'Donate Now';
$secondaryCta = $pageConfig['secondary_cta_text'] ?? 'Learn More';
$secondaryCtaLink = $pageConfig['secondary_cta_link'] ?? '#about';
$aboutHeading = $pageConfig['about_heading'] ?? 'About this campaign';
$recentHeading = $pageConfig['recent_supporters_heading'] ?? 'Recent supporters';
$bottomHeadline = $pageConfig['bottom_cta_headline'] ?? 'Ready to make an impact?';
$bottomBody = $pageConfig['bottom_cta_body'] ?? '';

$darkHero = $pageConfig['dark_hero'] ?? true;
$showProgress = $pageConfig['show_progress_bar'] ?? true;
$showDonorCount = $pageConfig['show_donor_count'] ?? true;
$showGoal = $pageConfig['show_goal_amount'] ?? true;
$showDays = $pageConfig['show_days_left'] ?? true;
$showRecent = $pageConfig['show_recent_supporters'] ?? true;
$showDetails = $pageConfig['show_campaign_details'] ?? true;
$showEmbed = $pageConfig['show_embed_code'] ?? true;
$showBottom = $pageConfig['show_bottom_cta'] ?? true;

$showAnon = $pageConfig['show_anonymous_donors'] ?? true;
$showAmounts = $pageConfig['show_donation_amounts'] ?? true;
$showAvatars = $pageConfig['show_donor_avatars'] ?? true;
?>
<div class="min-h-screen bg-white">
    {{-- Safelist dynamic Tailwind color classes --}}
    <div class="hidden bg-emerald-500 bg-blue-500 bg-amber-500 bg-rose-500 bg-violet-500 bg-cyan-500 text-emerald-700 text-blue-700 text-amber-700 text-rose-700 text-violet-700 text-cyan-700 bg-emerald-100 bg-blue-100 bg-amber-100 bg-rose-100 bg-violet-100 bg-cyan-100"></div>

    {{-- Hero Section --}}
    <div class="relative {{ $darkHero ? 'bg-slate-900 text-white' : 'bg-white text-slate-900 border-b border-slate-200' }}">
        @if($campaign->cover_image)
            <div class="absolute inset-0 {{ $darkHero ? 'opacity-40' : 'opacity-10' }}">
                <img src="{{ $campaign->cover_image }}" alt="{{ $campaign->name }}" class="h-full w-full object-cover">
            </div>
            @if($darkHero)
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
            @endif
        @endif

        <div class="relative mx-auto max-w-4xl px-6 py-20 md:py-28">
            <div class="mx-auto max-w-2xl text-center">
                <span class="inline-flex items-center rounded-full {{ $darkHero ? 'bg-white/10 text-white/80' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-medium backdrop-blur-sm">
                    {{ ucfirst($campaign->status) }}
                </span>
                <h1 class="mt-6 text-4xl font-bold tracking-tight md:text-5xl">
                    {{ $heroHeadline }}
                </h1>
                <p class="mt-4 text-lg {{ $darkHero ? 'text-white/70' : 'text-slate-600' }}">
                    {{ $heroSubheadline }}
                </p>
                <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <a href="/donate/{{ $campaign->public_id }}" class="rounded-lg {{ $darkHero ? 'bg-white text-slate-900 hover:bg-slate-100' : 'bg-slate-900 text-white hover:bg-slate-800' }} px-8 py-3.5 text-base font-semibold shadow-lg transition">
                        {{ $primaryCta }}
                    </a>
                    <a href="{{ $secondaryCtaLink }}" class="rounded-lg border {{ $darkHero ? 'border-white/20 bg-white/5 text-white hover:bg-white/10' : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50' }} px-8 py-3.5 text-base font-medium backdrop-blur-sm transition">
                        {{ $secondaryCta }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-6 py-12">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-10">

                @if($showProgress)
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
                        <div class="h-full rounded-full {{ $color['bg'] }} transition-all duration-500" style="width: {{ $campaign->progress_percentage }}%"></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm">
                        <div class="flex items-center gap-6">
                            @if($showDonorCount)
                            <div>
                                <div class="text-2xl font-bold text-slate-900">{{ $campaign->donor_count }}</div>
                                <div class="text-xs text-slate-500">Donors</div>
                            </div>
                            @endif
                            @if($showGoal)
                            <div>
                                <div class="text-2xl font-bold text-slate-900">{{ $campaign->goal_amount }}</div>
                                <div class="text-xs text-slate-500">Goal</div>
                            </div>
                            @endif
                            @if($showDays && $campaign->end_date)
                            <div>
                                <div class="text-2xl font-bold text-slate-900">{{ max(0, round(now()->diffInDays($campaign->end_date, false))) }}</div>
                                <div class="text-xs text-slate-500">Days Left</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- About Campaign --}}
                <section id="about">
                    <h2 class="text-2xl font-bold text-slate-900">{{ $aboutHeading }}</h2>
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
                @if($showRecent && $recentDonations->count() > 0)
                <section>
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">{{ $recentHeading }}</h2>
                    <div class="space-y-3">
                        @foreach($recentDonations as $donation)
                            @php
                                $isAnon = empty($donation->profile);
                                if (! $showAnon && $isAnon) continue;
                                $name = $isAnon ? 'Anonymous' : $donation->profile->full_name;
                                $initial = $isAnon ? 'A' : substr($donation->profile->first_name, 0, 1);
                            @endphp
                            <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50/50 px-5 py-4">
                                <div class="flex items-center gap-4">
                                    @if($showAvatars)
                                    <div class="size-10 rounded-full {{ $color['bg-light'] }} flex items-center justify-center text-sm font-bold {{ $color['text'] }}">
                                        {{ $initial }}
                                    </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">{{ $name }}</div>
                                        <div class="text-xs text-slate-500">{{ $donation->donation_date->diffForHumans() }}</div>
                                    </div>
                                </div>
                                @if($showAmounts)
                                <div class="text-right">
                                    <div class="text-sm font-bold text-slate-900">{{ $donation->amount }}</div>
                                    @if($donation->frequency !== 'one-time')
                                        <div class="text-xs text-slate-500">{{ $donation->frequency }}</div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

            </div>

            {{-- Sidebar Sticky --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">

                    @if($showDetails)
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
                        </div>
                    </div>
                    @endif

                    @if($showEmbed)
                    {{-- Embed Code --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-6">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Embed this campaign</h3>
                        <p class="mt-2 text-xs text-slate-500">Add this donation form to your website.</p>
                        <div class="mt-3">
                            <pre class="rounded-lg bg-slate-50 border border-slate-200 p-3 text-xs text-slate-700 overflow-x-auto whitespace-pre-wrap">&lt;iframe
  src="{{ config('app.url') }}/embed/{{ $campaign->slug }}"
  width="100%"
  height="650"
  frameborder="0"
  style="border: none; border-radius: 12px;"
  title="Donate to {{ $campaign->name }}"
&gt;&lt;/iframe&gt;</pre>
                        </div>
                        <button onclick="navigator.clipboard.writeText(this.previousElementSibling.querySelector('pre').innerText).then(() => { this.innerText = 'Copied!'; setTimeout(() => this.innerText = 'Copy code', 2000); })" class="mt-3 w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            Copy code
                        </button>
                    </div>
                    @endif

                </div>
            </aside>

        </div>
    </div>

    @if($showBottom)
    {{-- Bottom CTA --}}
    <div class="border-t border-slate-100 bg-slate-50/50 py-16">
        <div class="mx-auto max-w-2xl px-6 text-center">
            <h2 class="text-3xl font-bold text-slate-900">{{ $bottomHeadline }}</h2>
            <p class="mt-4 text-lg text-slate-600">
                {{ $bottomBody ?: 'Join '.$campaign->donor_count.' donors who have already supported this campaign.' }}
            </p>
            <a href="/donate/{{ $campaign->public_id }}" class="mt-8 inline-block rounded-lg bg-slate-900 px-8 py-3.5 text-base font-semibold text-white shadow-lg hover:bg-slate-800 transition">
                {{ $primaryCta }}
            </a>
        </div>
    </div>
    @endif

</div>
