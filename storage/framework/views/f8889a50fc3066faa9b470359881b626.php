<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-50 rounded-lg">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Assets Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Transaction Records</h1>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Total Records</p>
            <p class="text-2xl font-extrabold text-gray-900"><?php echo e(number_format($this->summary['count'])); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Transfers</p>
            <p class="text-2xl font-extrabold text-blue-600"><?php echo e(number_format($this->summary['transfers'])); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Repairs</p>
            <p class="text-2xl font-extrabold text-amber-600"><?php echo e(number_format($this->summary['repairs'])); ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Search</label>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search by SKU, item type, location, notes..."
                    class="w-full px-4 py-2 text-sm bg-stone-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all"
                />
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Type</label>
                <select
                    wire:model.live="type"
                    class="py-2 px-3 text-sm bg-stone-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all bg-white"
                >
                    <option value="">All Types</option>
                    <option value="check-in">Check-in</option>
                    <option value="check-out">Check-out</option>
                    <option value="transfer">Transfer</option>
                    <option value="repair">Repair</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Date From</label>
                <input
                    wire:model.live="dateFrom"
                    type="date"
                    class="py-2 px-3 text-sm bg-stone-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all"
                />
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Date To</label>
                <input
                    wire:model.live="dateTo"
                    type="date"
                    class="py-2 px-3 text-sm bg-stone-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all"
                />
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search || $type || $dateFrom || $dateTo): ?>
                <button
                    wire:click="clearFilters"
                    class="py-2 px-3 text-sm font-semibold text-gray-500 hover:text-red-600 border border-dashed border-gray-300 hover:border-red-300 rounded-lg transition-colors"
                >
                    Clear
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-base font-bold text-gray-800">Records</h3>
            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                <?php echo e($this->summary['count']); ?> <?php echo e($this->summary['count'] === 1 ? 'transaction' : 'transactions'); ?>

            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Movement</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $typeClasses = match ($transaction->type) {
                                'check-in' => 'bg-emerald-100 text-emerald-700',
                                'check-out' => 'bg-rose-100 text-rose-700',
                                'transfer' => 'bg-blue-100 text-blue-700',
                                'repair' => 'bg-amber-100 text-amber-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        ?>
                        <tr class="hover:bg-amber-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-400">#<?php echo e($transaction->id); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900"><?php echo e($transaction->asset?->sku ?? 'Unknown Asset'); ?></div>
                                <div class="text-xs text-gray-400">
                                    <?php echo e($transaction->asset?->itemType?->name ?? 'Unknown Item Type'); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($typeClasses); ?>">
                                    <?php echo e(ucfirst($transaction->type)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>From: <?php echo e($transaction->fromLocation?->name ?? 'N/A'); ?></div>
                                <div>To: <?php echo e($transaction->toLocation?->name ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 font-medium"><?php echo e($transaction->datetime?->format('M d, Y')); ?></p>
                                <p class="text-xs text-gray-400"><?php echo e($transaction->datetime?->format('h:i A')); ?></p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    wire:click="viewDetail(<?php echo e($transaction->id); ?>)"
                                    class="rounded-md bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700 shadow-sm hover:bg-amber-100 transition-colors"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                    </svg>
                                    <p class="text-sm font-medium">
                                        <?php echo e(($search || $type || $dateFrom || $dateTo) ? 'No transactions match your filters.' : 'No transaction records yet.'); ?>

                                    </p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search || $type || $dateFrom || $dateTo): ?>
                                        <button wire:click="clearFilters" class="mt-2 text-xs text-amber-600 hover:underline font-medium">
                                            Clear all filters
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->transactions->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                <?php echo e($this->transactions->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDetail && $this->detail): ?>
        <?php $detail = $this->detail; ?>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('showDetail', false)"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl sm:w-full sm:max-w-2xl">
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-amber-100 rounded-lg">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Transaction #<?php echo e($detail->id); ?></h3>
                                    <p class="text-xs text-gray-400"><?php echo e($detail->datetime?->format('F d, Y h:i A')); ?></p>
                                </div>
                            </div>
                            <button wire:click="$set('showDetail', false)" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-0.5">Asset</p>
                                <p class="text-sm font-bold text-gray-800"><?php echo e($detail->asset?->sku ?? 'Unknown Asset'); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($detail->asset?->itemType?->name ?? 'Unknown Item Type'); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-0.5">Transaction Type</p>
                                <p class="text-sm font-bold text-gray-800"><?php echo e(ucfirst($detail->type)); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-0.5">From Location</p>
                                <p class="text-sm font-bold text-gray-800"><?php echo e($detail->fromLocation?->name ?? 'N/A'); ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-0.5">To Location</p>
                                <p class="text-sm font-bold text-gray-800"><?php echo e($detail->toLocation?->name ?? 'N/A'); ?></p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-stone-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Notes</p>
                            <p class="text-sm text-gray-700"><?php echo e($detail->notes ?: 'No notes recorded for this transaction.'); ?></p>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end rounded-b-xl">
                        <button
                            wire:click="$set('showDetail', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-5 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\admin\Documents\GitHub\nlah\resources\views/pages/Assetsmanagement/transaction-records.blade.php ENDPATH**/ ?>