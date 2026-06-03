<div 
    x-data="{
        active: 'overview',

        init() {
            const sections = document.querySelectorAll('[data-section]')

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.active = entry.target.id
                    }
                })
            }, {
                rootMargin: '-25% 0px -65% 0px',
                threshold: 0
            })

            sections.forEach((section) => {
                observer.observe(section)
            })
        },

        scrollToSection(id) {
            document.getElementById(id)?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            })
        }
    }"
    class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900"
>
    <div class="mx-auto max-w-7xl">

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm font-medium text-slate-500">
            <a href="/users" class="hover:text-slate-900">Users</a> >
        </div>

        {{-- Header --}}
        <div class="mb-10 border-b border-slate-200 pb-8">
            <div class="flex items-center gap-4">
                <div class="size-14 rounded-full bg-slate-200 flex items-center justify-center text-xl font-bold text-slate-700">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">{{ $user->name }}</h1>
                    <div class="mt-1 flex items-center gap-3 text-slate-500">
                        <span>{{ $user->email }}</span>
                        <span>·</span>
                        <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">

            {{-- MAIN CONTENT --}}
            <main class="space-y-6 lg:col-span-9">

                {{-- Overview Section --}}
                <section
                    id="overview"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="user" />
                            <h2 class="text-xl font-semibold">Overview</h2>
                        </div>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <x-detail-row label="Full name">
                            {{ $user->name }}
                        </x-detail-row>

                        <x-detail-row label="Email">
                            <div class="flex items-center gap-2">
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <span class="rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Verified</span>
                                @else
                                    <span class="rounded-lg bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Unverified</span>
                                @endif
                            </div>
                        </x-detail-row>

                        <x-detail-row label="User ID">
                            <span class="font-mono text-sm">{{ $user->id }}</span>
                            <x-copy-button :text="(string) $user->id" class="ml-2">Copy</x-copy-button>
                        </x-detail-row>

                        <x-detail-row label="Joined">
                            {{ $user->created_at->format('F d, Y \a\t g:i A') }}
                        </x-detail-row>

                        <x-detail-row label="Last updated">
                            {{ $user->updated_at->format('F d, Y \a\t g:i A') }}
                        </x-detail-row>
                    </div>
                </section>

                {{-- Profile Details Section --}}
                <section
                    id="profile"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="settings" />
                            <h2 class="text-xl font-semibold">Profile Details</h2>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        @if ($user->profile)
                            <div class="space-y-5">
                                <x-detail-row label="First name">
                                    {{ $user->profile->first_name }}
                                </x-detail-row>

                                <x-detail-row label="Last name">
                                    {{ $user->profile->last_name }}
                                </x-detail-row>

                                <x-detail-row label="Phone">
                                    {{ $user->profile->phone ?? '—' }}
                                </x-detail-row>

                                <x-detail-row label="Country">
                                    {{ $user->profile->country ?? '—' }}
                                </x-detail-row>

                                <x-detail-row label="Address">
                                    @if ($user->profile->address_line_1)
                                        <p>{{ $user->profile->address_line_1 }}</p>
                                        @if ($user->profile->address_line_2)
                                            <p>{{ $user->profile->address_line_2 }}</p>
                                        @endif
                                        <p class="text-slate-500">
                                            {{ collect([$user->profile->city, $user->profile->state, $user->profile->postal_code])->filter()->join(', ') }}
                                        </p>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </x-detail-row>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <x-icon name="user" class="mx-auto size-10 text-slate-300" />
                                <h3 class="mt-3 text-sm font-medium text-slate-900">No profile linked</h3>
                                <p class="mt-1 text-sm text-slate-500">This user does not have a donor profile.</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Security Section --}}
                <section
                    id="security"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="hash" />
                            <h2 class="text-xl font-semibold">Security</h2>
                        </div>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <x-detail-row label="Password">
                            <span class="text-slate-400">••••••••</span>
                        </x-detail-row>

                        <x-detail-row label="Two-factor authentication">
                            <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">Not enabled</span>
                        </x-detail-row>

                        <x-detail-row label="Last login">
                            <span class="text-slate-400">—</span>
                        </x-detail-row>
                    </div>
                </section>

                {{-- Activity Section --}}
                <section
                    id="activity"
                    data-section
                    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <x-icon name="zap" />
                            <h2 class="text-xl font-semibold">Activity</h2>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="text-center py-10">
                            <x-icon name="activity" class="mx-auto size-10 text-slate-300" />
                            <h3 class="mt-3 text-sm font-medium text-slate-900">No recent activity</h3>
                            <p class="mt-1 text-sm text-slate-500">User actions will appear here.</p>
                        </div>
                    </div>
                </section>

            </main>

            {{-- RIGHT SIDEBAR --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-6 space-y-6">

                    {{-- Actions --}}
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <button class="flex w-full items-center gap-3 border-b border-slate-200 px-5 py-4 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">
                            <x-icon name="pencil" class="size-4 text-slate-500" />
                            <span>Edit user</span>
                        </button>

                        <button class="flex w-full items-center gap-3 px-5 py-4 text-left text-sm font-medium text-red-600 hover:bg-red-50">
                            <x-icon name="trash-2" class="size-4" />
                            <span>Delete user</span>
                        </button>
                    </div>

                    {{-- Section Navigation --}}
                    <nav class="rounded-xl border border-slate-200 bg-white p-2">
                        @foreach ($sections as $section)
                            <button
                                type="button"
                                @click="scrollToSection('{{ $section['id'] }}')"
                                class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-sm transition"
                                :class="active === '{{ $section['id'] }}'
                                    ? 'bg-slate-100 font-semibold text-slate-900'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                            >
                                <span class="w-5 text-center">{{ $section['icon'] }}</span>
                                <span>{{ $section['label'] }}</span>
                            </button>
                        @endforeach
                    </nav>

                </div>
            </aside>

        </div>
    </div>
</div>
