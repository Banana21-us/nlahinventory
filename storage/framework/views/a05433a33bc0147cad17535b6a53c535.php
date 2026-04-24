<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }

    .brand-bg-accent         { background-color: #f0b626; }
    .brand-bg-accent-light   { background-color: #fef8e7; }
    .brand-text-accent       { color: #f0b626; }

    .brand-bg-teal           { background-color: #027c8b; }
    .brand-bg-teal-light     { background-color: #e6f4f5; }
    .brand-text-teal         { color: #027c8b; }

    .brand-btn-primary {
        background-color: #015581;
        color: #ffffff;
        transition: background-color 0.15s ease;
    }
    .brand-btn-primary:hover { background-color: #01406a; }

    .brand-focus:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(1, 85, 129, 0.2);
        border-color: #015581;
    }
    .search-focus:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 124, 139, 0.2);
        border-color: #027c8b;
    }
    .brand-row-hover:hover { background-color: #f0f7fc; }

    @keyframes shrink { from { width: 100% } to { width: 0% } }
</style>

    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Employee Self-Service</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Leave Application</h1>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isProbationary): ?>
        <div class="mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4">
            <svg class="mt-0.5 w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <div>
                <p class="text-sm font-bold text-amber-800">Probationary Period — VL, SL &amp; BL Not Yet Available</p>
                <p class="text-xs text-amber-700 mt-0.5">
                    Vacation Leave, Sick Leave, and Birthday Leave credits are granted upon regularization.
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expectedRegDate): ?>
                        Expected regularization date:
                        <strong><?php echo e($expectedRegDate->format('M d, Y')); ?></strong>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daysLeft > 0): ?>
                            (<?php echo e($daysLeft); ?> day<?php echo e($daysLeft === 1 ? '' : 's'); ?> remaining)
                        <?php elseif($daysLeft <= 0): ?>
                            — regularization is overdue; please contact HR.
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden"
         x-data="{ open: <?php if ((object) ('showForm') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showForm'->value()); ?>')<?php echo e('showForm'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showForm'); ?>')<?php endif; ?> }">

        
        <button
            @click="open = !open"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none"
        >
            <div class="flex items-center">
                <div class="p-2 rounded-lg mr-4 brand-bg-primary-light">
                    <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Leave Entry</h2>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="open ? 'Minimize' : 'File a Leave'"></span>
        </button>

        
        <div
            x-show="open"
            x-collapse
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-6 border-t border-gray-100 bg-gray-50/30"
        >
            <form wire:submit.prevent="save" class="space-y-6">

                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Nature of Leave *</label>
                        <?php if (isset($component)) { $__componentOriginalc933793160c9c2655e76e4334d02687c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc933793160c9c2655e76e4334d02687c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.custom-select','data' => ['wireProperty' => 'leave_type','current' => $leave_type,'options' => $leaveTypeOptions,'placeholder' => 'Select Type…','error' => $errors->first('leave_type')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('custom-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-property' => 'leave_type','current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($leave_type),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($leaveTypeOptions),'placeholder' => 'Select Type…','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('leave_type'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc933793160c9c2655e76e4334d02687c)): ?>
<?php $attributes = $__attributesOriginalc933793160c9c2655e76e4334d02687c; ?>
<?php unset($__attributesOriginalc933793160c9c2655e76e4334d02687c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc933793160c9c2655e76e4334d02687c)): ?>
<?php $component = $__componentOriginalc933793160c9c2655e76e4334d02687c; ?>
<?php unset($__componentOriginalc933793160c9c2655e76e4334d02687c); ?>
<?php endif; ?>
                    </div>

                    <div class="md:col-span-2 brand-bg-primary-light rounded-md border border-blue-100 px-4 py-3 flex items-center justify-between"
                         <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('credits-panel-{{ $leave_type }}', get_defined_vars()); ?>wire:key="credits-panel-<?php echo e($leave_type); ?>">
                        <div>
                            <p class="text-[10px] font-bold brand-text-primary uppercase tracking-wide">
                                <?php echo e($creditLabel); ?>

                            </p>
                            <p class="text-xl font-bold text-gray-800 mt-0.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCredits): ?>
                                    <?php echo e($availableCredits); ?>

                                    <span class="text-sm font-semibold text-gray-500">Days</span>
                                <?php else: ?>
                                    <span class="text-sm font-semibold text-gray-400 italic">Unlimited</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                        <div class="p-2 rounded-lg brand-bg-primary">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['total_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 font-medium">
                        <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start Date *</label>
                        <input type="date" wire:model.live="start_date"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End Date *</label>
                        <input type="date" wire:model.live="end_date"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Duration</label>
                        <div class="flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalc933793160c9c2655e76e4334d02687c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc933793160c9c2655e76e4334d02687c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.custom-select','data' => ['wireProperty' => 'day_part','current' => $day_part,'options' => [
                                    ['value' => 'Full', 'label' => 'Full Day'],
                                    ['value' => 'AM',   'label' => 'AM Half'],
                                    ['value' => 'PM',   'label' => 'PM Half'],
                                ],'placeholder' => 'Full Day']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('custom-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire-property' => 'day_part','current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($day_part),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                                    ['value' => 'Full', 'label' => 'Full Day'],
                                    ['value' => 'AM',   'label' => 'AM Half'],
                                    ['value' => 'PM',   'label' => 'PM Half'],
                                ]),'placeholder' => 'Full Day']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc933793160c9c2655e76e4334d02687c)): ?>
<?php $attributes = $__attributesOriginalc933793160c9c2655e76e4334d02687c; ?>
<?php unset($__attributesOriginalc933793160c9c2655e76e4334d02687c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc933793160c9c2655e76e4334d02687c)): ?>
<?php $component = $__componentOriginalc933793160c9c2655e76e4334d02687c; ?>
<?php unset($__componentOriginalc933793160c9c2655e76e4334d02687c); ?>
<?php endif; ?>
                            <div class="px-3 py-2 brand-bg-teal-light rounded-md font-bold brand-text-teal text-sm shrink-0 border border-teal-100 whitespace-nowrap">
                                <?php echo e($total_days); ?>d
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason / Justification *</label>
                        <textarea wire:model="reason" rows="3"
                                  placeholder="Briefly explain the purpose of your leave..."
                                  class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 resize-none"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Designated Reliever</label>
                        <input type="text" wire:model="reliever"
                            placeholder="e.g. Juan dela Cruz"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        <p class="text-[10px] text-gray-400 mt-1.5 italic font-medium leading-tight">
                            Person who will cover your duties during your absence.
                        </p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['reliever'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="pt-2 border-t border-gray-100">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">
                        Attachment <span class="text-gray-400 normal-case font-normal">(Optional — Medical Certificate, etc.)</span>
                    </label>
                    <label class="flex items-center justify-center w-full h-20 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-teal-light">
                                <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attachment): ?>
                                    <p class="text-xs font-bold text-teal-600 uppercase tracking-wide">File selected</p>
                                    <p class="text-[10px] text-gray-400 font-medium"><?php echo e($attachment->getClientOriginalName()); ?></p>
                                <?php else: ?>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Click to upload file</p>
                                    <p class="text-[10px] text-gray-400 font-medium">PDF, JPG or PNG — max 5MB</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <input type="file" wire:model="attachment" class="hidden"/>
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-2">
                    <button type="button" @click="open = false"
                        class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="brand-btn-primary text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95 flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">Submit Leave</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Submitting…
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    
    <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">My Leave Applications</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    <?php echo e($leaves->count()); ?> <?php echo e(Str::plural('record', $leaves->count())); ?>

                </span>
            </div>

            <div class="relative">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search applications…"
                    class="search-focus pl-4 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg transition-all w-56"
                />
            </div>
        </div>

        
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Filed On</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dept Head</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">HR Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Feedback</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

                    <?php
                        $deptBadge = [
                            'approved' => 'background-color:#dcfce7;color:#166534;border:1px solid #86efac;',
                            'rejected' => 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                            'pending'  => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                        ];
                        $hrBadge = [
                            'approved'                => 'background-color:#dcfce7;color:#166534;border:1px solid #86efac;',
                            'rejected'                => 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                            'pending'                 => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                            'cancelled'               => 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;',
                            'cancellation_requested'  => 'background-color:#fef3c7;color:#92400e;border:1px solid #f59e0b;',
                        ];
                        $typeBadge = [
                            'Vacation Leave'      => 'background-color:#e6f4f5;color:#027c8b;border:1px solid #a5d8dd;',
                            'Sick Leave'          => 'background-color:#ede9fe;color:#6b21a8;border:1px solid #c4b5fd;',
                            'Pay-Off'             => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                            'Compassionate Leave' => 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                            'Leave Without Pay'   => 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;',
                            'Birthday Leave'      => 'background-color:#fce7f3;color:#9d174d;border:1px solid #f9a8d4;',
                            'Single Parent Leave' => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                            'Maternity Leave'     => 'background-color:#fce7f3;color:#9d174d;border:1px solid #f9a8d4;',
                            'Paternity Leave'     => 'background-color:#e6f4f5;color:#027c8b;border:1px solid #a5d8dd;',
                        ];
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $leaveLabel = $leaveTypeMap[$leave->leave_type] ?? $leave->leave_type;
                        ?>
                        <tr class="brand-row-hover transition-colors">

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="<?php echo e($typeBadge[$leave->leave_type] ?? $typeBadge[$leaveLabel] ?? 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;'); ?>">
                                    <?php echo e($leaveLabel); ?>

                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-800">
                                    <?php echo e($leave->start_date->format('M d')); ?> – <?php echo e($leave->end_date->format('M d, Y')); ?>

                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mt-0.5">
                                    <?php echo e($leave->day_part); ?> Day
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full brand-bg-teal-light brand-text-teal" style="border:1px solid #a5d8dd;">
                                    <?php echo e($leave->total_days); ?>d
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-400">
                                <?php echo e($leave->date_requested?->format('M d, Y') ?? '—'); ?>

                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="<?php echo e($deptBadge[$leave->dept_head_status] ?? $deptBadge['pending']); ?>">
                                    <?php echo e(ucfirst($leave->dept_head_status)); ?>

                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="<?php echo e($hrBadge[$leave->hr_status] ?? $hrBadge['pending']); ?>">
                                    <?php echo e($leave->hr_status === 'cancellation_requested' ? 'Cancel Requested' : ucfirst($leave->hr_status)); ?>

                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leave->rejection_reason): ?>
                                    <span class="text-xs text-red-600 font-medium italic"><?php echo e(Str::limit($leave->rejection_reason, 45)); ?></span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-300">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-6 py-4 text-right" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('action-{{ $leave->id }}', get_defined_vars()); ?>wire:key="action-<?php echo e($leave->id); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leave->dept_head_status === 'pending'): ?>
                                    <div x-data="{ confirm: false }" class="flex items-center justify-end gap-1">
                                        <button x-show="!confirm" @click.prevent="confirm = true"
                                                class="px-2.5 py-1 rounded text-xs font-bold text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 transition-colors">
                                            Delete
                                        </button>
                                        <template x-if="confirm">
                                            <span class="flex items-center gap-1">
                                                <span class="text-xs text-red-500 font-medium">Sure?</span>
                                                <button wire:click="deletePending(<?php echo e($leave->id); ?>)"
                                                        class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-2 py-0.5 rounded transition-colors">Yes</button>
                                                <button @click="confirm = false"
                                                        class="text-xs text-gray-400 hover:text-gray-600 px-1">No</button>
                                            </span>
                                        </template>
                                    </div>
                                <?php elseif($leave->dept_head_status === 'approved' && $leave->hr_status === 'pending'): ?>
                                    <div x-data="{ confirm: false }" class="flex items-center justify-end gap-1">
                                        <button x-show="!confirm" @click.prevent="confirm = true"
                                                class="px-2.5 py-1 rounded text-xs font-bold text-orange-600 bg-orange-50 border border-orange-200 hover:bg-orange-100 transition-colors">
                                            Cancel
                                        </button>
                                        <template x-if="confirm">
                                            <span class="flex items-center gap-1">
                                                <span class="text-xs text-orange-500 font-medium">Sure?</span>
                                                <button wire:click="cancelLeave(<?php echo e($leave->id); ?>)"
                                                        class="text-xs font-bold text-white bg-orange-500 hover:bg-orange-600 px-2 py-0.5 rounded transition-colors">Yes</button>
                                                <button @click="confirm = false"
                                                        class="text-xs text-gray-400 hover:text-gray-600 px-1">No</button>
                                            </span>
                                        </template>
                                    </div>
                                <?php elseif($leave->hr_status === 'approved'): ?>
                                    <div x-data="{ confirm: false }" class="flex items-center justify-end gap-1">
                                        <button x-show="!confirm" @click.prevent="confirm = true"
                                                class="px-2.5 py-1 rounded text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 hover:bg-purple-100 transition-colors whitespace-nowrap">
                                            Cancel Leave
                                        </button>
                                        <template x-if="confirm">
                                            <span class="flex items-center gap-1">
                                                <span class="text-xs text-purple-600 font-medium">Request?</span>
                                                <button wire:click="requestCancellation(<?php echo e($leave->id); ?>)"
                                                        class="text-xs font-bold text-white bg-purple-500 hover:bg-purple-600 px-2 py-0.5 rounded transition-colors">Yes</button>
                                                <button @click="confirm = false"
                                                        class="text-xs text-gray-400 hover:text-gray-600 px-1">No</button>
                                            </span>
                                        </template>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-gray-300">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">
                                        <?php echo e($search ? 'No applications match your search.' : 'You have not filed any leave applications yet.'); ?>

                                    </p>
                                    <p class="text-xs mt-1">
                                        <?php echo e($search ? 'Try a different keyword.' : 'Click "File a Leave" above to get started.'); ?>

                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5"
        >
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full brand-bg-teal-light">
                    <svg class="w-5 h-5 brand-text-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Submitted!</p>
                    <p class="mt-0.5 text-sm text-gray-500"><?php echo e(session('message')); ?></p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </div>
            <div class="h-1" style="background-color:#f0b626; animation: shrink 4s linear forwards;"></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/users/leaveform.blade.php ENDPATH**/ ?>