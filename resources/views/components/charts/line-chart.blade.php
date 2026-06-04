@props([
    'data' => [],          // [['date' => 'Jan', 'value' => 30], ...]
    'width' => 600,
    'height' => 220,
    'color' => '#10b981',
])

@php
$max = max(collect($data)->pluck('value')->toArray());
if ($max === 0) $max = 1;

$paddingX = 40;
$paddingY = 30;
$chartW = $width - ($paddingX * 2);
$chartH = $height - ($paddingY * 2);

$points = collect($data)->map(function ($item, $index) use ($data, $chartW, $chartH, $paddingX, $paddingY, $max) {
    $x = $paddingX + ($index / (count($data) - 1)) * $chartW;
    $y = $paddingY + $chartH - (($item['value'] / $max) * $chartH);
    return ['x' => round($x, 1), 'y' => round($y, 1)];
});

// Build path
$path = $points->map(function ($point, $index) {
    if ($index === 0) return "M {$point['x']} {$point['y']}";
    return "L {$point['x']} {$point['y']}";
})->implode(' ');

// Build area
$areaPath = $path . " L {$points->last()['x']} " . ($paddingY + $chartH) . " L {$points->first()['x']} " . ($paddingY + $chartH) . " Z";

// Grid lines
$gridY = $paddingY + $chartH;
@endphp

<div class="{{ $attributes->get('class') }}" style="height: {{ $height }}px;">
    <svg class="w-full h-full" viewBox="0 0 {{ $width }} {{ $height }}" preserveAspectRatio="none">
        <defs>
            <linearGradient id="growthGradient" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="{{ $color }}" stop-opacity="0.15"/>
                <stop offset="100%" stop-color="{{ $color }}" stop-opacity="0"/>
            </linearGradient>
        </defs>

        {{-- Grid --}}
        @foreach([0, 0.25, 0.5, 0.75, 1] as $grid)
            @php $y = $paddingY + ($chartH * $grid); @endphp
            <line x1="{{ $paddingX }}" y1="{{ $y }}" x2="{{ $width - $paddingX }}" y2="{{ $y }}" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 4"/>
        @endforeach

        {{-- Area fill --}}
        <path d="{{ $areaPath }}" fill="url(#growthGradient)"/>

        {{-- Line --}}
        <path d="{{ $path }}" fill="none" stroke="{{ $color }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

        {{-- Dots --}}
        @foreach($points as $point)
            <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="3" fill="white" stroke="{{ $color }}" stroke-width="2"/>
        @endforeach
    </svg>

    {{-- Labels --}}
    <div class="flex justify-between px-10 -mt-6">
        @foreach($data as $index => $item)
            <span class="text-[10px] text-slate-400 {{ $index % 2 !== 0 ? 'hidden lg:block' : '' }}">{{ $item['date'] }}</span>
        @endforeach
    </div>
</div>
