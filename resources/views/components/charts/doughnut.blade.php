@props([
    'data' => [],          // [['label' => 'Name', 'value' => 30, 'color' => '#10b981'], ...]
    'size' => 160,
    'strokeWidth' => 24,
])

@php
$total = collect($data)->sum('value');
$center = $size / 2;
$radius = ($size - $strokeWidth) / 2;
$circumference = 2 * pi() * $radius;
$offset = 0;
@endphp

<svg class="{{ $attributes->get('class') }}" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 {{ $size }} {{ $size }}">
    @foreach($data as $segment)
        @php
        $percentage = $total > 0 ? ($segment['value'] / $total) : 0;
        $dash = $circumference * $percentage;
        $gap = $circumference - $dash;
        @endphp
        <circle
            cx="{{ $center }}"
            cy="{{ $center }}"
            r="{{ $radius }}"
            fill="none"
            stroke="{{ $segment['color'] ?? '#e2e8f0' }}"
            stroke-width="{{ $strokeWidth }}"
            stroke-dasharray="{{ $dash }} {{ $gap }}"
            stroke-dashoffset="{{ -$offset }}"
            stroke-linecap="round"
            transform="rotate(-90 {{ $center }} {{ $center }})"
        />
        @php $offset += $dash; @endphp
    @endforeach
</svg>
