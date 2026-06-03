@props(['id', 'title', 'iconName' => null, 'icon' => null])

<section
    id="{{ $id }}"
    data-section
    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
>
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex items-center gap-3">
            <span class="text-slate-500">
                @if($iconName)
                    <x-icon name="{{ $iconName }}" />
                @else
                    {!! $icon !!}
                @endif
            </span>
            <h2 class="text-xl font-semibold">{{ $title }}</h2>
        </div>
    </div>

    <div class="space-y-5 px-6 py-6">
        {{ $slot }}
    </div>
</section>