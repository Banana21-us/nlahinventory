
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'wireProperty' => '',
    'current'      => '',
    'options'      => [],
    'placeholder'  => 'Select…',
    'error'        => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'wireProperty' => '',
    'current'      => '',
    'options'      => [],
    'placeholder'  => 'Select…',
    'error'        => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $optionsJson = json_encode($options);
    $currentJson = json_encode((string) $current);
?>

<div
    x-data="{
        open: false,
        value: <?php echo e($currentJson); ?>,
        options: <?php echo e($optionsJson); ?>,
        get label() {
            const found = this.options.find(o => o.value === this.value);
            return found ? found.label : '<?php echo e(addslashes($placeholder)); ?>';
        },
        select(val) {
            this.value = val;
            this.open = false;
            $wire.set('<?php echo e($wireProperty); ?>', val, true);
        }
    }"
    @click.outside="open = false"
    class="relative w-full"
>
    
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
            <?php echo e($placeholder); ?>

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

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
    <span class="text-red-500 text-xs mt-1 block"><?php echo e($error); ?></span>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/components/custom-select.blade.php ENDPATH**/ ?>