<div
    x-data="{ confirmCancel: false }"
    class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900"
>
    <div class="mx-auto max-w-3xl">

        {{-- Breadcrumb --}}
        <div class="mb-6 text-sm font-medium text-slate-500">
            <a href="/campaigns" class="hover:text-slate-900">Campaigns</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900">Create campaign</span>
        </div>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight">Create campaign</h1>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="save" class="space-y-6">

            {{-- Basic info --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold">Basic information</h2>
                </div>

                <div class="space-y-5 px-6 py-6">

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">
                            Campaign name <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model.live.debounce.300ms="name"
                            id="name"
                            type="text"
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                            placeholder="e.g. MTMT Development Fund"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="slug"
                            id="slug"
                            type="text"
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                            placeholder="e.g. mtmt-development-fund"
                        >
                        <p class="mt-1 text-xs text-slate-500">Used in URLs. Auto-generated from name but you can customize.</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">
                            Description
                        </label>
                        <textarea
                            wire:model="description"
                            id="description"
                            rows="4"
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                            placeholder="Describe the purpose of this campaign..."
                        ></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- Goal & dates --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold">Goal & dates</h2>
                </div>

                <div class="space-y-5 px-6 py-6">

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        {{-- Goal amount --}}
                        <div>
                            <label for="goalAmount" class="block text-sm font-medium text-slate-700">
                                Goal amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-1.5">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                                <input
                                    wire:model="goalAmount"
                                    id="goalAmount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="block w-full rounded-lg border border-slate-300 bg-white pl-7 pr-3 py-2 text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                    placeholder="0.00"
                                >
                            </div>
                            @error('goalAmount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Currency --}}
                        <div>
                            <label for="currency" class="block text-sm font-medium text-slate-700">
                                Currency <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model="currency"
                                id="currency"
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                            >
                                <option value="USD">USD — US Dollar</option>
                                <option value="EUR">EUR — Euro</option>
                                <option value="GBP">GBP — British Pound</option>
                                <option value="AUD">AUD — Australian Dollar</option>
                                <option value="CAD">CAD — Canadian Dollar</option>
                                <option value="SGD">SGD — Singapore Dollar</option>
                                <option value="MYR">MYR — Malaysian Ringgit</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        {{-- Start date --}}
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-slate-700">
                                Start date
                            </label>
                            <input
                                wire:model="startDate"
                                id="startDate"
                                type="date"
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                            >
                            @error('startDate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- End date --}}
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-slate-700">
                                End date
                            </label>
                            <input
                                wire:model="endDate"
                                id="endDate"
                                type="date"
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                            >
                            @error('endDate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>
            </section>

            {{-- Status --}}
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold">Status</h2>
                </div>

                <div class="space-y-5 px-6 py-6">

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700">
                            Campaign status <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="status"
                            id="status"
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                        >
                            <option value="active">Active</option>
                            <option value="paused">Paused</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- Actions --}}
            <div class="flex items-center justify-between">
                <button
                    type="button"
                    @click="confirmCancel = true"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition disabled:opacity-50"
                >
                    <span wire:loading.remove>Create campaign</span>
                    <span wire:loading>Creating...</span>
                </button>
            </div>

        </form>

        {{-- Cancel confirmation --}}
        <div
            x-show="confirmCancel"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
            @click.self="confirmCancel = false"
        >
            <div
                x-show="confirmCancel"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-sm rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden"
            >
                <div class="px-6 py-5 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Discard changes?</h3>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-slate-600">
                        Any unsaved information will be lost. Are you sure you want to leave this page?
                    </p>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <button
                        @click="confirmCancel = false"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                    >
                        Stay
                    </button>
                    <a
                        href="/campaigns"
                        wire:navigate
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 transition"
                    >
                        Leave
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
