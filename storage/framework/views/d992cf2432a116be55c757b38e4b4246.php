<div class="max-w-7xl mx-auto py-8 px-4">
    
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-50 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Point of Sale</p>
                    <h1 class="text-xl font-bold text-gray-800 leading-tight">Items</h1>
                </div>
            </div>
        </div>
    
    <div
        class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden"
        x-data="{ open: <?php if ((object) ('showForm') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showForm'->value()); ?>')<?php echo e('showForm'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showForm'); ?>')<?php endif; ?> }">

        
        <button
            @click="open = !open"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none">
            <div class="flex items-center">
                <div class="p-2 bg-amber-50 rounded-lg mr-4">
                    <svg
                        class="w-5 h-5 text-amber-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                            x-show="!open"/>
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                            x-show="open"
                            style="display:none"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Item Entry</h2>
            </div>
            <span
                class="text-sm font-medium text-amber-600"
                x-text="open ? 'Minimize' : 'Add New Item'"></span>
        </button>

        
        <div
            x-show="open"
            x-collapse="x-collapse"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-6 border-t border-gray-100 bg-gray-50/30">
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                
                <div class="md:col-span-1">
                    <label
                        class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Item Name *</label>
                    <input
                        type="text"
                        wire:model="name"
                        placeholder="e.g. Burger, Iced Tea"
                        @keydown.enter.prevent
                        class="block w-full rounded-md border border-gray-300 shadow-sm
                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2"/>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                        Barcode
                    </label>
                    <input
                    type="text"
                    wire:model="barcode"
                    placeholder="Scan or type barcode…"
                    @keydown.enter.prevent
                    class="block w-full rounded-md border border-gray-300 shadow-sm
                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2"/>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div>
                    <label
                        class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Type / Category</label>
                    <select
                        wire:model="type"
                        class="block w-full rounded-md border border-gray-300 shadow-sm
                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2 bg-white">
                        <option value="" disabled>Select category</option>
                        <option value="Meals">Meals</option>
                        <option value="Drinks">Drinks</option>
                        <option value="Snacks">Snacks</option>
                        <option value="Utensils">Utensils</option>
                    </select>
                </div>

                
                <div>
                    <label
                        class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Price (₱) *</label>
                    <div class="relative">
                       
                        <input
                            type="number"
                            wire:model="price"
                            min="0"
                            placeholder="0"
                            @keydown.enter.prevent
                            class="block w-full pl-7 rounded-md border border-gray-300 shadow-sm
                                   focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2"/>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                
                
                <div>
                    <label
                        class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                    <select
                        wire:model="status"
                        class="block w-full rounded-md border border-gray-300 shadow-sm
                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2 bg-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                
                <div class="md:col-span-2">
                    <label
                        class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Item Image</label>
                    <div class="flex items-center gap-4">
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
                        <img
                            src="<?php echo e($image->temporaryUrl()); ?>"
                            class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-sm"/>
                        <?php else: ?>
                        <div
                            class="w-16 h-16 rounded-lg bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center">
                            <svg
                                class="w-6 h-6 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <input
                            type="file"
                            wire:model="image"
                            accept="image/*"
                            class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                   file:rounded-full file:border-0 file:text-xs file:font-semibold
                                   file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer"/>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                

                
                <div
                    class="md:col-span-3 flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-2">
                    <button
                        type="button"
                        @click="open = false"
                        class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold py-2 px-10 rounded shadow-md
                               transition-all active:scale-95 flex items-center gap-2">
                        <span wire:loading.remove="wire:loading.remove" wire:target="save">Save Item</span>
                        <span
                            wire:loading="wire:loading"
                            wire:target="save"
                            class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"/>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Saving…
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div
        class="mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        
        <div
            class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-50 rounded-lg">
                    <svg
                        class="w-4 h-4 text-amber-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Current Inventory</h3>
            </div>
            <span
                class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                <?php echo e($items->count()); ?>

                <?php echo e(Str::plural('item', $items->count())); ?>

            </span>
        </div>

        
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 ms-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                        <th
                            class="px-6 ms-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Barcode</th>
                        <th
                            class="px-6 ms-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th
                            class="px-6 ms-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th
                            class="px-6 ms-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th
                            class="px-6 ms-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="hover:bg-amber-50/40 transition-colors">

                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <?php $imgUrl = $item->image ? asset('storage/' . $item->image) : asset('images/placeholder.png'); ?>
                                <img
                                    src="<?php echo e($imgUrl); ?>"
                                    alt="<?php echo e($item->name); ?>"
                                    class="w-10 h-10 rounded-lg object-cover bg-gray-100 border border-gray-200 shrink-0"/>
                                <div>
                                    <p class="text-sm font-bold text-gray-900"><?php echo e($item->name); ?></p>
                                    
                                </div>
                            </div>
                        </td>

                        <!-- barcode -->
                         <td class="px-6 py-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->barcode): ?>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <?php echo e($item->barcode); ?>

                            </span>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">—</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>

                        
                        <td class="px-6 py-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->type): ?>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <?php echo e($item->type); ?>

                            </span>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">—</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>

                        
                        <td class="px-6 py-4 text-sm font-bold text-gray-800">
                            ₱<?php echo e(number_format($item->price, 2)); ?>

                        </td>

                        
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php echo e($item->status === 'active'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e(ucfirst($item->status)); ?>

                            </span>
                        </td>

                        
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <button
                                wire:click="edit(<?php echo e($item->id); ?>)"
                                class="rounded-md bg-amber-50 px-2.5 py-1.5 text-sm font-semibold text-amber-700
                                           shadow-sm hover:bg-amber-100 transition-colors">
                                Edit
                            </button>
                            <button
                                wire:click="confirmDelete(<?php echo e($item->id); ?>)"
                                class="text-red-500 hover:text-red-700 font-semibold transition-colors">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <svg
                                    class="w-10 h-10 mb-3 opacity-40"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="text-sm font-medium">No items found in the inventory.</p>
                                <p class="text-xs mt-1">Click "Add New Item" above to get started.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmingDeletion): ?>
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div
            class="fixed inset-0 bg-gray-500/75 transition-opacity"
            wire:click="$set('confirmingDeletion', false)"></div>

        <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full bg-red-100">
                            <svg
                                class="w-6 h-6 text-red-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Delete Item</h3>
                            <p class="mt-1.5 text-sm text-gray-500">
                                Are you sure you want to remove this item? Its image and all associated data
                                will be permanently deleted. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                    <button
                        type="button"
                        wire:click="delete"
                        class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white
                                   shadow-sm hover:bg-red-500 transition-colors active:scale-95">
                        Delete Permanently
                    </button>
                    <button
                        type="button"
                        wire:click="$set('confirmingDeletion', false)"
                        class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700
                                   shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditing): ?>
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div
            class="fixed inset-0 bg-gray-500/75 transition-opacity"
            wire:click="$set('isEditing', false)"></div>

        <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <form wire:submit.prevent="update">

                    
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                            <div class="p-2 bg-amber-100 rounded-lg mr-3">
                                <svg
                                    class="w-5 h-5 text-amber-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Update Item</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Item Name *</label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm
                                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2"/>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <!-- barcdode -->
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Barcode *</label>
                                <input
                                    type="text"
                                    wire:model="barcode"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm
                                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2"/>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <div>
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Type / Category</label>
                                <select
                                    wire:model="type"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm
                                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2 bg-white">
                                    <option value="" disabled>Select category</option>
                                    <option value="Meals">Meals</option>
                                    <option value="Drinks">Drinks</option>
                                    <option value="Snacks">Snacks</option>
                                    <option value="Utensils">Utensils</option>
                                </select>
                            </div>

                            
                            <div>
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Price (₱) *</label>
                                <div class="relative">
                                
                                    <input
                                        type="number"
                                        wire:model="price"
                                        min="0"
                                        class="block w-full pl-7 rounded-md border border-gray-300 shadow-sm
                                                   focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2"/>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Item Image</label>
                                <div class="flex items-center gap-4">
                                    
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
                                    <img
                                        src="<?php echo e($image->temporaryUrl()); ?>"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-sm"/>
                                    <?php elseif($existingImage): ?>
                                    <img
                                        src="<?php echo e(Storage::disk('public')->url($existingImage)); ?>"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-sm"/>
                                    <?php else: ?>
                                    <div
                                        class="w-16 h-16 rounded-lg bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center">
                                        <svg
                                            class="w-6 h-6 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div>
                                        <input
                                            type="file"
                                            wire:model="image"
                                            accept="image/*"
                                            class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                                       file:rounded-full file:border-0 file:text-xs file:font-semibold
                                                       file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer"/>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingImage): ?>
                                        <p class="text-xs text-gray-400 mt-1">Upload a new image to replace the current one.</p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <div>
                                <label
                                    class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                                <select
                                    wire:model="status"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm
                                               focus:ring-amber-500 focus:border-amber-500 sm:text-sm p-2 bg-white">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-lg bg-amber-500 px-5 py-2 text-sm font-bold text-white
                                       shadow-sm hover:bg-amber-600 transition-colors active:scale-95 flex items-center gap-2">
                            <span wire:loading.remove="wire:loading.remove" wire:target="update">Save Changes</span>
                            <span
                                wire:loading="wire:loading"
                                wire:target="update"
                                class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"/>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Saving…
                            </span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('isEditing', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700
                                       shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
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
        class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5">
        <div class="p-4 flex items-start gap-3">
            <div
                class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full bg-green-100">
                <svg
                    class="w-5 h-5 text-green-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-semibold text-gray-900">Success!</p>
                <p class="mt-0.5 text-sm text-gray-500"><?php echo e(session('message')); ?></p>
            </div>
            <button
                @click="show = false"
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                </svg>
            </button>
        </div>
        
        <div
            class="h-1 bg-green-500 origin-left animate-[shrink_4s_linear_forwards]"
            style="animation: shrink 4s linear forwards; @keyframes shrink { from { width: 100%; } to { width: 0%; } }"></div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH C:\Users\admin\Documents\GitHub\nlah\resources\views/pages/POSuser/Items.blade.php ENDPATH**/ ?>