<?php use Illuminate\Support\Facades\Storage; ?>

<div class="max-w-7xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-50 rounded-lg">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Repair Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight"><?php echo e($userDepartmentName); ?> - Assets Needing Repair</h1>
                <p class="text-xs text-gray-500 mt-1">View and manage assets that need repair or maintenance</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Needs Repair</p>
                    <p class="text-2xl font-bold text-amber-600"><?php echo e($assets->where('status', 'maintenance')->count()); ?></p>
                </div>
                <div class="p-2 bg-amber-100 rounded-full">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Poor Condition</p>
                    <p class="text-2xl font-bold text-red-600"><?php echo e($assets->where('condition_status', 'poor')->count()); ?></p>
                </div>
                <div class="p-2 bg-red-100 rounded-full">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold">Fair Condition</p>
                    <p class="text-2xl font-bold text-yellow-600"><?php echo e($assets->where('condition_status', 'fair')->count()); ?></p>
                </div>
                <div class="p-2 bg-yellow-100 rounded-full">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
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
                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
            >
        </div>
    </div>

    <!-- Assets Grid -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assets && $assets->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <?php
                    $needRepair = $asset->status === 'maintenance';
                    $poorCondition = $asset->condition_status === 'poor';
                    $fairCondition = $asset->condition_status === 'fair';
                    $priorityClass = $needRepair ? 'border-l-4 border-l-red-500' : ($poorCondition ? 'border-l-4 border-l-amber-500' : 'border-l-4 border-l-yellow-500');
                ?>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 cursor-pointer <?php echo e($priorityClass); ?>" wire:click="showDetails(<?php echo e($asset->id); ?>)">
                    <!-- Card Image -->
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
                        <!-- Priority Badge -->
                        <div class="absolute top-2 right-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($needRepair): ?>
                                <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">Needs Repair</span>
                            <?php elseif($poorCondition): ?>
                                <span class="bg-amber-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">Poor Condition</span>
                            <?php else: ?>
                                <span class="bg-yellow-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">Fair Condition</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-3">
                        <p class="font-bold text-gray-900 text-sm truncate"><?php echo e($asset->asset_code); ?></p>
                        <p class="font-semibold text-gray-800 text-xs truncate"><?php echo e($asset->name); ?></p>
                        
                        <div class="mt-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Condition:</span>
                                <?php
                                    $conditionColor = match($asset->condition_status) {
                                        'good' => 'text-green-600',
                                        'fair' => 'text-yellow-600',
                                        'poor' => 'text-red-600',
                                        default => 'text-gray-600',
                                    };
                                ?>
                                <span class="font-semibold <?php echo e($conditionColor); ?>"><?php echo e(ucfirst($asset->condition_status)); ?></span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-gray-500">Status:</span>
                                <span class="font-semibold"><?php echo e(ucfirst(str_replace('_', ' ', $asset->status))); ?></span>
                            </div>
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
                <div class="p-4 bg-green-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">No Assets Need Repair</h3>
                <p class="text-sm text-gray-500 max-w-md">
                    <?php echo e($search ? 'No assets match your search criteria.' : 'All assets are in good condition. No repairs needed at this time.'); ?>

                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
                    <button wire:click="clearFilters" class="mt-4 text-amber-600 hover:text-amber-700 text-sm font-medium">
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
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white/20 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Asset Details</h3>
                                    <p class="text-xs text-amber-200"><?php echo e($selectedAsset->asset_code); ?></p>
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

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Asset Code</p>
                                    <p class="text-gray-700"><?php echo e($selectedAsset->asset_code); ?></p>
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
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Serial Number</p>
                                    <p class="text-gray-700"><?php echo e($selectedAsset->serial_number ?: 'N/A'); ?></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Status</p>
                                        <?php
                                            $statusColor = match($selectedAsset->status) {
                                                'maintenance' => 'bg-red-100 text-red-700',
                                                'in_use' => 'bg-blue-100 text-blue-700',
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

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button 
                            type="button" 
                            wire:click="openRepairModal(<?php echo e($selectedAsset->id); ?>)" 
                            class="inline-flex justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-amber-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            </svg>
                            Complete Repair
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

    <!-- Complete Repair Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showRepairModal): ?>
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeRepairModal()">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl sm:w-full sm:max-w-lg w-full max-w-md">
                    <form wire:submit.prevent="completeRepair">
                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white/20 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Complete Repair</h3>
                                        <p class="text-xs text-green-200">Mark this asset as repaired</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="closeRepairModal" class="text-white/80 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Issue / Problem *</label>
                                <textarea wire:model="repair_issue" rows="3" placeholder="Describe what was repaired or fixed..." class="w-full rounded-md border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2" required></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['repair_issue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Repair Cost</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" step="0.01" wire:model="repair_cost" placeholder="0.00" class="w-full rounded-md border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2 pl-8">
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['repair_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Additional Notes</label>
                                <textarea wire:model="repair_notes" rows="2" placeholder="Any additional information about the repair..." class="w-full rounded-md border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2"></textarea>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-3 text-xs text-blue-700">
                                <p>After completing the repair:</p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>Asset status will change from "Maintenance" to "In Use"</li>
                                    <li>Condition will be updated to "Good"</li>
                                    <li>Repair record will be saved in the transaction history</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit" class="inline-flex justify-center rounded-lg bg-green-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-green-700 transition-colors">
                                Complete Repair
                            </button>
                            <button type="button" wire:click="closeRepairModal" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Success Message Toast -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-5 right-5 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/RepairAssets/repair.blade.php ENDPATH**/ ?>