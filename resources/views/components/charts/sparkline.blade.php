@props([
    'data' => [],          // Array of numeric values
    'width' => 120,
    'height' => 40,
    'stroke' => '#10b981',
    'strokeWidth' => 2,
    'fill' => false,
    'fillColor' => '#10b981',
])

@php
$max = max($data);
$min = min($data);
$range = $max - $min;
if ($range === 0) $range = 1;

$points = collect($data)->map(function ($value, $index) use ($data, $width, $height, $min, $range) {
    $x = ($index / (count($data) - 1)) * $width;
    $y = $height - (($value - $min) / $range) * $height;
    return round($x, 1) . ',' . round($y, 1);
})->implode(' ');
@endphp

<svg class="{{ $attributes->get('class') }}" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 {{ $width }} {{ $height }}">
    @if($fill)
        <polygon points="0,{{ $height }} {{ $points }} {{ $width }},{{ $height }}" fill="{{ $fillColor }}" fill-opacity="0.15"/>
    @endif
    <polyline points="{{ $points }}" fill="none" stroke="{{ $stroke }}" stroke-width="{{ $strokeWidth }}" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
