<button
    type="button"
    wire:click="openProofPreview({{ $partId }}, '{{ $dayKey }}', '{{ $shift }}')"
    class="inline-flex h-8 w-8 items-center justify-center rounded-md border {{ $theme === 'orange' ? 'border-orange-300 bg-orange-50 text-orange-600 hover:bg-orange-100 dark:border-orange-700 dark:bg-orange-900/30 dark:text-orange-300 dark:hover:bg-orange-900/50' : 'border-indigo-300 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:border-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50' }}"
    aria-label="{{ $shift === 'AM' ? __('Preview AM proof image') : __('Preview PM proof image') }}"
>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
        <circle cx="16.5" cy="9" r="1.5"></circle>
        <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
    </svg>
</button>
