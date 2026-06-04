@props([
    'data' => [],          // [['label' => 'Freq', 'value' => 80], ...]
    'size' => 200,
    'fillColor' => '#10b981',
    'fillOpacity' => 0.15,
])

@php
$center = $size / 2;
$radius = ($size / 2) - 20;
$totalAxes = count($data);
$max = max(collect($data)->pluck('value')->toArray() + [100]);

// Build polygon points
$points = collect($data)->map(function ($item, $index) use ($center, $radius, $totalAxes, $max) {
    $angle = (2 * pi() * $index / $totalAxes) - (pi() / 2);
    $r = ($item['value'] / $max) * $radius;
    $x = $center + $r * cos($angle);
    $y = $center + $r * sin($angle);
    return round($x, 1) . ',' . round($y, 1);
})->implode(' ');

// Grid levels
$levels = [0.2, 0.4, 0.6, 0.8, 1.0];
@endphp

<svg class="{{ $attributes->get('class') }}" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 {{ $size }} {{ $size }}">
    {{-- Grid levels --}}
    @foreach($levels as $level)
        @php
        $levelPoints = collect(range(0, $totalAxes - 1))->map(function ($index) use ($center, $radius, $totalAxes, $level) {
            $angle = (2 * pi() * $index / $totalAxes) - (pi() / 2);
            $r = $radius * $level;
            $x = $center + $r * cos($angle);
            $y = $center + $r * sin($angle);
            return round($x, 1) . ',' . round($y, 1);
        })->implode(' ');
        @endphp
        <polygon points="{{ $levelPoints }}" fill="none" stroke="#e2e8f0" stroke-width="1"/>
    @endforeach

    {{-- Axes --}}
    @foreach($data as $index => $item)
        @php
        $angle = (2 * pi() * $index / $totalAxes) - (pi() / 2);
        $x = $center + $radius * cos($angle);
        $y = $center + $radius * sin($angle);
        @endphp
        <line x1="{{ $center }}" y1="{{ $center }}" x2="{{ $x }}" y2="{{ $y }}" stroke="#e2e8f0" stroke-width="1"/>
        
        {{-- Labels --}}
        @php
        $labelX = $center + ($radius + 15) * cos($angle);
        $labelY = $center + ($radius + 15) * sin($angle);
        @endphp
        <text x="{{ $labelX }}" y="{{ $labelY }}" text-anchor="middle" dominant-baseline="middle" class="text-[8px] fill-slate-400">
            {{ Str::limit($item['label'], 8) }}
        </text>
    @endforeach

    {{-- Data fill --}}
    <polygon points="{{ $points }}" fill="{{ $fillColor }}" fill-opacity="{{ $fillOpacity }}" stroke="{{ $fillColor }}" stroke-width="2"/>

    {{-- Data points --}}
    @foreach($data as $index => $item)
        @php
        $angle = (2 * pi() * $index / $totalAxes) - (pi() / 2);
        $r = ($item['value'] / $max) * $radius;
        $x = $center + $r * cos($angle);
        $y = $center + $r * sin($angle);
        @endphp
        <circle cx="{{ $x }}" cy="{{ $y }}" r="3" fill="white" stroke="{{ $fillColor }}" stroke-width="2"/>
    @endforeach
</svg>
