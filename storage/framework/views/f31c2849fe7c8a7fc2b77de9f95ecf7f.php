<div class="container-fluid px-4 py-4">

    
    <div class="flex justify-between items-center mb-4">
        <div>
            <div class="text-2xl font-bold text-gray-900">Department Head Confirmation</div>
            <div class="text-sm text-gray-500">Review and confirm leave requests from your department</div>
            <div class="text-xs text-blue-600 mt-1">Department: <?php echo e($department); ?></div>
        </div>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
            <div class="font-bold text-gray-900 text-base">Leave Applications</div>
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
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('startdate')">
                            Duration
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'startdate'): ?>
                                <i class="fas fa-chevron-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?> text-xs ml-1"></i>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="text-center text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Days</th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Reason</th>
                        <th class="text-center text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('dept_head_status')">
                            Status
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'dept_head_status'): ?>
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
                            <td class="px-5 py-4 align-middle text-gray-600 text-xs whitespace-nowrap">
                                <?php echo e(\Carbon\Carbon::parse($leave->startdate)->format('M d, Y')); ?> –
                                <?php echo e(\Carbon\Carbon::parse($leave->enddate)->format('M d, Y')); ?>

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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leave->dept_head_status == 'pending'): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                <?php elseif($leave->dept_head_status == 'confirmed'): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                <?php elseif($leave->dept_head_status == 'rejected'): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <?php echo e($leave->dept_head_status ?? 'N/A'); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                No leave applications found for your department
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
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-xl font-bold text-gray-900">Leave Application Details</div>
                            <div class="text-sm text-gray-500 mt-0.5">
                                #<?php echo e($selectedLeave->id); ?> &middot; Submitted <?php echo e(\Carbon\Carbon::parse($selectedLeave->created_at)->format('M d, Y h:i A')); ?>

                            </div>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" wire:click="closeModal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Employee Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Employee Name</div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->user->username ?? 'Unknown'); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Employee Number</div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->user->employee_number ?? 'N/A'); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Department</div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->department); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Role</div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->user->role ?? 'N/A'); ?></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Leave Details</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Leave Type</div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?php echo e($selectedLeave->leave_type_badge_class); ?>">
                                    <?php echo e($selectedLeave->formatted_leave_type); ?>

                                </span>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Dept Head Status</div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->dept_head_status == 'pending'): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                <?php elseif($selectedLeave->dept_head_status == 'confirmed'): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmed</span>
                                <?php elseif($selectedLeave->dept_head_status == 'rejected'): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e($selectedLeave->dept_head_status ?? 'N/A'); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Start Date</div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e(\Carbon\Carbon::parse($selectedLeave->startdate)->format('l, F d, Y')); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">End Date</div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e(\Carbon\Carbon::parse($selectedLeave->enddate)->format('l, F d, Y')); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Total Days</div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($selectedLeave->totaldays); ?> <?php echo e(Str::plural('day', $selectedLeave->totaldays)); ?></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Reason for Leave</h4>
                        <div class="text-sm text-gray-700 leading-relaxed"><?php echo e($selectedLeave->reason); ?></div>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->remarks && $selectedLeave->remarks !== 'NULL'): ?>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Remarks</h4>
                            <div class="text-sm text-gray-700 leading-relaxed"><?php echo e($selectedLeave->remarks); ?></div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4 flex gap-3 justify-end">
                    <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors"
                            wire:click="closeModal">Close</button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedLeave->dept_head_status === 'pending'): ?>
                        <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors"
                                wire:click="openActionModal(<?php echo e($selectedLeave->id); ?>, 'rejected')">Reject</button>
                        <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors"
                                wire:click="openActionModal(<?php echo e($selectedLeave->id); ?>, 'confirmed')">Confirm</button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showActionModal && $selectedLeave): ?>
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-xl font-bold text-gray-900">
                            <?php echo e($actionType === 'confirmed' ? 'Confirm' : 'Reject'); ?> Leave Request
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="closeModal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <p class="text-sm text-gray-700">
                            You are about to <strong class="<?php echo e($actionType === 'confirmed' ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($actionType === 'confirmed' ? 'confirm' : 'reject'); ?>

                            </strong> the leave request from:
                        </p>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <div class="font-medium text-gray-900"><?php echo e($selectedLeave->user->username ?? 'Unknown'); ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo e($selectedLeave->formatted_leave_type); ?> &middot; 
                                <?php echo e(\Carbon\Carbon::parse($selectedLeave->startdate)->format('M d')); ?> - 
                                <?php echo e(\Carbon\Carbon::parse($selectedLeave->enddate)->format('M d, Y')); ?>

                                (<?php echo e($selectedLeave->totaldays); ?> days)
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-semibold text-gray-700 block mb-2">
                            Remarks <span class="font-normal text-gray-500">(Optional)</span>
                        </label>
                        <textarea class="w-full border border-gray-300 rounded-lg text-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                  wire:model="actionRemarks" 
                                  rows="3"
                                  placeholder="Add any remarks or comments..."></textarea>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 px-6 py-4 flex gap-3 justify-end">
                    <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors"
                            wire:click="closeModal">Cancel</button>
                    <button type="button"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($actionType === 'confirmed' ? 'text-green-700 bg-green-50 hover:bg-green-100' : 'text-red-700 bg-red-50 hover:bg-red-100'); ?>"
                            wire:click="processLeaveAction"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove><?php echo e($actionType === 'confirmed' ? 'Confirm' : 'Reject'); ?></span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="fixed bottom-4 right-4 z-50">
            <div class="bg-green-50 border border-green-200 rounded-lg shadow-lg p-4 min-w-[300px]">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-green-800"><?php echo e(session('message')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-50 border border-red-200 rounded-lg shadow-lg p-4 min-w-[300px]">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-800"><?php echo e(session('error')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/Department_Head/confirm-application.blade.php ENDPATH**/ ?>