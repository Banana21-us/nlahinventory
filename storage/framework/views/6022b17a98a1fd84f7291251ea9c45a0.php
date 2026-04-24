<?php use Illuminate\Support\Facades\Storage; ?>

<div class="max-w-7xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-50 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Department Assets</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight"><?php echo e($userDepartmentName); ?> - Asset Inventory</h1>
                <p class="text-xs text-gray-500 mt-1">Click on any asset to view details and update status</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative max-w-md">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search assets by code, name, brand, or serial number..." 
                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
            >
        </div>
    </div>

    <!-- Assets Grid - 4 Cards per row -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assets->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div 
                    class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 cursor-pointer hover:scale-[1.02]"
                    wire:click="showDetails(<?php echo e($asset->id); ?>)"
                >
                    <!-- Card Image - Smaller -->
                    <div class="h-32 bg-gray-100">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($asset->image && Storage::disk('public')->exists($asset->image)): ?>
                            <img src="<?php echo e(Storage::url($asset->image)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <!-- Card Body - Compact -->
                    <div class="p-3">
                        <p class="font-bold text-gray-900 text-sm truncate"><?php echo e($asset->asset_code); ?></p>
                        <p class="text-xs text-gray-600 truncate"><?php echo e($asset->name); ?></p>
                        
                        <!-- Status Badge -->
                        <?php
                            $statusColor = match($asset->status) {
                                'in_use' => 'bg-blue-100 text-blue-700',
                                'maintenance' => 'bg-amber-100 text-amber-700',
                                'retired' => 'bg-rose-100 text-rose-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        ?>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold <?php echo e($statusColor); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $asset->status))); ?>

                            </span>
                        </div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($assets->links()); ?>

        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12 text-center">
            <div class="flex flex-col items-center">
                <div class="p-4 bg-gray-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">No Assets Found</h3>
                <p class="text-sm text-gray-500 max-w-md">
                    <?php echo e($search ? 'No assets match your search criteria.' : 'No assets are currently assigned to your department.'); ?>

                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
                    <button wire:click="clearFilters" class="mt-4 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                        Clear Search
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Asset Details Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDetailsModal && $selectedAsset): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeDetailsModal()">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl sm:w-full sm:max-w-2xl w-full max-w-2xl">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white/20 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">Asset Details</h3>
                                    <p class="text-xs text-indigo-200"><?php echo e($selectedAsset->asset_code); ?></p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeDetailsModal" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Left Column - Image -->
                            <div class="md:w-1/2 bg-gray-50 rounded-lg p-4 flex items-center justify-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedAsset->image && Storage::disk('public')->exists($selectedAsset->image)): ?>
                                    <img src="<?php echo e(Storage::url($selectedAsset->image)); ?>" class="w-full max-w-sm object-cover rounded-lg shadow-md">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Right Column - Details -->
                            <div class="md:w-1/2 space-y-3">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Asset Name</p>
                                    <p class="text-lg font-semibold text-gray-800"><?php echo e($selectedAsset->name); ?></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Brand</p>
                                        <p class="text-gray-700"><?php echo e($selectedAsset->brand ?: 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Model</p>
                                        <p class="text-gray-700"><?php echo e($selectedAsset->model ?: 'N/A'); ?></p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Category</p>
                                    <p class="text-gray-700"><?php echo e($selectedAsset->category ?: 'N/A'); ?></p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Serial Number</p>
                                    <p class="text-gray-700"><?php echo e($selectedAsset->serial_number ?: 'N/A'); ?></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Purchase Date</p>
                                        <p class="text-gray-700"><?php echo e($selectedAsset->purchase_date ? date('M d, Y', strtotime($selectedAsset->purchase_date)) : 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Purchase Cost</p>
                                        <p class="text-gray-700">₱<?php echo e(number_format($selectedAsset->purchase_cost, 2)); ?></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Status</p>
                                        <?php
                                            $statusColor = match($selectedAsset->status) {
                                                'in_use' => 'bg-blue-100 text-blue-700',
                                                'maintenance' => 'bg-amber-100 text-amber-700',
                                                'retired' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($statusColor); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $selectedAsset->status))); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Condition</p>
                                        <?php
                                            $conditionColor = match($selectedAsset->condition_status) {
                                                'good' => 'bg-green-100 text-green-700',
                                                'fair' => 'bg-yellow-100 text-yellow-700',
                                                'poor' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($conditionColor); ?>">
                                            <?php echo e(ucfirst($selectedAsset->condition_status)); ?>

                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Location</p>
                                    <p class="text-gray-700"><?php echo e($selectedAsset->location->name ?? 'N/A'); ?></p>
                                </div>

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Notes</p>
                                    <p class="text-gray-600 text-sm"><?php echo e($selectedAsset->notes ?: 'No notes available.'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer with Update Button -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button 
                            type="button" 
                            wire:click="openUpdateModal(<?php echo e($selectedAsset->id); ?>)" 
                            class="inline-flex justify-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-amber-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Update Status / Condition
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeDetailsModal" 
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Update Status Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showUpdateModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeUpdateModal()">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl sm:w-full sm:max-w-lg w-full max-w-md">
                    <form wire:submit.prevent="updateAssetStatus">
                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold">Update Asset</h3>
                                        <p class="text-xs text-amber-200">Change status or condition</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="closeUpdateModal" class="text-white/80 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                                <select wire:model="update_status" class="w-full rounded-md border border-gray-300 focus:ring-amber-500 focus:border-amber-500 p-2">
                                    <option value="in_use">In Use</option>
                                    <option value="maintenance">Under Maintenance</option>
                                    <option value="retired">Retired</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Condition</label>
                                <select wire:model="update_condition_status" class="w-full rounded-md border border-gray-300 focus:ring-amber-500 focus:border-amber-500 p-2">
                                    <option value="good">Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Remarks / Notes</label>
                                <textarea wire:model="update_remarks" rows="3" placeholder="Optional: Add notes about why you're changing the status or condition..." class="w-full rounded-md border border-gray-300 focus:ring-amber-500 focus:border-amber-500 p-2"></textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit" class="inline-flex justify-center rounded-lg bg-amber-500 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-amber-700 transition-colors">
                                Update Asset
                            </button>
                            <button type="button" wire:click="closeUpdateModal" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Success/Error Toasts -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-5 right-5 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-5 right-5 z-50 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/Deptassetmanagement/asset.blade.php ENDPATH**/ ?>