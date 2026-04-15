<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
?>

<div>
<?php
    // #[Computed] methods are NOT auto-injected into blade scope for Volt anonymous
    // class components — pull them in explicitly. The methods are still memoized
    // (cached per request), so each call below does not re-query the database.
    $locations        = $this->locations;
    $availableFloors  = $this->availableFloors;
    $areaParts        = $this->areaParts;
    $locationProgress = $this->locationProgress;
    $shifts           = ['AM', 'PM'];
    $days             = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday'];
?>





<div wire:loading.delay style="display:none" class="checklist-progress-bar"></div>


<div
    x-data="{ offline: !navigator.onLine }"
    x-on:online.window="offline = false; document.body.classList.remove('is-offline')"
    x-on:offline.window="offline = true; document.body.classList.add('is-offline')"
    x-init="if (offline) document.body.classList.add('is-offline')"
    x-show="offline"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    style="display:none"
    class="fixed inset-x-0 top-0 z-[9998] flex items-start gap-3 bg-amber-50 border-b border-amber-300 px-4 py-3 text-sm shadow-md dark:bg-amber-900/30 dark:border-amber-700"
    role="alert"
>
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <div class="min-w-0">
        <p class="font-semibold text-amber-800 dark:text-amber-300">You're offline</p>
        <p class="text-amber-700 dark:text-amber-400">
            Camera still works — photos are saved locally.
            Changing location or date requires a connection.
        </p>
    </div>
</div>

<section class="w-full">
    <?php echo $__env->make('partials.checklist-heading', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (isset($component)) { $__componentOriginal12ee43392dd2a18848f46f7708481d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal12ee43392dd2a18848f46f7708481d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'af6a29d55d306249cfe5b80ece79872b::Maintenance.checklist.layout','data' => ['wide' => true,'routeName' => 'Maintenance.checklist.check','locationId' => $selectedLocationId,'locationName' => $selectedLocation,'selectedPeriod' => $periodType]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pages::Maintenance.checklist.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wide' => true,'route-name' => 'Maintenance.checklist.check','locationId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedLocationId),'locationName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedLocation),'selectedPeriod' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($periodType)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="space-y-4">
            <?php
                $periodLabel = match ($periodType) {
                    'weekly' => __('Weekly'),
                    'monthly' => __('Monthly'),
                    'nightly' => __('Nightly'),
                    default => __('Daily'),
                };
                $periodContext = match ($periodType) {
                    'weekly' => ($weeklyWeeks['w1']['label'] ?? __('Current Week')),
                    'monthly' => ($monthlyPeriods['m1']['label'] ?? __('Current Month')),
                    default => \Carbon\Carbon::parse($selectedDate)->format('M d, Y'),
                };
                $sectionLabel = __('Maintenance Checklist');
                $checklistUrl = route('Maintenance.checklist.check', array_filter([
                    'period' => $periodType,
                    'location' => $selectedLocationId,
                    'location_name' => $selectedLocation,
                    'prefill_location' => $selectedLocationId ? 1 : null,
                    'date' => $periodType === 'daily' ? $selectedDate : null,
                ], fn ($value) => $value !== null && $value !== ''));
                $periodUrl = route('Maintenance.checklist.check', array_filter([
                    'period' => $periodType,
                    'location' => $selectedLocationId,
                    'location_name' => $selectedLocation,
                    'prefill_location' => $selectedLocationId ? 1 : null,
                    'date' => $periodType === 'daily' ? $selectedDate : null,
                ], fn ($value) => $value !== null && $value !== ''));
            ?>
            <div class="flex flex-col gap-3">
                <div class="min-w-0">
                    <?php if (isset($component)) { $__componentOriginalbbbea167ab072e3e3621cf7b736152aa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbbbea167ab072e3e3621cf7b736152aa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periodType === 'daily' && $showDailyChecklist): ?>
                            <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => ['href' => '#','wire:click.prevent' => 'showDailyCalendar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','wire:click.prevent' => 'showDailyCalendar']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($sectionLabel); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                        <?php else: ?>
                            <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => ['href' => ''.e($checklistUrl).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($checklistUrl).'','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($sectionLabel); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periodType === 'daily' && $showDailyChecklist): ?>
                            <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => ['href' => '#','wire:click.prevent' => 'showDailyCalendar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','wire:click.prevent' => 'showDailyCalendar']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($periodLabel); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                        <?php else: ?>
                            <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => ['href' => ''.e($periodUrl).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($periodUrl).'','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($periodLabel); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periodType === 'daily' && $showDailyChecklist): ?>
                            <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => ['href' => '#','wire:click.prevent' => 'showDailyCalendar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','wire:click.prevent' => 'showDailyCalendar']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($periodContext); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                        <?php else: ?>
                            <?php if (isset($component)) { $__componentOriginalced986e8ff6641d3797206c3198c2b83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalced986e8ff6641d3797206c3198c2b83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::breadcrumbs.item','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::breadcrumbs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($periodContext); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $attributes = $__attributesOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__attributesOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalced986e8ff6641d3797206c3198c2b83)): ?>
<?php $component = $__componentOriginalced986e8ff6641d3797206c3198c2b83; ?>
<?php unset($__componentOriginalced986e8ff6641d3797206c3198c2b83); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbbbea167ab072e3e3621cf7b736152aa)): ?>
<?php $attributes = $__attributesOriginalbbbea167ab072e3e3621cf7b736152aa; ?>
<?php unset($__attributesOriginalbbbea167ab072e3e3621cf7b736152aa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbbbea167ab072e3e3621cf7b736152aa)): ?>
<?php $component = $__componentOriginalbbbea167ab072e3e3621cf7b736152aa; ?>
<?php unset($__componentOriginalbbbea167ab072e3e3621cf7b736152aa); ?>
<?php endif; ?>
                </div>

                <div class="w-full overflow-hidden">
                    <?php
                        $filteredLocations = $floorFilter !== ''
                            ? array_values(array_filter($locations, fn ($l) => $l['floor'] === $floorFilter))
                            : $locations;
                        $locationChunks = array_chunk($filteredLocations, 9);
                    ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($availableFloors) > 1): ?>
                        <div class="mb-2 flex gap-1.5">
                            <button
                                type="button"
                                wire:click="$set('floorFilter', '')"
                                class="checklist-interactive rounded-lg border px-3 py-1 text-xs font-semibold transition"
                                style="<?php echo e($floorFilter === ''
                                    ? 'border-color:#097b86;background-color:#097b86;color:white;'
                                    : 'border-color:#e5e7eb;background-color:white;color:#4b5563;'); ?>"
                            >All</button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableFloors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <button
                                    type="button"
                                    wire:click="$set('floorFilter', '<?php echo e(addslashes($floor)); ?>')"
                                    class="checklist-interactive rounded-lg border px-3 py-1 text-xs font-semibold transition hover:border-[#097b86] hover:bg-teal-50 dark:hover:border-[#097b86] dark:hover:bg-teal-900/20"
                                    style="<?php echo e($floorFilter === $floor
                                        ? 'border-color:#097b86;background-color:#097b86;color:white;'
                                        : 'border-color:#e5e7eb;background-color:white;color:#4b5563;'); ?>"
                                ><?php echo e($floor); ?></button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div
                        x-data="{
                            page: Math.min(window._locSliderPage ?? 0, Math.max(0, <?php echo e(count($locationChunks)); ?> - 1)),
                            total: <?php echo e(count($locationChunks)); ?>,
                            touchStartX: 0,
                            prev() { if (this.page > 0) { this.page--; window._locSliderPage = this.page; } },
                            next() { if (this.page < this.total - 1) { this.page++; window._locSliderPage = this.page; } },
                            onTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX; },
                            onTouchEnd(e) {
                                const dx = e.changedTouches[0].screenX - this.touchStartX;
                                if (dx < -40) this.next();
                                else if (dx > 40) this.prev();
                            },
                        }"
                        class="w-full"
                    >
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLocation !== ''): ?>
                            <div class="mb-2 flex items-center gap-2">
                                <span class="flex-1 truncate rounded-lg bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">
                                    <?php echo e($selectedLocation); ?>

                                </span>
                                <button
                                    type="button"
                                    wire:click="clearSelectedLocation"
                                    class="checklist-interactive inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-zinc-300 bg-white text-zinc-500 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                    aria-label="<?php echo e(__('Clear location')); ?>"
                                >&times;</button>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div
                            class="w-full overflow-hidden"
                            @touchstart.passive="onTouchStart($event)"
                            @touchend.passive="onTouchEnd($event)"
                        >
                            <div
                                class="flex transition-transform duration-300 ease-in-out"
                                :style="`transform: translateX(-${page * 100}%)`"
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $locationChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="grid w-full shrink-0 grid-cols-3 gap-2" style="min-width:100%">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <?php
                                                $locProg  = $locationProgress[$location['id']] ?? ['pct' => 0, 'done' => 0, 'total' => 0];
                                                $locPct   = $locProg['pct'];
                                                $locDone  = $locProg['done'];
                                                $locTotal = $locProg['total'];
                                                $isDone   = $locTotal > 0 && $locDone >= $locTotal;
                                                $isActive = $selectedLocationId === ($location['id'] ?? null);
                                                $fillColor = $isDone ? 'rgba(9,123,134,0.18)' : 'rgba(9,123,134,0.13)';
                                            ?>
                                            <button
                                                type="button"
                                                @click="window._locSliderPage = page"
                                                wire:click="selectLocationByName('<?php echo e(addslashes($location['display_name'])); ?>')"
                                                class="checklist-interactive relative flex flex-col items-center gap-1 overflow-hidden rounded-xl border px-1 py-3 text-center text-xs font-medium transition
                                                    <?php echo e($isActive
                                                        ? 'border-sky-500 bg-sky-50 text-sky-700 dark:border-sky-500 dark:bg-sky-900/30 dark:text-sky-300'
                                                        : 'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800'); ?>"
                                            >
                                                
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($locPct > 0): ?>
                                                    <span
                                                        class="pointer-events-none absolute bottom-0 left-0 right-0 transition-all duration-500"
                                                        style="height: <?php echo e($locPct); ?>%; background: <?php echo e($fillColor); ?>;"
                                                        aria-hidden="true"
                                                    ></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDone): ?>
                                                    <!-- <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 items-center justify-center
                                                     rounded-full border-2 border-white bg-[#097b86] text-white dark:border-zinc-900" aria-hidden="true">
                                                        <svg class="h-2.5 w-2.5" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/>
                                                        </svg>
                                                    </span> -->
                                                <?php elseif($locPct > 0): ?>
                                                    
                                                    <span class="absolute right-1 top-1 rounded-full bg-teal-600 px-1 py-px text-[8px] font-bold leading-none text-white" aria-hidden="true">
                                                        <?php echo e($locDone); ?>/<?php echo e($locTotal); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                <span class="relative z-10 line-clamp-2 leading-tight"><?php echo e($location['display_name']); ?></span>
                                            </button>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = count($chunk); $i < 9; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i % 3 === 0 && $i !== 0): ?>  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($locationChunks) > 1): ?>
                            <div class="mt-2 flex items-center justify-between">
                                <button type="button" @click="prev" :disabled="page === 0"
                                    :class="page === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-zinc-100 dark:hover:bg-zinc-700'"
                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-zinc-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-800">
                                    <svg class="h-3.5 w-3.5 text-zinc-600 dark:text-zinc-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <div class="flex gap-1.5">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $locationChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $_): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span
                                            :class="<?php echo e($i); ?> === page ? 'bg-sky-500 w-4' : 'bg-zinc-300 dark:bg-zinc-600 w-1.5'"
                                            class="h-1.5 rounded-full transition-all duration-300"
                                        ></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                                <button type="button" @click="next" :disabled="page >= total - 1"
                                    :class="page >= total - 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-zinc-100 dark:hover:bg-zinc-700'"
                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-zinc-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-800">
                                    <svg class="h-3.5 w-3.5 text-zinc-600 dark:text-zinc-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($periodType, ['daily', 'nightly']) && ! $showDailyChecklist): ?>
                <?php
                    $calendarBase = \Carbon\Carbon::parse($calendarMonth)->startOfMonth();
                    $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();
                    $weekdayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    $firstVisibleDate = $calendarBase->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                    $cellCount = 42;
                ?>
                <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

                    
                    <div class="flex items-center justify-between px-5 py-4" style="background: linear-gradient(135deg, #1e3a5f 0%, #097b86 100%);">
                        <button type="button" wire:click="previousCalendarMonth"
                                class="checklist-interactive flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                aria-label="<?php echo e(__('Previous month')); ?>">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div class="text-center">
                            <p class="mb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/55">
                                <?php echo e($periodType === 'nightly' ? 'Nightly' : 'Daily'); ?> — Pick a Date
                            </p>
                            <p class="text-lg font-bold leading-none text-white">
                                <?php echo e($calendarBase->format('F')); ?>

                                <span class="font-normal text-white/60"><?php echo e($calendarBase->format('Y')); ?></span>
                            </p>
                        </div>
                        <button type="button" wire:click="nextCalendarMonth"
                                class="checklist-interactive flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                aria-label="<?php echo e(__('Next month')); ?>">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    
                    <div class="grid border-b border-zinc-100 dark:border-zinc-700/50" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekdayHeaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weekdayHeader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="py-2 text-center text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                <?php echo e($weekdayHeader); ?>

                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    
                    <div class="grid gap-1 p-3" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($cell = 0; $cell < $cellCount; $cell++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $cellDateObj = $firstVisibleDate->copy()->addDays($cell);
                                $cellDate    = $cellDateObj->toDateString();
                                $dayNumber   = $cellDateObj->day;
                                $isCurrentMonth = $cellDateObj->month === $calendarBase->month;
                                $isSelected  = $cellDate === $selectedDate;
                                $isToday     = $cellDate === $today;
                                $isFuture    = $cellDate > $today;
                            ?>
                            <button type="button"
                                    wire:click="selectCalendarDate('<?php echo e($cellDate); ?>')"
                                    <?php if($isFuture): echo 'disabled'; endif; ?>
                                    class="<?php echo e($isFuture ? '' : 'checklist-interactive'); ?> relative flex aspect-square items-center justify-center rounded-xl text-sm font-semibold transition-all
                                        <?php echo e($isSelected ? 'text-white shadow-md' : ''); ?>

                                        <?php echo e($isToday && ! $isSelected ? 'ring-2 ring-offset-1' : ''); ?>

                                        <?php echo e(! $isSelected && ! $isFuture && $isCurrentMonth ? 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-700/50' : ''); ?>

                                        <?php echo e(! $isCurrentMonth && ! $isFuture ? 'text-zinc-300 hover:bg-zinc-50 dark:text-zinc-600 dark:hover:bg-zinc-800' : ''); ?>

                                        <?php echo e($isFuture ? 'cursor-not-allowed opacity-30' : ''); ?>"
                                    style="<?php echo e($isSelected ? 'background: linear-gradient(135deg, #1e3a5f, #097b86);' : ''); ?>

                                           <?php echo e($isToday && ! $isSelected ? 'ring-color: #097b86;' : ''); ?>">
                                <?php echo e($dayNumber); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isToday && ! $isSelected): ?>
                                    <span class="absolute bottom-1 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full" style="background-color:#097b86;"></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedDate): ?>
                        <div class="border-t border-zinc-100 px-4 py-2 text-center text-[11px] font-medium text-zinc-400 dark:border-zinc-700/50 dark:text-zinc-500">
                            Selected:
                            <span class="font-semibold text-zinc-600 dark:text-zinc-300">
                                <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('l, F d Y')); ?>

                            </span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLocationId !== null && in_array($periodType, ['weekly', 'monthly'], true)): ?>
                <?php
                    $dayColumns = match ($periodType) {
                        'weekly' => $weeklyWeeks,
                        'monthly' => $monthlyPeriods,
                        default => $days,
                    };
                    $periodShifts = $periodType === 'daily' ? $shifts : ['AM'];
                    $totalColumns = 1 + (count($dayColumns) * count($periodShifts));
                    $activeLabel = $periodType === 'weekly'
                        ? ($weeklyWeeks['w1']['label'] ?? __('Current Week'))
                        : ($monthlyPeriods['m1']['label'] ?? __('Current Month'));
                    $periodCardTitle = $periodType === 'weekly' ? __('Checklist Week') : __('Checklist Month');
                    $activeDate = $periodType === 'weekly'
                        ? ($weeklyWeeks['w1']['start_date'] ?? now('Asia/Manila')->toDateString())
                        : ($monthlyPeriods['m1']['start_date'] ?? now('Asia/Manila')->toDateString());
                ?>
                
                <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 mb-3">
                    <div class="flex items-center justify-between px-5 py-4" style="background: linear-gradient(135deg, #1e3a5f 0%, #097b86 100%);">
                        <button type="button"
                                wire:click="<?php echo e($periodType === 'weekly' ? 'previousWeeklyPeriod' : 'previousMonthlyPeriod'); ?>"
                                class="checklist-interactive flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                aria-label="<?php echo e(__('Previous period')); ?>">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div class="text-center">
                            <p class="mb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/55">
                                <?php echo e($periodType === 'weekly' ? 'Weekly Checklist' : 'Monthly Checklist'); ?>

                            </p>
                            <p class="text-lg font-bold leading-none text-white">
                                <?php echo e(\Carbon\Carbon::parse($activeDate)->format('F')); ?>

                                <span class="font-normal text-white/60"><?php echo e(\Carbon\Carbon::parse($activeDate)->format('Y')); ?></span>
                            </p>
                            <p class="mt-1 text-xs font-medium text-white/70"><?php echo e($activeLabel); ?></p>
                        </div>
                        <button type="button"
                                wire:click="<?php echo e($periodType === 'weekly' ? 'nextWeeklyPeriod' : 'nextMonthlyPeriod'); ?>"
                                class="checklist-interactive flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                aria-label="<?php echo e(__('Next period')); ?>">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold text-white" style="background-color:#097b86;">
                            <?php echo e($selectedLocation); ?>

                        </span>
                        <button type="button"
                                id="openDailyCameraBtn"
                                data-frequency="<?php echo e($periodType); ?>"
                                data-day-key="<?php echo e($periodType === 'weekly' ? 'w1' : 'm1'); ?>"
                                data-date-label="<?php echo e(\Carbon\Carbon::parse($activeDate)->format('M d, Y')); ?>"
                                data-location="<?php echo e($selectedLocation); ?>"
                                data-captured-by="<?php echo e(auth()->user()?->name ?? ''); ?>"
                                class="checklist-interactive inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-semibold text-zinc-700 shadow-sm transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200"
                                aria-label="<?php echo e(__('Open camera')); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" />
                                <path d="M10 8a3 3 0 100 6 3 3 0 000-6z" />
                            </svg>
                            Camera
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <table class="min-w-full border-collapse text-sm">
                        <thead class="bg-zinc-100 dark:bg-zinc-800">
                            <tr>
                                <th class="border border-zinc-200 px-4 py-2 text-left dark:border-zinc-700"><?php echo e(__('Area Part')); ?></th>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dayColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayKey => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <th colspan="2" class="border border-zinc-200 px-3 py-2 text-center dark:border-zinc-700">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periodType === 'weekly'): ?>
                                            <div class="font-semibold">
                                                <?php echo e($dayName['label']); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($dayName['is_current'])): ?>
                                                    <span class="ml-1 inline-block h-2 w-2 rounded-full bg-sky-500 align-middle"></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php elseif($periodType === 'monthly'): ?>
                                            <div class="font-semibold">
                                                <?php echo e($dayName['label']); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($dayName['is_current'])): ?>
                                                    <span class="ml-1 inline-block h-2 w-2 rounded-full bg-sky-500 align-middle"></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="font-semibold"><?php echo e($dayName); ?></div>
                                            <div class="text-xs text-zinc-500"><?php echo e(\Carbon\Carbon::parse($weekDates[$dayKey])->format('M d, Y')); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </th>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tr>
                            <tr>
                                <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_keys($dayColumns); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periodType === 'daily'): ?>
                                        <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-orange-600 dark:border-zinc-700 dark:text-orange-400">AM</th>
                                        <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-sky-600 dark:border-zinc-700 dark:text-sky-400">PM</th>
                                    <?php else: ?>
                                        <th class="border border-zinc-200 px-2 py-1 text-center dark:border-zinc-700"><?php echo e(__('Check')); ?></th>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__wm_subArea = '__init__'; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $areaParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($part['sub_area'] !== $__wm_subArea): ?>
                                    <?php $__wm_subArea = $part['sub_area']; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__wm_subArea !== null): ?>
                                        <tr>
                                            <td colspan="<?php echo e($totalColumns); ?>" class="border border-zinc-200 bg-sky-50 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-sky-700 dark:border-zinc-700 dark:bg-sky-900/30 dark:text-sky-300">
                                                <?php echo e($__wm_subArea); ?>

                                            </td>
                                        </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                    <td class="border border-zinc-200 px-4 py-2 font-medium dark:border-zinc-700">
                                        <div class="flex items-center justify-between gap-2">
                                            <span><?php echo e($part['display_name']); ?></span>
                                            <?php
                                                $previewDayKey = array_key_first($dayColumns);
                                                $hasProofPreview = $previewDayKey !== null
                                                    ? $this->hasSlotProof($part['id'], $previewDayKey, 'AM')
                                                    : false;
                                            ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasProofPreview): ?>
                                                <button
                                                    type="button"
                                                    wire:click="openProofPreview(<?php echo e($part['id']); ?>, '<?php echo e($previewDayKey); ?>', 'AM')"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                                    aria-label="<?php echo e(__('Preview proof image')); ?>"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                        <circle cx="16.5" cy="9" r="1.5"></circle>
                                                        <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                    </svg>
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_keys($dayColumns); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periodShifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <?php
                                                $selected = $this->isSlotSelected($part['id'], $dayKey, $shift);
                                                $locked = $this->isSlotLockedForFuture($dayKey);
                                            ?>
                                            <td
                                                class="border border-zinc-200 px-2 py-2 text-center dark:border-zinc-700 <?php echo e($locked ? 'opacity-50' : ''); ?>"
                                            >
                                                <input
                                                    type="checkbox"
                                                    <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'slot-week-'.e($part['id']).'-'.e($dayKey).'-'.e($shift).''; ?>wire:key="slot-week-<?php echo e($part['id']); ?>-<?php echo e($dayKey); ?>-<?php echo e($shift); ?>"
                                                    disabled
                                                    <?php if($selected): echo 'checked'; endif; ?>
                                                    class="h-4 w-4 cursor-not-allowed rounded border-zinc-300 text-sky-600 focus:ring-sky-500 disabled:cursor-not-allowed dark:border-zinc-600 dark:bg-zinc-900"
                                                />
                                            </td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="<?php echo e($totalColumns); ?>" class="border border-zinc-200 px-4 py-6 text-center text-zinc-500 dark:border-zinc-700">
                                        <?php echo e(__('No mapped checklist parts found. Add rows to location_area_parts with the selected frequency.')); ?>

                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif($selectedLocationId !== null && $periodType === 'nightly' && $showDailyChecklist): ?>
                <div class="space-y-5 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="rounded-xl border border-indigo-200 bg-gradient-to-r from-indigo-50 to-white p-4 dark:border-indigo-700 dark:from-indigo-900/30 dark:to-zinc-800">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="space-y-1">
                                <?php if (isset($component)) { $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::heading','data' => ['size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'lg']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Nightly Checklist')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $attributes = $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $component = $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                                    <span class="rounded-full bg-indigo-100 px-2.5 py-0.5 font-medium text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                        <?php echo e($selectedLocation); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    id="openDailyCameraBtn"
                                    data-frequency="nightly"
                                    data-day-key="selected"
                                    data-date-label="<?php echo e(\Carbon\Carbon::parse($selectedDate)->format('M d, Y')); ?>"
                                    data-location="<?php echo e($selectedLocation); ?>"
                                    data-captured-by="<?php echo e(auth()->user()?->name ?? ''); ?>"
                                    class="checklist-interactive inline-flex items-center gap-2 rounded-lg border border-indigo-300 bg-white px-3 py-2 text-sm font-medium text-indigo-700 shadow-sm hover:bg-indigo-50 dark:border-indigo-700 dark:bg-zinc-900 dark:text-indigo-300 dark:hover:bg-indigo-900/30"
                                    aria-label="<?php echo e(__('Open camera')); ?>"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" />
                                        <path d="M10 8a3 3 0 100 6 3 3 0 000-6z" />
                                    </svg>
                                    <?php echo e(__('Camera')); ?>

                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="max-h-[65vh] overflow-auto rounded-xl border border-zinc-200 shadow-sm dark:border-zinc-700">
                        <table class="min-w-full border-collapse text-sm">
                            <thead class="sticky top-0 z-10 bg-indigo-50 dark:bg-indigo-900/30">
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-3 text-left font-semibold dark:border-zinc-700"><?php echo e(__('Area Part')); ?></th>
                                    <th class="border border-zinc-200 px-3 py-3 text-center dark:border-zinc-700">
                                        <div class="font-semibold"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('l')); ?></div>
                                        <div class="text-xs text-zinc-500"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('M d, Y')); ?></div>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                    <th class="border border-zinc-200 px-2 py-2 text-center font-semibold text-indigo-600 dark:border-zinc-700 dark:text-indigo-400"><?php echo e(__('Check')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__night_subArea = '__init__'; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $areaParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($part['sub_area'] !== $__night_subArea): ?>
                                        <?php $__night_subArea = $part['sub_area']; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__night_subArea !== null): ?>
                                            <tr>
                                                <td colspan="2" class="border border-zinc-200 bg-sky-50 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-sky-700 dark:border-zinc-700 dark:bg-sky-900/30 dark:text-sky-300">
                                                    <?php echo e($__night_subArea); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                        <td class="border border-zinc-200 px-4 py-3 font-medium dark:border-zinc-700">
                                            <?php $hasNightProof = $this->hasSlotProof($part['id'], 'selected', 'PM'); ?>
                                            <div class="flex items-center justify-between gap-2">
                                                <span><?php echo e($part['display_name']); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasNightProof): ?>
                                                    <button
                                                        type="button"
                                                        wire:click="openProofPreview(<?php echo e($part['id']); ?>, 'selected', 'PM')"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-indigo-300 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:border-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50"
                                                        aria-label="<?php echo e(__('Preview proof image')); ?>"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                            <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                            <circle cx="16.5" cy="9" r="1.5"></circle>
                                                            <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                        </svg>
                                                    </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </td>
                                        <?php
                                            $selected = $this->isSlotSelected($part['id'], 'selected', 'PM');
                                            $locked   = $this->isSlotLockedForFuture('selected');
                                        ?>
                                        <td class="border border-zinc-200 px-2 py-3 text-center dark:border-zinc-700 <?php echo e($locked ? 'opacity-50' : ''); ?>">
                                            <input
                                                type="checkbox"
                                                <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'slot-night-'.e($part['id']).'-selected-PM'; ?>wire:key="slot-night-<?php echo e($part['id']); ?>-selected-PM"
                                                disabled
                                                <?php if($selected): echo 'checked'; endif; ?>
                                                class="h-4 w-4 cursor-not-allowed rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500 disabled:cursor-not-allowed dark:border-indigo-600 dark:bg-zinc-900"
                                            />
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="2" class="border border-zinc-200 px-4 py-8 text-center text-zinc-500 dark:border-zinc-700">
                                            <?php echo e(__('No mapped checklist parts found. Add rows to location_area_parts with nightly frequency.')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php elseif($selectedLocationId !== null && $periodType === 'daily' && $showDailyChecklist): ?>
                <div class="space-y-5 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="rounded-xl border border-zinc-200 bg-gradient-to-r from-zinc-50 to-white p-4 dark:border-zinc-700 dark:from-zinc-900 dark:to-zinc-800">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="space-y-1">
                                <?php if (isset($component)) { $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::heading','data' => ['size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'lg']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($periodType === 'nightly' ? __('Nightly Checklist') : __('Daily Checklist')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $attributes = $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $component = $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                                    <span class="rounded-full bg-sky-100 px-2.5 py-0.5 font-medium text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                                        <?php echo e($selectedLocation); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    id="openDailyCameraBtn"
                                    data-frequency="daily"
                                    data-day-key="selected"
                                    data-date-label="<?php echo e(\Carbon\Carbon::parse($selectedDate)->format('M d, Y')); ?>"
                                    data-location="<?php echo e($selectedLocation); ?>"
                                    data-captured-by="<?php echo e(auth()->user()?->name ?? ''); ?>"
                                    class="checklist-interactive inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-700 shadow-sm hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                    aria-label="<?php echo e(__('Open camera')); ?>"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" />
                                        <path d="M10 8a3 3 0 100 6 3 3 0 000-6z" />
                                    </svg>
                                    <?php echo e(__('Camera')); ?>

                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="max-h-[65vh] overflow-auto rounded-xl border border-zinc-200 shadow-sm dark:border-zinc-700">
                        <table class="min-w-full border-collapse text-sm">
                            <thead class="sticky top-0 z-10 bg-zinc-100 dark:bg-zinc-800">
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-3 text-left font-semibold dark:border-zinc-700"><?php echo e(__('Area Part')); ?></th>
                                    <th colspan="2" class="border border-zinc-200 px-3 py-3 text-center dark:border-zinc-700">
                                        <div class="font-semibold"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('l')); ?></div>
                                        <div class="text-xs text-zinc-500"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('M d, Y')); ?></div>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                    <th class="border border-zinc-200 px-2 py-2 text-center font-semibold text-orange-600 dark:border-zinc-700 dark:text-orange-400">AM</th>
                                    <th class="border border-zinc-200 px-2 py-2 text-center font-semibold text-sky-600 dark:border-zinc-700 dark:text-sky-400">PM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__daily_subArea = '__init__'; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $areaParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($part['sub_area'] !== $__daily_subArea): ?>
                                        <?php $__daily_subArea = $part['sub_area']; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($__daily_subArea !== null): ?>
                                            <tr>
                                                <td colspan="3" class="border border-zinc-200 bg-sky-50 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-sky-700 dark:border-zinc-700 dark:bg-sky-900/30 dark:text-sky-300">
                                                    <?php echo e($__daily_subArea); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                        <td class="border border-zinc-200 px-4 py-3 font-medium dark:border-zinc-700">
                                            <?php
                                                $hasAmProof = $this->hasSlotProof($part['id'], 'selected', 'AM');
                                                $hasPmProof = $this->hasSlotProof($part['id'], 'selected', 'PM');
                                            ?>
                                            <div class="flex items-center justify-between gap-2">
                                                <span><?php echo e($part['display_name']); ?></span>
                                                <div class="flex items-center gap-1">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasAmProof): ?>
                                                        <button
                                                            type="button"
                                                            wire:click="openProofPreview(<?php echo e($part['id']); ?>, 'selected', 'AM')"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-orange-300 bg-orange-50 text-orange-600 hover:bg-orange-100 dark:border-orange-700 dark:bg-orange-900/30 dark:text-orange-300 dark:hover:bg-orange-900/50"
                                                            aria-label="<?php echo e(__('Preview AM proof image')); ?>"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                            </svg>
                                                        </button>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasPmProof): ?>
                                                        <button
                                                            type="button"
                                                            wire:click="openProofPreview(<?php echo e($part['id']); ?>, 'selected', 'PM')"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-sky-300 bg-sky-50 text-sky-600 hover:bg-sky-100 dark:border-sky-700 dark:bg-sky-900/30 dark:text-sky-300 dark:hover:bg-sky-900/50"
                                                            aria-label="<?php echo e(__('Preview PM proof image')); ?>"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                            </svg>
                                                        </button>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <?php
                                                $selected = $this->isSlotSelected($part['id'], 'selected', $shift);
                                                $locked = $this->isSlotLockedForFuture('selected');
                                            ?>
                                            <td
                                                class="border border-zinc-200 px-2 py-3 text-center dark:border-zinc-700 <?php echo e($locked ? 'opacity-50' : ''); ?>"
                                            >
                                                <input
                                                    type="checkbox"
                                                    <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'slot-day-'.e($part['id']).'-selected-'.e($shift).''; ?>wire:key="slot-day-<?php echo e($part['id']); ?>-selected-<?php echo e($shift); ?>"
                                                    disabled
                                                    <?php if($selected): echo 'checked'; endif; ?>
                                                    class="h-4 w-4 cursor-not-allowed rounded border-zinc-300 text-sky-600 focus:ring-sky-500 disabled:cursor-not-allowed dark:border-zinc-600 dark:bg-zinc-900"
                                                />
                                            </td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="3" class="border border-zinc-200 px-4 py-8 text-center text-zinc-500 dark:border-zinc-700">
                                            <?php echo e(__('No mapped checklist parts found. Add rows to location_area_parts with daily frequency.')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php elseif($selectedLocationId !== null && in_array($periodType, ['daily', 'nightly'])): ?>
                <div class="rounded-xl border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                    <?php echo e(__('Select a date to load the daily checklist.')); ?>

                </div>
            <?php else: ?>
                <div class="rounded-xl border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                    <?php echo e(__('Select a frequency and area location to load the checklist table.')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showProofPreviewModal): ?>
                
                <div
                    x-data="{ url: null }"
                    x-on:proof-preview-url.window="url = $event.detail.url"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                >
                    <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-zinc-900">
                        <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
                            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                <?php echo e($proofPreviewTitle ?? __('Proof Preview')); ?>

                            </h3>
                            <button
                                type="button"
                                wire:click="closeProofPreview"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                aria-label="<?php echo e(__('Close preview')); ?>"
                            >
                                &times;
                            </button>
                        </div>
                        <div class="p-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proofPreviewSkipReason): ?>
                                <div class="flex flex-col items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-8 text-center dark:border-amber-700/40 dark:bg-amber-900/20">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-800/40">
                                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Photo Skipped</p>
                                        <p class="mt-1 text-xs text-amber-700 dark:text-amber-400"><?php echo e($proofPreviewSkipReason); ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mx-auto w-full max-w-sm">
                                    <div x-show="url" class="aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                        <img :src="url" alt="<?php echo e(__('Proof image')); ?>" class="h-full w-full object-contain">
                                    </div>
                                    <div x-show="!url" class="rounded-md border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                                        <?php echo e(__('No proof image available for this item.')); ?>

                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal12ee43392dd2a18848f46f7708481d39)): ?>
<?php $attributes = $__attributesOriginal12ee43392dd2a18848f46f7708481d39; ?>
<?php unset($__attributesOriginal12ee43392dd2a18848f46f7708481d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal12ee43392dd2a18848f46f7708481d39)): ?>
<?php $component = $__componentOriginal12ee43392dd2a18848f46f7708481d39; ?>
<?php unset($__componentOriginal12ee43392dd2a18848f46f7708481d39); ?>
<?php endif; ?>

</section>

<?php
    $activePeriodKey = $periodType === 'weekly'
        ? 'w1'
        : ($periodType === 'monthly' ? 'm1' : 'selected');
?>
<?php if (isset($component)) { $__componentOriginal308b536a96782c61c6e834984d7325d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal308b536a96782c61c6e834984d7325d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.checklist-proof-camera-modal','data' => ['areaParts' => $areaParts,'selectedSlots' => $selectedSlots,'selectedLocation' => $selectedLocation,'selectedDate' => $selectedDate,'periodType' => $periodType,'activePeriodKey' => $activePeriodKey]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('checklist-proof-camera-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['area-parts' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($areaParts),'selected-slots' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedSlots),'selected-location' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedLocation),'selected-date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedDate),'period-type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($periodType),'active-period-key' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activePeriodKey)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal308b536a96782c61c6e834984d7325d1)): ?>
<?php $attributes = $__attributesOriginal308b536a96782c61c6e834984d7325d1; ?>
<?php unset($__attributesOriginal308b536a96782c61c6e834984d7325d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal308b536a96782c61c6e834984d7325d1)): ?>
<?php $component = $__componentOriginal308b536a96782c61c6e834984d7325d1; ?>
<?php unset($__componentOriginal308b536a96782c61c6e834984d7325d1); ?>
<?php endif; ?>

<?php if (! $__env->hasRenderedOnce('fcb919a0-30ee-4716-acf2-aaa9997f2ac4')): $__env->markAsRenderedOnce('fcb919a0-30ee-4716-acf2-aaa9997f2ac4'); ?>
<script>
(function () {
    const STORE_KEY = 'nlah_checklist_last_location';
    const userId    = <?php echo e(auth()->id() ?? 'null'); ?>;
    const storageKey = STORE_KEY + (userId ? '_' + userId : '');

    // ── Save current location whenever Livewire updates the page ────────────
    // We read the location ID and name from the server-rendered data attributes
    // so we don't need to know Livewire internals.
    function saveIfSelected() {
        const locId   = <?php echo e($selectedLocationId ?? 'null'); ?>;
        const locName = <?php echo \Illuminate\Support\Js::from($selectedLocation)->toHtml() ?>;
        const period  = <?php echo \Illuminate\Support\Js::from($periodType)->toHtml() ?>;
        if (locId) {
            try {
                localStorage.setItem(storageKey, JSON.stringify({
                    id: locId, name: locName, period: period
                }));
            } catch {}
        }
    }

    // Run on initial render
    saveIfSelected();

    // Re-run after every Livewire update (location may have changed)
    document.addEventListener('livewire:update', saveIfSelected);

    // ── On page load: if no location is pre-selected, redirect to last one ──
    // This only runs when arriving at the bare checklist URL with no location.
    const currentLocId = <?php echo e($selectedLocationId ?? 'null'); ?>;
    if (!currentLocId) {
        try {
            const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
            if (saved && saved.id && saved.name) {
                // Build the URL with saved location pre-selected and navigate
                const url = new URL(window.location.href);
                url.searchParams.set('location', saved.id);
                url.searchParams.set('location_name', saved.name);
                url.searchParams.set('prefill_location', '1');
                if (saved.period) url.searchParams.set('period', saved.period);
                // Replace current history entry so Back button still works
                window.location.replace(url.toString());
            }
        } catch {}
    }
})();
</script>


<?php endif; ?>
</div><?php /**PATH D:\nlahweb\nlahinventory\storage\framework/views/livewire/views/3332eeaa.blade.php ENDPATH**/ ?>