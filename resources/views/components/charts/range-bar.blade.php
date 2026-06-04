@props([
    'data' => [],          // [['label' => '$0-10', 'count' => 50, 'color' => '#10b981'], ...]
    'height' => 200,
    'barHeight' => 32,
])

@php
$maxCount = max(collect($data)->pluck('count')->toArray() + [1]);
$totalCount = collect($data)->sum('count');
@endphp

<div class="space-y-2 {{ $attributes->get('class') }}">
    @foreach($data as $item)
        @php
        $width = ($item['count'] / $maxCount) * 100;
        $percentage = $totalCount > 0 ? round(($item['count'] / $totalCount) * 100) : 0;
        @endphp
        <div class="flex items-center gap-3">
            <div class="w-20 text-xs text-slate-500 text-right flex-shrink-0">
                {{ $item['label'] }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-slate-100 rounded-full overflow-hidden" style="height: {{ $barHeight / 4 }}px;">
                        <div
                            class="h-full rounded-full transition-all duration-700"
                            style="width: {{ $width }}%; background-color: {{ $item['color'] ?? '#10b981' }}"
                        ></div>
                    </div>
                    <span class="text-xs font-medium text-slate-700 w-12">{{ $item['count'] }}</span>
                    <span class="text-[10px] text-slate-400 w-10">{{ $percentage }}%</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
