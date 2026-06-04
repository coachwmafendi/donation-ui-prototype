@props([
    'data' => [],          // [['label' => 'Name', 'value' => 30, 'color' => '#10b981'], ...]
    'height' => 200,
    'barHeight' => 32,
    'showValues' => true,
])

@php
$max = max(collect($data)->pluck('value')->toArray());
if ($max === 0) $max = 1;
@endphp

<div class="space-y-3 {{ $attributes->get('class') }}">
    @foreach($data as $item)
        @php
        $width = ($item['value'] / $max) * 100;
        @endphp
        <div class="flex items-center gap-3">
            <div class="w-24 text-xs text-slate-500 truncate text-right flex-shrink-0">
                {{ $item['label'] }}
            </div>
            <div class="flex-1 h-{{ $barHeight / 4 }} bg-slate-100 rounded-full overflow-hidden">
                <div
                    class="h-full rounded-full transition-all duration-700"
                    style="width: {{ $width }}%; background-color: {{ $item['color'] ?? '#10b981' }}"
                ></div>
            </div>
            @if($showValues)
                <div class="w-12 text-xs font-medium text-slate-700 text-right flex-shrink-0">
                    {{ is_numeric($item['value']) && $item['value'] > 100 ? number_format($item['value']) : $item['value'] }}
                </div>
            @endif
        </div>
    @endforeach
</div>
