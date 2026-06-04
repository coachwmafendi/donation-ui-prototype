@props(['title', 'description', 'icon'])

<div class="py-8 text-center">
    @if($icon)
        <div class="mb-4">
            @include($icon)
        </div>
    @endif
    @if($title)
        <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
    @endif
    @if($description)
        <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
    @endif>
</div>