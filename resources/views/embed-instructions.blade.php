<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Embed Donation Form — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f7f7fb; }
        .code-block { background: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; font-family: monospace; font-size: 0.875rem; overflow-x: auto; white-space: pre-wrap; word-break: break-all; }
    </style>
</head>
<body class="min-h-screen">
    <div class="mx-auto max-w-3xl px-6 py-12">

        <div class="mb-10">
            <h1 class="text-3xl font-bold text-slate-900">Embed Donation Form</h1>
            <p class="mt-2 text-slate-600">Add a donation form to your website, blog, or masjid page in minutes.</p>
        </div>

        {{-- Production URL Warning --}}
        <div class="mb-8 rounded-xl border border-red-200 bg-red-50 p-5">
            <div class="flex items-start gap-3">
                <svg class="size-5 text-red-600 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <div>
                    <h3 class="font-semibold text-red-900">Important: Production URL Required</h3>
                    <p class="mt-1 text-sm text-red-700">
                        Below examples use <code class="bg-red-100 px-1 rounded">{{ request()->getSchemeAndHttpHost() }}</code> which only works on this local computer.
                    </p>
                    <p class="mt-2 text-sm text-red-700">
                         Set <code class="bg-red-100 px-1 rounded">APP_URL</code> in your <code class="bg-red-100 px-1 rounded">.env</code> to your production domain (e.g. <code class="bg-red-100 px-1 rounded">https://yoursite.com</code>) before sharing embed codes.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-8">

            {{-- General Embed --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">General Donation Form</h2>
                <p class="mt-2 text-sm text-slate-500">Donors choose which campaign to support.</p>

                <div class="mt-4">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Embed code</label>
                    <pre class="code-block mt-2">&lt;iframe
  src="{{ url('/embed') }}"
  width="100%"
  height="700"
  frameborder="0"
  style="border: none; border-radius: 12px;"
  title="Donation Form"
&gt;&lt;/iframe&gt;</pre>
                </div>

                <div class="mt-4">
                    <a href="/embed" target="_blank" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                        Preview form →
                    </a>
                </div>
            </div>

            {{-- Campaign-specific Embed --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Campaign-Specific Form</h2>
                <p class="mt-2 text-sm text-slate-500">Pre-select a campaign. Donors just pick an amount.</p>

                @php
                    $campaigns = App\Models\Campaign::where('status', 'active')->get(['slug', 'name', 'public_id']);
                @endphp

                @if($campaigns->count() > 0)
                    <div class="mt-4 space-y-4">
                        @foreach($campaigns as $campaign)
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-slate-900">{{ $campaign->name }}</h3>
                                        <p class="text-xs text-slate-500 mt-1">Slug: <code class="bg-slate-100 px-1.5 py-0.5 rounded">{{ $campaign->slug }}</code></p>
                                    </div>
                                    <button onclick="copyEmbed('{{ $campaign->slug }}')" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        Copy code
                                    </button>
                                </div>
                                <pre class="code-block mt-3 text-xs">&lt;iframe
  src="{{ url('/embed') }}/{{ $campaign->slug }}"
  width="100%"
  height="650"
  frameborder="0"
  style="border: none; border-radius: 12px;"
  title="Donate to {{ $campaign->name }}"
&gt;&lt;/iframe&gt;</pre>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-4 text-sm text-slate-500 italic">No active campaigns yet. Create one first.</p>
                @endif
            </div>

            {{-- Tips --}}
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-6">
                <h2 class="text-lg font-semibold text-amber-900">Tips</h2>
                <ul class="mt-3 space-y-2 text-sm text-amber-800">
                    <li>• Adjust the <code class="bg-amber-100 px-1 rounded">height</code> based on your layout (try 600–800px).</li>
                    <li>• The form is responsive and works on mobile.</li>
                    <li>• Use <code class="bg-amber-100 px-1 rounded">border-radius</code> to match your website design.</li>
                    <li>• Donations are tracked and visible in your admin dashboard.</li>
                </ul>
            </div>

            {{-- Deployment --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Deploy to production</h2>
                <p class="mt-2 text-sm text-slate-500">Before embedding on live websites:</p>
                <ol class="mt-4 space-y-3 text-sm text-slate-700 list-decimal list-inside">
                    <li>Deploy this app to a public server (e.g. <a href="https://cloud.laravel.com" target="_blank" class="text-blue-600 hover:underline">Laravel Cloud</a>, VPS, shared hosting)</li>
                    <li>Update <code class="bg-slate-100 px-1 rounded">APP_URL</code> in <code class="bg-slate-100 px-1 rounded">.env</code> to your domain</li>
                    <li>Set up SSL/HTTPS (required for embed security)</li>
                    <li>Generate new embed codes — auto-updated URLs below</li>
                </ol>
                <div class="mt-4 rounded-lg bg-slate-50 p-4">
                    <p class="text-xs font-medium text-slate-500 uppercase">Current APP_URL</p>
                    <p class="mt-1 text-sm font-mono text-slate-900">{{ config('app.url') }}</p>
                </div>
            </div>

        </div>

        <div class="mt-12 text-center text-sm text-slate-500">
            <a href="/dashboard" class="text-blue-600 hover:text-blue-800">← Back to dashboard</a>
        </div>
    </div>

    <script>
        function copyEmbed(slug) {
            const baseUrl = '{{ config('app.url') }}';
            const code = `<iframe\n  src="${baseUrl}/embed/${slug}"\n  width="100%"\n  height="650"\n  frameborder="0"\n  style="border: none; border-radius: 12px;"\n  title="Donation Form"\n></iframe>`;
            navigator.clipboard.writeText(code).then(() => {
                alert('Embed code copied!\n\nNote: Update APP_URL in .env before using on production.');
            });
        }
    </script>
</body>
</html>
