@props([
    'steps' => [],         // [['label' => 'Started', 'value' => 100, 'color' => '#10b981'], ...]
    'height' => 40,
])

@php
$max = max(collect($steps)->pluck('value')->toArray() + [1]);
@endphp

<div class="space-y-3 {{ $attributes->get('class') }}">
    @foreach($steps as $index => $step)
        @php
        $width = ($step['value'] / $max) * 100;
        $isLast = $index === count($steps) - 1;
        @endphp
        <div class="flex items-center gap-3">
            <div class="w-20 text-xs text-slate-500 text-right flex-shrink-0">
                {{ $step['label'] }}
            </div>
            <div class="flex-1 relative" style="height: {{ $height }}px;">
                <div
                    class="h-full rounded-lg flex items-center px-4 transition-all duration-700"
                    style="width: {{ $width }}%; background-color: {{ $step['color'] ?? '#e2e8f0' }}"
                >
                    <span class="text-xs font-semibold text-white">
                        {{ number_format($step['value']) }}
                    </span>
                </div>
            </div>
            @if(!$isLast)
                <div class="text-[10px] text-slate-400 w-12 text-right flex-shrink-0">
                    {{ round(($steps[$index + 1]['value'] / $step['value']) * 100) }}%
                </div>
            @else
                <div class="w-12 flex-shrink-0"></div>
            @endif
        </div>
    @endforeach
</div>
