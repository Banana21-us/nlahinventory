<?php use Illuminate\Support\Facades\Storage; ?>

<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-sky-50 rounded-lg">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7.5 12 3l9 4.5M4.5 10.5V16L12 21l7.5-5v-5.5M12 12l9-4.5M12 12 3 7.5"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Assets Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Assets Inventory</h1>
                <p class="text-xs text-gray-500 mt-1">Register new assets to the system</p>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <button wire:click="openForm" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Asset
        </button>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-sky-600 to-sky-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7 12 3 4 7m16 0v10l-8 4-8-4V7m16 0-8 4m-8-4 8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white"><?php echo e($isEditing ? 'Edit Asset' : 'Register New Asset'); ?></h3>
                        <p class="text-xs text-sky-200">Fill in the asset details below</p>
                    </div>
                </div>
                <button type="button" wire:click="cancelForm" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <form wire:submit.prevent="<?php echo e($isEditing ? 'update' : 'save'); ?>" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Asset Code *</label>
                    <input type="text" wire:model="asset_code" placeholder="ASSET-001" class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asset_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Asset Name *</label>
                    <input type="text" wire:model="name" placeholder="Dell XPS 15" class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Category</label>
                    <input type="text" wire:model="category" placeholder="Electronics, Furniture, etc." class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Brand</label>
                    <input type="text" wire:model="brand" placeholder="Dell, HP, Epson..." class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Model</label>
                    <input type="text" wire:model="model" placeholder="XPS 15, ProBook 450..." class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Serial Number</label>
                    <input type="text" wire:model="serial_number" placeholder="SN-123456" class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['serial_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Purchase Date</label>
                    <input type="date" wire:model="purchase_date" class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Purchase Cost</label>
                    <input type="number" step="0.01" wire:model="purchase_cost" placeholder="0.00" class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                    <select wire:model="status" class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2 bg-white">
                        <option value="active">Active (Available for Assignment)</option>
                        <option value="in_use">In Use (Currently Assigned)</option>
                        <option value="maintenance">Under Maintenance</option>
                        <option value="retired">Retired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Condition Status</label>
                    <select wire:model="condition_status" class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2 bg-white">
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
                <div class="md:col-span-2 xl:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Notes</label>
                    <textarea wire:model="notes" rows="3" placeholder="Additional notes about this asset..." class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm p-2"></textarea>
                </div>
                <div class="md:col-span-2 xl:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Asset Image</label>
                    <div class="mt-1 flex items-center gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existing_image): ?>
                            <div class="relative">
                                <img src="<?php echo e(Storage::url($existing_image)); ?>" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                <button type="button" wire:click="$set('existing_image', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        <?php elseif($image): ?>
                            <div class="relative">
                                <img src="<?php echo e($image->temporaryUrl()); ?>" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                <button type="button" wire:click="$set('image', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="flex-1">
                            <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                            <p class="text-xs text-gray-400 mt-1">Supported formats: JPG, PNG, GIF. Max size: 2MB</p>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-4">
                <button type="button" wire:click="cancelForm" class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">Cancel</button>
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold py-2 px-6 rounded shadow-md transition-all">
                    <?php echo e($isEditing ? 'Update Asset' : 'Register Asset'); ?>

                </button>
            </div>
        </form>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="mb-6">
        <div class="relative max-w-md">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assets..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent">
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">Asset Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">Brand/Model</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">Condition</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">Assignment</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $isAssigned = $asset->department_id && $asset->location_id;
                        ?>
                        <tr class="hover:bg-sky-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($asset->image && Storage::disk('public')->exists($asset->image)): ?>
                                    <img src="<?php echo e(Storage::url($asset->image)); ?>" class="w-12 h-12 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900"><?php echo e($asset->asset_code); ?></div>
                                <div class="text-xs text-gray-400">Added <?php echo e($asset->created_at?->format('M d, Y')); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-800"><?php echo e($asset->name); ?></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($asset->serial_number): ?>
                                    <div class="text-xs text-gray-400">SN: <?php echo e($asset->serial_number); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($asset->brand ?: 'N/A'); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($asset->model): ?> (<?php echo e($asset->model); ?>) <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></td>
                            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><?php echo e(ucfirst(str_replace('_', ' ', $asset->status))); ?></span></td>
                            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700"><?php echo e(ucfirst($asset->condition_status)); ?></span></td>
                            <td class="px-6 py-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAssigned): ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Assigned</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Unassigned</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <button wire:click="edit(<?php echo e($asset->id); ?>)" class="rounded-md bg-sky-50 px-2.5 py-1.5 text-sm font-semibold text-sky-700 shadow-sm hover:bg-sky-100">Edit</button>
                                <button wire:click="confirmDelete(<?php echo e($asset->id); ?>)" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center text-gray-400">
                                No asset records yet. Click "Add New Asset" to get started.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            <?php echo e($assets->links()); ?>

        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmingDeletion): ?>
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="cancelDelete"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full bg-red-100">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Delete Asset Record</h3>
                            <p class="mt-1.5 text-sm text-gray-500">Are you sure you want to remove this asset record? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                    <button type="button" wire:click="delete" class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500">Delete Permanently</button>
                    <button type="button" wire:click="cancelDelete" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-5 right-5 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
        <?php echo e(session('message')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/Assetsmanagement/assets.blade.php ENDPATH**/ ?>