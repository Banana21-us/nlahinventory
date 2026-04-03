{{--
    Custom Select — replaces native <select> to avoid Android keyboard bottom-sheet rendering.

    Usage:
        <x-custom-select
            wire-property="leave_type"
            :current="$leave_type"
            :options="[
                ['value' => 'Sick Leave', 'label' => 'Sick Leave (SL)'],
            ]"
            placeholder="Select Type…"
            :error="$errors->first('leave_type')"
        />
--}}
@props([
    'wireProperty' => '',
    'current'      => '',
    'options'      => [],
    'placeholder'  => 'Select…',
    'error'        => null,
])

@php
    $optionsJson = json_encode($options);
    $currentJson = json_encode((string) $current);
@endphp

<div
    x-data="{
        open: false,
        value: {{ $currentJson }},
        options: {{ $optionsJson }},
        get label() {
            const found = this.options.find(o => o.value === this.value);
            return found ? found.label : '{{ addslashes($placeholder) }}';
        },
        select(val) {
            this.value = val;
            this.open = false;
            $wire.set('{{ $wireProperty }}', val, true);
        }
    }"
    @click.outside="open = false"
    class="relative w-full"
>
    {{-- Trigger --}}
    <button
        type="button"
        @click="open = !open"
        class="brand-focus flex items-center justify-between w-full rounded-md border border-gray-300 bg-white shadow-sm sm:text-sm p-2 text-left"
        :class="value ? 'text-gray-900' : 'text-gray-400'"
    >
        <span x-text="label" class="truncate"></span>
        <svg class="w-4 h-4 text-gray-400 shrink-0 ml-2 transition-transform duration-150"
             :class="open ? 'rotate-180' : ''"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg max-h-60 overflow-y-auto"
        style="display:none"
    >
        <button type="button"
            @click="select('')"
            class="w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:bg-gray-50 border-b border-gray-100">
            {{ $placeholder }}
        </button>

        <template x-for="opt in options" :key="opt.value">
            <button type="button"
                @click="select(opt.value)"
                class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                :class="value === opt.value
                    ? 'font-semibold bg-blue-50 text-[#015581]'
                    : 'text-gray-700 hover:bg-gray-50'">
                <span x-text="opt.label"></span>
            </button>
        </template>
    </div>
</div>

@if($error)
    <span class="text-red-500 text-xs mt-1 block">{{ $error }}</span>
@endif
