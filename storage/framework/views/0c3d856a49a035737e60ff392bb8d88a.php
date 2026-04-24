<div class="container-fluid px-4 py-4">

    
    <div class="flex justify-between items-center mb-4">
        <div>
            <div class="text-2xl font-bold text-gray-900">Leave Applications</div>
            <div class="text-sm text-gray-500">Manage and review employee leave requests (Department Head Confirmed Only)</div>
        </div>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
            <div class="font-bold text-gray-900 text-base">Leave List (Confirmed by Department Heads)</div>
            <div class="text-sm text-gray-500">Total: <?php echo e($leaves->total()); ?> application(s)</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Employee</th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('leavetype')">
                            Leave Type
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'leavetype'): ?>
                                <i class="fas fa-chevron-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> text-xs ml-1"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Department</th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('startdate')">
                            Duration
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'startdate'): ?>
                                <i class="fas fa-chevron-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> text-xs ml-1"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="text-center text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Days</th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Reason</th>
                        <th class="text-center text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Confirmed By</th>
                        <th class="text-center text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('status')">
                            HR Status
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'status'): ?>
                                <i class="fas fa-chevron-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> text-xs ml-1"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('leave-{{ $leave->id }}', get_defined_vars()); ?>wire:key="leave-<?php echo e($leave->id); ?>" 
                            wire:click="viewLeave(<?php echo e($leave->id); ?>)" 
                            class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                            <td class="px-5 py-4 align-middle">
                                <div class="font-semibold text-gray-900 text-sm"><?php echo e($leave->user->username ?? 'Unknown'); ?></div>
                                <div class="text-xs text-gray-500 mt-0.5"><?php echo e($leave->user->employee_number ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-5 py-4 align-middle">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?php echo e($leave->leave_type_badge_class); ?>">
                                    <?php echo e($leave->formatted_leave_type); ?>

                                </span>
                            </td>
                            <td class="px-5 py-4 align-middle text-gray-600 text-sm"><?php echo e($leave->department); ?></td>
                            <td class="px-5 py-4 align-middle text-gray-600 text-xs whitespace-nowrap">
                                <?php echo e(Carbon\Carbon::parse($leave->startdate)->format('M d, Y')); ?> –
                                <?php echo e(Carbon\Carbon::parse($leave->enddate)->format('M d, Y')); ?>

                            </td>
                            <td class="px-5 py-4 align-middle text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    <?php echo e($leave->totaldays); ?> <?php echo e(Str::plural('day', $leave->totaldays)); ?>

                                </span>
                            </td>
                            <td class="px-5 py-4 align-middle text-gray-600 text-sm max-w-[200px]">
                                <span title="<?php echo e($leave->reason); ?>"><?php echo e(Str::limit($leave->reason, 40)); ?></span>
                            </td>
                            <td class="px-5 py-4 align-middle text-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leave->departmentHead): ?>
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo e($leave->departmentHead->username); ?>

                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e(Carbon\Carbon::parse($leave->dept_head_confirmed_at)->format('M d, Y')); ?>

                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-gray-500">N/A</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-5 py-4 align-middle text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?php echo e($leave->status_badge_class); ?>">
                                    <?php echo e($leave->formatted_status); ?>

                                </span>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                No leave applications found that are confirmed by department heads
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leaves->hasPages()): ?>
            <div class="px-4 py-3 border-t border-gray-100">
                <?php echo e($leaves->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showViewModal && $selectedLeave): ?>
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto max-h-[85vh] overflow-y-auto">
                    
                    <div class="sticky top-0 bg-white border-b border-gray-100 px-5 py-3 rounded-t-2xl">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Leave Application Details</h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    #<?php echo e($selectedLeave->id); ?> · Submitted <?php echo e(Carbon\Carbon::parse($selectedLeave->created_at)->format('M d, Y h:i A')); ?>

                                </p>
                            </div>
                            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" wire:click="closeModal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    
                    <div class="p-5 space-y-4">
                        
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Employee Information</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500">Employee Name</p>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->user->username ?? 'Unknown'); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Employee Number</p>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->user->employee_number ?? 'N/A'); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Department</p>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->department); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Position</p>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->user->position ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Leave Details</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500">Leave Type</p>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($selectedLeave->leave_type_badge_class); ?> mt-1">
                                        <?php echo e($selectedLeave->formatted_leave_type); ?>

                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">HR Status</p>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($selectedLeave->status_badge_class); ?> mt-1">
                                        <?php echo e($selectedLeave->formatted_status); ?>

                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Start Date</p>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e(Carbon\Carbon::parse($selectedLeave->startdate)->format('M d, Y')); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e(Carbon\Carbon::parse($selectedLeave->startdate)->format('l')); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">End Date</p>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e(Carbon\Carbon::parse($selectedLeave->enddate)->format('M d, Y')); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e(Carbon\Carbon::parse($selectedLeave->enddate)->format('l')); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total Days</p>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->totaldays); ?> <?php echo e(Str::plural('day', $selectedLeave->totaldays)); ?></p>
                                </div>
                            </div>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->dept_head_status === 'confirmed'): ?>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="text-xs font-semibold text-green-700 uppercase tracking-wider">Department Head Confirmation</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs text-green-600">Confirmed By</p>
                                        <p class="text-sm font-medium text-green-900"><?php echo e($selectedLeave->departmentHead->username ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-green-600">Confirmed Date</p>
                                        <p class="text-sm font-medium text-green-900">
                                            <?php echo e($selectedLeave->dept_head_confirmed_at ? Carbon\Carbon::parse($selectedLeave->dept_head_confirmed_at)->format('M d, Y h:i A') : 'N/A'); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Reason for Leave</h4>
                            <div class="bg-white rounded-md p-3 border border-gray-200">
                                <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($selectedLeave->reason); ?></p>
                            </div>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->remarks || $selectedLeave->approved_by): ?>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">HR Approval Details</h4>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->remarks): ?>
                                    <div class="mb-2">
                                        <p class="text-xs text-gray-500">Remarks</p>
                                        <p class="text-sm text-gray-700"><?php echo e($selectedLeave->remarks); ?></p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->approved_by): ?>
                                    <div>
                                        <p class="text-xs text-gray-500">Processed By (HR)</p>
                                        <p class="text-sm font-medium text-gray-900"><?php echo e(optional($selectedLeave->approver)->username ?? 'System'); ?></p>
                                        <p class="text-xs text-gray-400 mt-1">Processed on <?php echo e(Carbon\Carbon::parse($selectedLeave->updated_at)->format('M d, Y h:i A')); ?></p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div class="sticky bottom-0 bg-white border-t border-gray-100 px-5 py-3 flex gap-2 justify-end rounded-b-2xl">
                        <button type="button" class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors"
                                wire:click="closeModal">Close</button>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->status === 'pending'): ?>
                            <button type="button" class="px-3 py-1.5 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors"
                                    wire:click="openActionModal(<?php echo e($selectedLeave->id); ?>, 'rejected')">Reject</button>
                            <button type="button" class="px-3 py-1.5 rounded-lg text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors"
                                    wire:click="openActionModal(<?php echo e($selectedLeave->id); ?>, 'approved')">Approve</button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showActionModal && $selectedLeave): ?>
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click.self="closeModal">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto">
                    <div class="border-b border-gray-100 px-5 py-3">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">
                                <?php echo e($actionType === 'approved' ? 'Approve' : 'Reject'); ?> Leave Request
                            </h3>
                            <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="closeModal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="mb-4">
                            <p class="text-sm text-gray-700">
                                You are about to <strong class="<?php echo e($actionType === 'approved' ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e($actionType === 'approved' ? 'approve' : 'reject'); ?>

                                </strong> the leave request from:
                            </p>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                <div class="font-medium text-gray-900"><?php echo e($selectedLeave->user->username ?? 'Unknown'); ?></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php echo e($selectedLeave->formatted_leave_type); ?> · 
                                    <?php echo e(Carbon\Carbon::parse($selectedLeave->startdate)->format('M d')); ?> - 
                                    <?php echo e(Carbon\Carbon::parse($selectedLeave->enddate)->format('M d, Y')); ?>

                                    (<?php echo e($selectedLeave->totaldays); ?> days)
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->departmentHead): ?>
                                    <div class="text-xs text-green-600 mt-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Confirmed by: <?php echo e($selectedLeave->departmentHead->username); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-xs font-semibold text-gray-700 block mb-1">
                                Remarks <span class="font-normal text-gray-500">(Optional)</span>
                            </label>
                            <textarea class="w-full border border-gray-300 rounded-lg text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                    wire:model="actionRemarks" 
                                    rows="2"
                                    placeholder="Add any remarks or comments..."></textarea>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 px-5 py-3 flex gap-2 justify-end">
                        <button type="button" class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors"
                                wire:click="closeModal">Cancel</button>
                        <button type="button"
                                class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors <?php echo e($actionType === 'approved' ? 'text-green-700 bg-green-50 hover:bg-green-100' : 'text-red-700 bg-red-50 hover:bg-red-100'); ?>"
                                wire:click="processLeaveAction"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove><?php echo e($actionType === 'approved' ? 'Confirm Approval' : 'Confirm Rejection'); ?></span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/HR/leave-applications.blade.php ENDPATH**/ ?>