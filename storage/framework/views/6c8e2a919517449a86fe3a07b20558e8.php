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

    .brand-btn-teal {
        background-color: #027c8b;
        color: #ffffff;
        transition: background-color 0.15s ease;
    }
    .brand-btn-teal:hover { background-color: #016070; }

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

    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Administration</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Leave Approval</h1>
            </div>
        </div>

        
        <div class="flex gap-3">
            <div class="flex items-center px-4 py-2 brand-bg-accent-light border border-yellow-200 rounded-lg gap-2">
                <span class="flex h-2 w-2 rounded-full brand-bg-accent animate-pulse"></span>
                <span class="text-xs font-bold brand-text-accent uppercase tracking-wide"><?php echo e($this->pendingCount); ?> Pending Review</span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->cancellationCount > 0): ?>
            <div class="flex items-center px-4 py-2 bg-amber-50 border border-amber-300 rounded-lg gap-2">
                <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wide"><?php echo e($this->cancellationCount); ?> Cancel Request<?php echo e($this->cancellationCount > 1 ? 's' : ''); ?></span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="flex items-center px-4 py-2 bg-green-50 border border-green-100 rounded-lg">
                <span class="text-xs font-bold text-green-700 uppercase tracking-wide"><?php echo e($this->approvedTodayCount); ?> Approved Today</span>
            </div>
        </div>
    </div>

    
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Application Queue</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    <?php echo e(count($this->leaves)); ?> <?php echo e(Str::plural('record', count($this->leaves))); ?>

                </span>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                
                <select wire:model.live="statusFilter"
                    class="search-focus text-sm border border-gray-200 rounded-lg p-2 bg-white text-gray-700 transition-all">
                    <option value="all">All Status</option>
                    <option value="pending">Pending Only</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancellation_requested">Cancel Requests</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                
                <div class="relative flex-1 md:flex-none">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="   Search staff…"
                        class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg transition-all w-56"
                    />
                </div>
            </div>
        </div>

        
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Leave Details</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Timeline</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dept Head</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">HR Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr class="brand-row-hover transition-colors">

                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 brand-bg-primary">
                                        <?php echo e(strtoupper(substr($leave->user?->username ?? '?', 0, 2))); ?>

                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900"><?php echo e($leave->user?->username ?? '(no user)'); ?></div>
                                        <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">
                                            <?php echo e($leave->user?->employmentDetail?->department?->name ?? 'General'); ?>

                                        </div>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-800"><?php echo e($leave->leave_type); ?></div>
                                <div class="text-xs text-gray-400 truncate max-w-[180px] italic">"<?php echo e($leave->reason); ?>"</div>
                            </td>

                            
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($leave->start_date->format('M d')); ?> – <?php echo e($leave->end_date->format('M d, Y')); ?>

                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                                    <?php echo e($leave->total_days); ?> Days · <?php echo e($leave->day_part); ?>

                                </div>
                            </td>

                            
                            <td class="px-6 py-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leave->dept_head_status === 'approved'): ?>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Cleared
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold brand-text-accent brand-bg-accent-light border border-yellow-200 px-2.5 py-0.5 rounded-full">
                                        Awaiting DH
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-6 py-4">
                                <?php
                                    $hrStyles = [
                                        'approved'               => 'background-color:#dcfce7;color:#166534;border:1px solid #86efac;',
                                        'rejected'               => 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                                        'pending'                => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                                        'cancelled'              => 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;',
                                        'cancellation_requested' => 'background-color:#fef3c7;color:#92400e;border:1px solid #f59e0b;',
                                    ];
                                    $hrLabel = [
                                        'cancellation_requested' => 'Cancel Requested',
                                    ];
                                ?>
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="<?php echo e($hrStyles[$leave->hr_status] ?? $hrStyles['pending']); ?>">
                                    <?php echo e($hrLabel[$leave->hr_status] ?? ucfirst($leave->hr_status)); ?>

                                </span>
                            </td>

                            
                            <td class="px-6 py-4 text-right">
                                <button wire:click="viewDetails(<?php echo e($leave->id); ?>)"
                                    class="brand-edit-btn rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm transition-colors"
                                    style="background-color:#e6f4f5;color:#027c8b;"
                                    onmouseover="this.style.backgroundColor='#cde9ec'"
                                    onmouseout="this.style.backgroundColor='#e6f4f5'">
                                    Review
                                </button>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">
                                        <?php echo e($search ? 'No applications match your search.' : 'No leave applications found.'); ?>

                                    </p>
                                    <p class="text-xs mt-1">
                                        <?php echo e($search ? 'Try a different keyword.' : 'All caught up — nothing pending review.'); ?>

                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-data="{ show: <?php if ((object) ('isReviewing') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isReviewing'->value()); ?>')<?php echo e('isReviewing'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isReviewing'); ?>')<?php endif; ?> }"
         x-show="show"
         class="fixed inset-0 z-50 overflow-hidden"
         style="display: none;">

        
        <div class="absolute inset-0 bg-gray-500/75 transition-opacity"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             @click="show = false; $wire.closeModal()"></div>

        
        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div class="w-screen max-w-md transform transition ease-in-out duration-500"
                 x-show="show"
                 x-transition:enter="transform transition ease-in-out duration-500"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">

                <div class="h-full flex flex-col bg-white shadow-2xl overflow-hidden"
                     style="border-top-left-radius: 1rem; border-bottom-left-radius: 1rem;">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->selectedLeave): ?>

                        
                        <div class="px-6 py-6 brand-bg-primary text-white relative">
                            <button @click="show = false; $wire.closeModal()"
                                    class="absolute top-5 right-5 text-blue-200 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <p class="text-[10px] font-semibold tracking-widest uppercase opacity-70 mb-1">Administration</p>
                            <h2 class="text-lg font-bold leading-tight">Review Leave Request</h2>
                            <p class="text-xs opacity-60 mt-0.5">Application #<?php echo e($this->selectedLeave->id); ?></p>
                        </div>

                        
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">

                            
                            <div class="flex items-center gap-4 p-4 brand-bg-primary-light rounded-lg border border-blue-100">
                                <div class="w-11 h-11 rounded-full brand-bg-primary flex items-center justify-center text-white font-bold text-sm shrink-0">
                                    <?php echo e(strtoupper(substr($this->selectedLeave->user->username, 0, 1))); ?>

                                </div>
                                <div>
                                    <p class="text-base font-bold text-gray-900 leading-tight"><?php echo e($this->selectedLeave->user->username); ?></p>
                                    <p class="text-[10px] font-semibold brand-text-primary uppercase tracking-wide mt-0.5">
                                        <?php echo e($this->selectedLeave->user->employmentDetail?->position ?? 'Staff Member'); ?>

                                    </p>
                                </div>
                            </div>

                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Type of Leave</label>
                                    <p class="text-sm font-bold text-gray-800"><?php echo e($this->selectedLeave->leave_type); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Total Days</label>
                                    <p class="text-sm font-bold text-gray-800"><?php echo e($this->selectedLeave->total_days); ?> Day(s)</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Effective Period</label>
                                    <p class="text-sm font-bold text-gray-800">
                                        <?php echo e($this->selectedLeave->start_date->format('F d, Y')); ?> — <?php echo e($this->selectedLeave->end_date->format('F d, Y')); ?>

                                    </p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Day Part</label>
                                    <p class="text-sm font-bold text-gray-800"><?php echo e($this->selectedLeave->day_part); ?></p>
                                </div>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Statement of Reason</label>
                                <div class="p-4 brand-bg-accent-light border border-yellow-200 rounded-lg text-sm text-gray-700 italic leading-relaxed">
                                    "<?php echo e($this->selectedLeave->reason); ?>"
                                </div>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Department Head Status</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->selectedLeave->dept_head_status === 'approved'): ?>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Cleared by Department Head
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold brand-text-accent brand-bg-accent-light border border-yellow-200 px-3 py-1.5 rounded-full">
                                        Awaiting Department Head
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <div class="pt-4 border-t border-gray-100">
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">HR Review Remarks</label>
                                <textarea wire:model="hrRemarks" rows="4"
                                          placeholder="Enter internal notes or feedback here..."
                                          class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white resize-none"></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hrRemarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->selectedLeave->hr_status === 'cancellation_requested'): ?>
                                
                                <div class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 font-medium">
                                    This employee has requested to cancel an approved leave. Approving will restore their credits.
                                </div>
                                <div class="flex flex-row-reverse gap-3">
                                    <button wire:click="approveCancellation" wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 text-sm font-bold shadow-sm active:scale-95 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="approveCancellation">Approve Cancellation</span>
                                        <span wire:loading wire:target="approveCancellation">Saving…</span>
                                    </button>
                                    <button wire:click="rejectCancellation" wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors active:scale-95">
                                        Deny Cancellation
                                    </button>
                                </div>
                            <?php else: ?>
                                
                                <div class="flex flex-row-reverse gap-3">
                                    <button wire:click="approve" wire:loading.attr="disabled"
                                        class="brand-btn-teal inline-flex justify-center items-center gap-2 rounded-lg px-5 py-2 text-sm font-bold shadow-sm active:scale-95 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="approve">Approve</span>
                                        <span wire:loading wire:target="approve" class="flex items-center gap-2">
                                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                            </svg>
                                            Saving…
                                        </span>
                                    </button>
                                    <button wire:click="reject" wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-200 hover:bg-red-50 transition-colors active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
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
                    <p class="text-sm font-semibold text-gray-900">Success!</p>
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

</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/HR/hr-leave-management.blade.php ENDPATH**/ ?>