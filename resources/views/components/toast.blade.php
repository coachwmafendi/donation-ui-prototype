<div
    x-data="{
        toasts: [],
        add(message, type = 'success') {
            const id = Date.now() + Math.random()
            this.toasts.push({ id, message, type })
            setTimeout(() => this.remove(id), 5000)
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id)
        }
    }"
    x-on:toast.window="add($event.detail.message, $event.detail.type)"
    class="fixed bottom-6 right-6 z-[60] flex flex-col gap-3"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave-end="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            class="flex items-center gap-3 rounded-xl px-5 py-4 shadow-lg border"
            :class="{
                'bg-emerald-50 border-emerald-200 text-emerald-800': toast.type === 'success',
                'bg-red-50 border-red-200 text-red-800': toast.type === 'error',
                'bg-amber-50 border-amber-200 text-amber-800': toast.type === 'warning',
                'bg-blue-50 border-blue-200 text-blue-800': toast.type === 'info',
            }"
        >
            {{-- Icon --}}
            <svg x-show="toast.type === 'success'" class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="m8.5 12 2.5 2.5 5-5"/>
            </svg>
            <svg x-show="toast.type === 'error'" class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <svg x-show="toast.type === 'warning'" class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <svg x-show="toast.type === 'info'" class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>

            {{-- Message --}}
            <span class="text-sm font-medium" x-text="toast.message"></span>

            {{-- Close --}}
            <button @click="remove(toast.id)" class="ml-2 text-current opacity-60 hover:opacity-100">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </template>
</div>
