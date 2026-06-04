@props([
    'percentage' => 0,     // 0-100
    'size' => 120,
    'strokeWidth' => 10,
    'color' => '#10b981',
    'bgColor' => '#e2e8f0',
])

@php
$center = $size / 2;
$radius = ($size - $strokeWidth) / 2;
$circumference = 2 * pi() * $radius;
$dash = ($percentage / 100) * $circumference;
$gap = $circumference - $dash;
@endphp

<div class="relative inline-flex items-center justify-center {{ $attributes->get('class') }}">
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 {{ $size }} {{ $size }}">
        {{-- Background circle --}}
        <circle
            cx="{{ $center }}"
            cy="{{ $center }}"
            r="{{ $radius }}"
            fill="none"
            stroke="{{ $bgColor }}"
            stroke-width="{{ $strokeWidth }}"
        />
        {{-- Progress circle --}}
        <circle
            cx="{{ $center }}"
            cy="{{ $center }}"
            r="{{ $radius }}"
            fill="none"
            stroke="{{ $color }}"
            stroke-width="{{ $strokeWidth }}"
            stroke-dasharray="{{ $dash }} {{ $gap }}"
            stroke-linecap="round"
            transform="rotate(-90 {{ $center }} {{ $center }})"
            class="transition-all duration-1000"
        />
    </svg>
    <div class="absolute inset-0 flex items-center justify-center">
        <span class="text-lg font-bold text-slate-900">{{ round($percentage) }}%</span>
    </div>
</div>
