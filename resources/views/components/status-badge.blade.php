@php
$styles = [
    'succeeded' => 'bg-emerald-100 text-emerald-700',
    'success' => 'bg-emerald-100 text-emerald-700',
    'active' => 'bg-emerald-100 text-emerald-700',
    'paid' => 'bg-emerald-100 text-emerald-700',
    'pending' => 'bg-amber-100 text-amber-700',
    'processing' => 'bg-amber-100 text-amber-700',
    'failed' => 'bg-red-100 text-red-700',
    'error' => 'bg-red-100 text-red-700',
    'refunded' => 'bg-slate-100 text-slate-700',
    'cancelled' => 'bg-slate-100 text-slate-700',
    'archived' => 'bg-slate-100 text-slate-700',
    'draft' => 'bg-slate-100 text-slate-700',
];

$status = strtolower($status);
$style = $styles[$status] ?? 'bg-slate-100 text-slate-700';
@endphp

<span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-medium {{ $style }}">
    {{ ucfirst($status) }}
</span>
