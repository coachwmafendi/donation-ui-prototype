@props([
    'data' => [],          // [['day' => 0, 'hour' => 0, 'value' => 30], ...] or simple 2D array
    'cols' => 7,           // days
    'color' => '#10b981',
    'max' => null,
])

@php
$values = collect($data)->pluck('value');
$maxValue = $max ?? max($values->toArray() + [1]);
@endphp

<div class="{{ $attributes->get('class') }}">
    <div class="flex gap-1">
        @foreach($data as $item)
            @php
            $intensity = $maxValue > 0 ? ($item['value'] / $maxValue) : 0;
            $alpha = max(0.1, $intensity);
            $label = $item['label'] ?? ($item['date'] ?? '');
            @endphp
            <div class="flex-1 flex flex-col gap-1" title="{{ $label }}: {{ $item['value'] }}">
                <div
                    class="aspect-square rounded-sm transition-colors"
                    style="background-color: {{ $color }}; opacity: {{ $alpha }}"
                ></div>
            </div>
        @endforeach
    </div>
    @if(collect($data)->pluck('date')->filter()->isNotEmpty())
        <div class="flex justify-between mt-2 text-[10px] text-slate-400">
            @php $chunks = collect($data)->chunk(ceil(count($data) / 5)); @endphp
            @foreach($chunks as $chunk)
                <span>{{ $chunk->first()['date'] ?? '' }}</span>
            @endforeach
        </div>
    @endif
</div>
