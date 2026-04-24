<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
?>

<div>
    <section class="w-full">
        <?php echo $__env->make('partials.checklist-heading', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (isset($component)) { $__componentOriginal87c487d0a6659ae6b9df49b3a12e7e8a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87c487d0a6659ae6b9df49b3a12e7e8a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'af6a29d55d306249cfe5b80ece79872b::maintenance.checklist.layout','data' => ['wide' => true,'routeName' => 'Maintenance.checklist.verify','locationId' => $selectedLocationId,'locationName' => $selectedLocation,'selectedPeriod' => $periodType]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pages::maintenance.checklist.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wide' => true,'route-name' => 'Maintenance.checklist.verify','locationId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedLocationId),'locationName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedLocation),'selectedPeriod' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($periodType)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <div class="space-y-4">
                <?php
                    $periodLabel = match ($periodType) {
                        'nightly' => __('Nightly'), // Add this line
                        'weekly' => __('Weekly'),
                        'monthly' => __('Monthly'),
                        default => __('Daily'),
                    };
                    $periodContext = match ($periodType) {
                        'weekly' => ($weeklyWeeks['w1']['label'] ?? __('Current Week')),
                        'monthly' => ($monthlyPeriods['m1']['label'] ?? __('Current Month')),
                        default => \Carbon\Carbon::parse($selectedDate)->format('M d, Y'),
                    };
                    $sectionLabel = __('Verify Checklist');
                    $checklistUrl = route('Maintenance.checklist.verify', array_filter([
                        'period' => $periodType,
                        'location' => $selectedLocationId,
                        'location_name' => $selectedLocation,
                        'date' => $periodType === 'daily' ? $selectedDate : null,
                    ], fn ($value) => $value !== null && $value !== ''));
                    $periodUrl = route('Maintenance.checklist.verify', array_filter([
                        'period' => $periodType,
                        'location' => $selectedLocationId,
                        'location_name' => $selectedLocation,
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
                        <?php $locationChunks = array_chunk($locations, 9); ?>
                        <div
                            x-data="{
                                page: 0,
                                total: <?php echo e(count($locationChunks)); ?>,
                                touchStartX: 0,
                                prev() { if (this.page > 0) this.page--; },
                                next() { if (this.page < this.total - 1) this.page++; },
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
                                        class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-zinc-300 bg-white text-zinc-500 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800"
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $locationChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="grid w-full shrink-0 grid-cols-3 gap-2" style="min-width:100%">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <button
                                                    type="button"
                                                    wire:click="selectLocationByName('<?php echo e(addslashes($location['display_name'])); ?>')"
                                                    class="flex flex-col items-center gap-1 rounded-xl border px-1 py-3 text-center text-xs font-medium transition
                                                        <?php echo e($selectedLocationId === ($location['id'] ?? null)
                                                            ? 'border-sky-500 bg-sky-50 text-sky-700 dark:border-sky-500 dark:bg-sky-900/30 dark:text-sky-300'
                                                            : 'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800'); ?>"
                                                >
                                                    
                                                    <span class="line-clamp-2 leading-tight"><?php echo e($location['display_name']); ?></span>
                                                </button>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = count($chunk); $i % 3 !== 0; $i++): ?>
                                                <div></div>
                                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $locationChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $_): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
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

                        <div class="flex justify-end pt-1">
                            <button
                                type="button"
                                wire:click="exportToPdf"
                                <?php if(!$selectedLocationId || ($periodType === 'daily' && !$showDailyChecklist)): ?>
                                    disabled
                                <?php endif; ?>
                                style="
                                    display: inline-flex;
                                    width: 100%;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 0.5rem;
                                    border-radius: 0.375rem;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.875rem;
                                    font-weight: 500;
                                    <?php echo e($selectedLocationId && ($periodType !== 'daily' || $showDailyChecklist)
                                        ? 'background-color: #2563eb; color: white; border: 1px solid #1e40af;'
                                        : 'background-color: #d1d5db; color: #374151; border: 1px solid #9ca3af; cursor: not-allowed; opacity: 0.5;'); ?>

                                "
                                onmouseover="<?php echo e($selectedLocationId && ($periodType !== 'daily' || $showDailyChecklist) ? 'this.style.backgroundColor=\'#1d4ed8\'' : ''); ?>"
                                onmouseout="<?php echo e($selectedLocationId && ($periodType !== 'daily' || $showDailyChecklist) ? 'this.style.backgroundColor=\'#2563eb\'' : ''); ?>"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <span><?php echo e(__('Export PDF')); ?></span>
                            </button>
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
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
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
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                    aria-label="<?php echo e(__('Next month')); ?>">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        
                        <div class="grid border-b border-zinc-100 dark:border-zinc-700/50" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekdayHeaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weekdayHeader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="py-2 text-center text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                    <?php echo e($weekdayHeader); ?>

                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>

                        
                        <div class="grid gap-1 p-3" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($cell = 0; $cell < $cellCount; $cell++): ?>
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
                                        class="relative flex aspect-square items-center justify-center rounded-xl text-sm font-semibold transition-all
                                            <?php echo e($isSelected ? 'text-white shadow-md' : ''); ?>

                                            <?php echo e($isToday && ! $isSelected ? 'ring-2 ring-offset-1' : ''); ?>

                                            <?php echo e(! $isSelected && ! $isFuture && $isCurrentMonth ? 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-700/50' : ''); ?>

                                            <?php echo e(! $isCurrentMonth && ! $isFuture ? 'text-zinc-300 hover:bg-zinc-50 dark:text-zinc-600 dark:hover:bg-zinc-800' : ''); ?>

                                            <?php echo e($isFuture ? 'cursor-not-allowed opacity-30' : ''); ?>"
                                        style="<?php echo e($isSelected ? 'background: linear-gradient(135deg, #1e3a5f, #097b86);' : ''); ?>">
                                    <?php echo e($dayNumber); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isToday && ! $isSelected): ?>
                                        <span class="absolute bottom-1 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full" style="background-color:#097b86;"></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </button>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        $activeDate = $periodType === 'weekly'
                            ? ($weeklyWeeks['w1']['start_date'] ?? now('Asia/Manila')->toDateString())
                            : ($monthlyPeriods['m1']['start_date'] ?? now('Asia/Manila')->toDateString());
                    ?>
                    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 mb-3">
                        <div class="flex items-center justify-between px-5 py-4" style="background: linear-gradient(135deg, #1e3a5f 0%, #097b86 100%);">
                            <button type="button"
                                    wire:click="<?php echo e($periodType === 'weekly' ? 'previousWeeklyPeriod' : 'previousMonthlyPeriod'); ?>"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
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
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                    aria-label="<?php echo e(__('Next period')); ?>">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                        <table class="min-w-full border-collapse text-sm">
                            <thead class="bg-zinc-100 dark:bg-zinc-800">
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-2 text-left dark:border-zinc-700"><?php echo e(__('Area Part')); ?></th>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dayColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayKey => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_keys($dayColumns); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $areaParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                        <td class="border border-zinc-200 px-4 py-2 font-medium dark:border-zinc-700">
                                            <div class="flex items-center justify-between gap-2">
                                                <span><?php echo e($part['display_name']); ?></span>
                                                <?php
                                                    $previewDayKey = array_key_first($dayColumns);
                                                    $hasRecordPreview = $previewDayKey !== null ? $this->hasSlotRecord($part['id'], $previewDayKey, 'AM') : false;
                                                    $isVerifiedPreview = $previewDayKey !== null ? $this->isSlotSelected($part['id'], $previewDayKey, 'AM') : false;
                                                ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasRecordPreview): ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVerifiedPreview): ?>
                                                        <button
                                                            type="button"
                                                            wire:click="openProofPreview(<?php echo e($part['id']); ?>, '<?php echo e($previewDayKey); ?>', 'AM')"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-violet-300 bg-violet-50 text-violet-700 hover:bg-violet-100 dark:border-violet-700 dark:bg-violet-900/30 dark:text-violet-300 dark:hover:bg-violet-900/40"
                                                            aria-label="<?php echo e(__('Preview verified proof and comment')); ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                            </svg>
                                                        </button>
                                                    <?php else: ?>
                                                        <button
                                                            type="button"
                                                            disabled
                                                            class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-md border border-violet-300 bg-violet-50 text-violet-600 dark:border-violet-700 dark:bg-violet-900/30 dark:text-violet-300"
                                                            aria-label="<?php echo e(__('Proof image available')); ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                            </svg>
                                                        </button>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_keys($dayColumns); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periodShifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <?php
                                                    $selected = $this->isSlotSelected($part['id'], $dayKey, $shift);
                                                    $locked = $this->isSlotLockedForFuture($dayKey);
                                                    $hasRecord = $this->hasSlotRecord($part['id'], $dayKey, $shift);
                                                ?>
                                                <td class="border border-zinc-200 px-2 py-2 text-center dark:border-zinc-700 <?php echo e(($locked || ! $hasRecord) ? 'opacity-50' : ''); ?> <?php echo e($selected ? 'bg-violet-50/60 dark:bg-violet-900/20' : ''); ?>">
                                                    <input
                                                        type="checkbox"
                                                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('slot-week-{{ $part[\'id\'] }}-{{ $dayKey }}-{{ $shift }}', get_defined_vars()); ?>wire:key="slot-week-<?php echo e($part['id']); ?>-<?php echo e($dayKey); ?>-<?php echo e($shift); ?>"
                                                        wire:click.prevent="requestToggleWithProof(<?php echo e($part['id']); ?>, '<?php echo e($dayKey); ?>', '<?php echo e($shift); ?>')"
                                                        <?php if($locked || ! $hasRecord): echo 'disabled'; endif; ?>
                                                        <?php if($selected): ?> tabindex="-1" aria-disabled="true" <?php endif; ?>
                                                        <?php if($selected): echo 'checked'; endif; ?>
                                                        class="h-4 w-4 rounded border-zinc-300 text-violet-600 accent-violet-600 focus:ring-violet-500 <?php echo e($selected ? 'pointer-events-none cursor-not-allowed' : 'pointer-events-auto cursor-pointer'); ?> disabled:cursor-not-allowed disabled:opacity-100 disabled:accent-violet-600 dark:border-zinc-600 dark:bg-zinc-900"
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

                <?php elseif($selectedLocationId !== null && in_array($periodType, ['daily', 'nightly']) && $showDailyChecklist): ?>
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
<?php echo e(__('Checklist')); ?> <?php echo $__env->renderComponent(); ?>
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
                            </div>
                        </div>

                        <div class="max-h-[65vh] overflow-auto rounded-xl border border-zinc-200 shadow-sm dark:border-zinc-700">
                            <table class="min-w-full border-collapse text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-zinc-200 px-4 py-3 text-left font-semibold dark:border-zinc-700"><?php echo e(__('Area Part')); ?></th>
                                        <th colspan="<?php echo e($periodType === 'nightly' ? 1 : 2); ?>" class="border border-zinc-200 px-3 py-3 text-center dark:border-zinc-700">
                                            <div class="font-semibold"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('l')); ?></div>
                                            <div class="text-xs text-zinc-500"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('M d, Y')); ?></div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periodType === 'daily'): ?>
                                                <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-orange-600 dark:border-zinc-700 dark:text-orange-400">AM</th>
                                                <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-sky-600 dark:border-zinc-700 dark:text-sky-400">PM</th>
                                            <?php else: ?>
                                                <th class="border border-zinc-200 px-2 py-1 text-center dark:border-zinc-700"><?php echo e(__('Check')); ?></th>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $areaParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                            <td class="border border-zinc-200 px-4 py-3 font-medium dark:border-zinc-700">
                                                <?php
                                                    $hasAmRecord = $this->hasSlotRecord($part['id'], 'selected', 'AM');
                                                    $hasPmRecord = $this->hasSlotRecord($part['id'], 'selected', 'PM');
                                                    $hasAmVerified = $this->isSlotSelected($part['id'], 'selected', 'AM');
                                                    $hasPmVerified = $this->isSlotSelected($part['id'], 'selected', 'PM');
                                                ?>
                                                <div class="flex items-center justify-between gap-2">
                                                    <span><?php echo e($part['display_name']); ?></span>
                                                    <div class="flex items-center gap-1">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasAmRecord): ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasAmVerified): ?>
                                                                <button
                                                                    type="button"
                                                                    wire:click="openProofPreview(<?php echo e($part['id']); ?>, 'selected', 'AM')"
                                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-orange-300 bg-orange-50 text-orange-700 hover:bg-orange-100 dark:border-orange-700 dark:bg-orange-900/30 dark:text-orange-300 dark:hover:bg-orange-900/40"
                                                                    aria-label="<?php echo e(__('Preview AM verified proof and comment')); ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                                    </svg>
                                                                </button>
                                                            <?php else: ?>
                                                                <button
                                                                    type="button"
                                                                    disabled
                                                                    class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-md border border-orange-300 bg-orange-50 text-orange-600 dark:border-orange-700 dark:bg-orange-900/30 dark:text-orange-300"
                                                                    aria-label="<?php echo e(__('AM proof image available')); ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                        <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                        <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                                    </svg>
                                                                </button>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasPmRecord): ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasPmVerified): ?>
                                                                <button
                                                                    type="button"
                                                                    wire:click="openProofPreview(<?php echo e($part['id']); ?>, 'selected', 'PM')"
                                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-sky-300 bg-sky-50 text-sky-700 hover:bg-sky-100 dark:border-sky-700 dark:bg-sky-900/30 dark:text-sky-300 dark:hover:bg-sky-900/40"
                                                                    aria-label="<?php echo e(__('Preview PM verified proof and comment')); ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="#0284c7" stroke-width="2">
                                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                                    </svg>
                                                                </button>
                                                            <?php else: ?>
                                                                <button
                                                                    type="button"
                                                                    disabled
                                                                    class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-md border border-sky-300 bg-sky-50 text-sky-600 dark:border-sky-700 dark:bg-sky-900/30 dark:text-sky-300"
                                                                    aria-label="<?php echo e(__('PM proof image available')); ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                        <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                        <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                                    </svg>
                                                                </button>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periodType === 'nightly' && $shift === 'AM'): ?> <?php continue; ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php
                                                    $selected = $this->isSlotSelected($part['id'], 'selected', $shift);
                                                    $locked = $this->isSlotLockedForFuture('selected');
                                                    $hasRecord = $this->hasSlotRecord($part['id'], 'selected', $shift);
                                                ?>
                                                <td
                                                    <?php if(! ($locked || ! $hasRecord || $selected)): ?>
                                                        wire:click="requestToggleWithProof(<?php echo e($part['id']); ?>, 'selected', '<?php echo e($shift); ?>')"
                                                    <?php endif; ?>
                                                    class="border border-zinc-200 px-2 py-3 text-center dark:border-zinc-700 <?php echo e(($locked || ! $hasRecord) ? 'opacity-50' : 'cursor-pointer'); ?> <?php echo e($selected ? 'bg-violet-50/60 dark:bg-violet-900/20' : ''); ?>"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('slot-day-{{ $part[\'id\'] }}-selected-{{ $shift }}', get_defined_vars()); ?>wire:key="slot-day-<?php echo e($part['id']); ?>-selected-<?php echo e($shift); ?>"
                                                        <?php if($locked || ! $hasRecord): echo 'disabled'; endif; ?>
                                                        <?php if($selected): ?> tabindex="-1" aria-disabled="true" <?php endif; ?>
                                                        <?php if($selected): echo 'checked'; endif; ?>
                                                        class="pointer-events-none h-4 w-4 rounded border-zinc-300 text-violet-600 accent-violet-600 focus:ring-violet-500 disabled:cursor-not-allowed disabled:opacity-100 disabled:accent-violet-600 dark:border-zinc-600 dark:bg-zinc-900"
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
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-zinc-900">
                            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                    <?php echo e($proofPreviewTitle ?? __('Proof Preview')); ?>

                                </h3>
                                <button
                                    type="button"
                                    wire:click="closeProofPreview"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                    aria-label="<?php echo e(__('Close preview')); ?>">
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
                                <?php elseif($proofPreviewUrl): ?>
                                    <div class="mx-auto w-full max-w-sm">
                                        <div class="aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                            <img src="<?php echo e($proofPreviewUrl); ?>" alt="<?php echo e(__('Proof image')); ?>" class="h-full w-full object-contain">
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($proofPreviewComment)): ?>
                                        <div class="mx-auto mt-3 w-full max-w-sm rounded-md border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/60 dark:text-zinc-200">
                                            <div class="mb-1 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400"><?php echo e(__('Verifier Comment')); ?></div>
                                            <div><?php echo e($proofPreviewComment); ?></div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    <div class="rounded-md border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                                        <?php echo e(__('No proof image available for this item.')); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showVerifyModal): ?>
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-zinc-900">
                            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100"><?php echo e(__('Verify Checklist Record')); ?></h3>
                                <button
                                    type="button"
                                    wire:click="closeVerifyModal"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                    aria-label="<?php echo e(__('Close verify modal')); ?>">
                                    &times;
                                </button>
                            </div>
                            <div class="space-y-4 p-4">
                                <?php
                                    $verifySlotKey = null;
                                    $verifySkipReason = null;
                                    if ($verifyRecordId) {
                                        foreach ($slotProofs as $sk => $sp) {
                                            if (str_starts_with($sp, 'skip:') && isset($slotRecordIds[$sk]) && $slotRecordIds[$sk] == $verifyRecordId) {
                                                $r = substr($sp, 5);
                                                $verifySkipReason = match ($r) {
                                                    'patient_present' => 'Patient Present',
                                                    'gloves'          => 'Gloves On / Sanitary Concern',
                                                    default           => ucwords(str_replace('_', ' ', $r)),
                                                };
                                                break;
                                            }
                                        }
                                    }
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($verifySkipReason): ?>
                                    <div class="flex flex-col items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-8 text-center dark:border-amber-700/40 dark:bg-amber-900/20">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-800/40">
                                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Photo Skipped</p>
                                            <p class="mt-1 text-xs text-amber-700 dark:text-amber-400"><?php echo e($verifySkipReason); ?></p>
                                        </div>
                                    </div>
                                <?php elseif($verifyPreviewUrl): ?>
                                    <div class="mx-auto w-full max-w-sm">
                                        <div class="aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                            <img src="<?php echo e($verifyPreviewUrl); ?>" alt="<?php echo e(__('Proof image')); ?>" class="h-full w-full object-contain">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="rounded-md border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                                        <?php echo e(__('No proof image available for this record.')); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <div class="space-y-2">
                                    <label for="verifyComment" class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                        <?php echo e(__('Verifier Comment')); ?>

                                    </label>
                                    <textarea
                                        id="verifyComment"
                                        wire:model.defer="verifyComment"
                                        rows="3"
                                        placeholder="<?php echo e(__('Add verification comment...')); ?>"
                                        class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="closeVerifyModal"
                                        class="rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                        <?php echo e(__('Cancel')); ?>

                                    </button>
                                    <button
                                        type="button"
                                        wire:click="confirmVerifyChecklist"
                                        class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                        <?php echo e(__('Confirm Verification')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87c487d0a6659ae6b9df49b3a12e7e8a)): ?>
<?php $attributes = $__attributesOriginal87c487d0a6659ae6b9df49b3a12e7e8a; ?>
<?php unset($__attributesOriginal87c487d0a6659ae6b9df49b3a12e7e8a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87c487d0a6659ae6b9df49b3a12e7e8a)): ?>
<?php $component = $__componentOriginal87c487d0a6659ae6b9df49b3a12e7e8a; ?>
<?php unset($__componentOriginal87c487d0a6659ae6b9df49b3a12e7e8a); ?>
<?php endif; ?>
    </section>
</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\storage\framework/views/livewire/views/e0ad8e54.blade.php ENDPATH**/ ?>