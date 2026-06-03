<button
    type="button"
    @click="
        navigator.clipboard.writeText($el.dataset.text).then(() => {
            copied = true
            $dispatch('copied', { text: $el.dataset.text })
            setTimeout(() => copied = false, 2000)
        })
    "
    class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs font-medium transition {{ $attributes->get('class') }}"
    :class="copied ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'text-slate-600 hover:bg-slate-50'"
    x-data="{ copied: false }"
    data-text="{{ $text }}"
    @copied.window="if ($el.dataset.text === $event.detail.text) { copied = true; setTimeout(() => copied = false, 2000) }"
>
    <span x-show="!copied">{{ $slot ?? 'Copy' }}</span>
    <span x-show="copied" class="flex items-center gap-1">
        <x-icon name="check" class="size-3" />
        Copied
    </span>
</button>
