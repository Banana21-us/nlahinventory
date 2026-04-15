<div
    class="flex h-screen font-sans antialiased bg-slate-50/50 text-slate-900 overflow-hidden">
    <div
    x-data="{
        buffer: '',
        timeout: null,
        minLength: 3,
        maxGap: 50,

        handleKey(e) {
            const tag = document.activeElement?.tagName;
            const isRealInput = (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT')
                && document.activeElement !== this.$refs.scannerInput;

            if (isRealInput) return;

            if (e.key === 'Enter') {
                if (this.buffer.length >= this.minLength) {
                    if ($store.scanner.mode === 'cart') {
                        $wire.addToCartBybarcode(this.buffer);
                    } else {
                        $wire.set('search', this.buffer);
                    }
                }
                this.buffer = '';
                clearTimeout(this.timeout);
                return;
            }

            if (e.key.length === 1) {
                this.buffer += e.key;
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => { this.buffer = ''; }, this.maxGap);
            }
        }
    }"
    x-init="window.addEventListener('keydown', e => handleKey(e))"
    class="sr-only"
    aria-hidden="true"
>
    <input x-ref="scannerInput" type="text" tabindex="-1" readonly />
</div>
    
    <div
        class="flex flex-col flex-1 min-w-0 bg-white border-r border-slate-200/60 shadow-sm">

        
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-2xl bg-amber-500 flex items-center justify-center shadow-lg shadow-amber-100">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">Cafeteria POS</h1>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Northern Luzon Adventist Hospital</p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full border border-slate-200/50">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-bold font-mono text-slate-600" id="pos-clock">--:--:--</span>
                    
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-full border border-slate-200/60">
                    <button
                        type="button"
                        @click="$store.scanner.mode = 'cart'"
                        :class="$store.scanner.mode === 'cart'
                            ? 'bg-white text-amber-600 shadow-sm ring-1 ring-slate-200'
                            : 'text-slate-400 hover:text-slate-600'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all"
                    >
                        🛒 Cart
                    </button>
                    <button
                        type="button"
                        @click="$store.scanner.mode = 'search'"
                        :class="$store.scanner.mode === 'search'
                            ? 'bg-white text-amber-600 shadow-sm ring-1 ring-slate-200'
                            : 'text-slate-400 hover:text-slate-600'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all"
                    >
                        🔍 Search
                    </button>
                </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <input
                        wire:model.live="search"
                        type="text"
                        placeholder="    Search products..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm transition-all focus:ring-2 focus:ring-amber-400/20 focus:border-amber-400 focus:bg-white"
                        x-data="{
                        buffer: '',
                        timeout: null,
                        lastKey: 0,
                        maxGap: 50,
                        minLength: 3,
                    }"
                        @keydown="
                        const now = Date.now();
                        const gap = now - lastKey;
                        lastKey = now;

                        if ($event.key === 'Enter') {
                            $event.preventDefault();
                            if (buffer.length >= minLength) {
                                $wire.addToCartBybarcode(buffer);
                                $wire.set('search', '');
                            }
                            buffer = '';
                            clearTimeout(timeout);
                            return;
                        }

                        if ($event.key.length === 1) {
                            if (gap < maxGap) {
                                buffer += $event.key;
                            } else {
                                buffer = $event.key;
                            }
                            clearTimeout(timeout);
                            timeout = setTimeout(() => { buffer = ''; }, 300);
                        }
                    "/>
                </div>
                <div
                    class="flex gap-1.5 p-1 bg-slate-100/50 rounded-xl border border-slate-200/60 overflow-x-auto no-scrollbar">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['all' => 'All', 'meals' => '🍱', 'drinks' => '🥤', 'snacks' => '🍟']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <button
                        type="button"
                        wire:click="$set('category', '<?php echo e($key); ?>')"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition-all
                                   <?php echo e($category === $key
                                        ? 'bg-white text-amber-600 shadow-sm ring-1 ring-slate-200'
                                        : 'text-slate-500 hover:text-slate-700'); ?>">
                        <?php echo e($label); ?>

                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
        <div
            class="mx-6 mb-2 flex items-center gap-2.5 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-medium">
            <svg
                class="w-4 h-4 shrink-0 text-red-400"
                fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"/>
            </svg>
            <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
        <div
            class="mx-6 mb-2 flex items-center gap-2.5 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-medium">
            <svg
                class="w-4 h-4 shrink-0 text-emerald-500"
                fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"/>
            </svg>
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('checkout_success')): ?>
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 2500)"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mx-6 mb-2 flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs font-semibold text-emerald-700">
            <svg
                class="w-4 h-4 shrink-0 text-emerald-500"
                fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"/>
            </svg>
            <?php echo e(session('checkout_success')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex-1 overflow-y-auto px-6 pb-6 custom-scrollbar">
            <div
                class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                <button
                    type="button"
                    wire:click="openExtraUtensilsModal"
                    <?php if($this->extraUtensilsDisabled): echo 'disabled'; endif; ?>="<?php if($this->extraUtensilsDisabled): echo 'disabled'; endif; ?>"
                    class="group text-left border rounded-xl overflow-hidden transition-all
                           <?php echo e($this->extraUtensilsDisabled
                                ? 'bg-slate-100 border-slate-200 opacity-60 cursor-not-allowed'
                                : 'bg-white border-slate-200 hover:border-amber-400 hover:shadow-lg hover:shadow-amber-50/50 active:scale-[0.98]'); ?>">
                    <div
                        class="relative overflow-hidden bg-gradient-to-br from-slate-100 via-amber-50 to-white"
                        style="height:80px">
                        <div class="absolute inset-0 flex items-center justify-center text-3xl">🍴</div>
                        <div class="absolute top-1.5 right-1.5">
                            <span
                                class="rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-black uppercase text-amber-700 shadow-sm">
                                Extras
                            </span>
                        </div>
                    </div>
                    <div class="p-2">
                        <h3 class="text-xs font-bold text-slate-800 leading-tight">Extra Utensils</h3>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">Boxes, cups, forks, spoons</p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->extraUtensilsDisabled): ?>
                        <p class="mt-1 text-[10px] font-semibold text-red-500">No extra utensils in stock</p>
                        <?php else: ?>
                        <p class="mt-1 text-xs font-extrabold text-amber-600">Choose extras</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </button>

                <button
                    type="button"
                    wire:click="openBudgetMealModal"
                    <?php if($this->budgetMealDisabled): echo 'disabled'; endif; ?>="<?php if($this->budgetMealDisabled): echo 'disabled'; endif; ?>"
                    class="group text-left border rounded-xl overflow-hidden transition-all
                           <?php echo e($this->budgetMealDisabled
                                ? 'bg-slate-100 border-slate-200 opacity-60 cursor-not-allowed'
                                : 'bg-white border-slate-200 hover:border-amber-400 hover:shadow-lg hover:shadow-amber-50/50 active:scale-[0.98]'); ?>">
                    <div
                        class="relative overflow-hidden bg-gradient-to-br from-amber-100 via-orange-50 to-white"
                        style="height:80px">
                        <div class="absolute inset-0 flex items-center justify-center text-3xl">🍱</div>
                        <div class="absolute top-1.5 right-1.5">
                            <span
                                class="rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-black uppercase text-amber-700 shadow-sm">
                                Bundle
                            </span>
                        </div>
                    </div>
                    <div class="p-2">
                        <h3 class="text-xs font-bold text-slate-800 leading-tight">Budget Meal</h3>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">Rice + meal + utensils</p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->budgetMealDisabled): ?>
                        <p class="mt-1 text-[10px] font-semibold text-red-500">
                            Unavailable until rice, meal, and utensils are stocked
                        </p>
                        <?php else: ?>
                        <p class="mt-1 text-xs font-extrabold text-amber-600">Build order</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </button>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->filteredItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?> <?php $imagePath = $item->image ?
                (str_starts_with($item->image, 'item_images/') ? $item->image : 'item_images/' .
                ltrim($item->image, '/')) : null; $imageUrl = $imagePath ?
                rtrim(request()->getSchemeAndHttpHost(), '/') . '/storage/' . $imagePath :
                rtrim(request()->getSchemeAndHttpHost(), '/') . '/images/placeholder.png';
                ?>
                <button
                    wire:click="addToCart(<?php echo e($item->id); ?>)"
                    class="group text-left bg-white border border-slate-200 rounded-xl overflow-hidden
                               hover:border-amber-400 hover:shadow-lg hover:shadow-amber-50/50
                               transition-all active:scale-[0.98]">
                    <div class="relative overflow-hidden bg-slate-100" style="height:80px">
                        <img
                            src="<?php echo e($imageUrl); ?>"
                            alt="<?php echo e($item->name); ?>"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                        <div
                            class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="p-1 bg-white/90 backdrop-blur rounded-full shadow-sm">
                                <svg
                                    class="w-3 h-3 text-amber-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-2">
                        <h3
                            class="text-xs font-bold text-slate-800 line-clamp-1 capitalize leading-tight"><?php echo e($item->name); ?></h3>
                        <p class="text-amber-600 font-extrabold mt-0.5 text-xs">₱<?php echo e(number_format($item->price, 2)); ?></p>
                    </div>
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex p-4 rounded-full bg-slate-50 text-slate-300 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/></svg>
                    </div>
                    <p class="text-slate-500 font-medium">No products found.</p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="flex flex-col w-[380px] shrink-0 bg-slate-50/50">

        <div class="p-6 pb-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                Order Items
                <span
                    class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-[10px] font-black uppercase">
                    <?php echo e(count($this->cart)); ?>

                    Items
                </span>
            </h2>
        </div>

        
        <div class="flex-1 overflow-y-auto px-6 space-y-2 min-h-0">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div
                class="flex items-center gap-4 p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-amber-200 transition-all">
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-slate-800 truncate"><?php echo e($cartItem['name']); ?></h4>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($cartItem['is_bundle'])): ?>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5 truncate"><?php echo e($cartItem['bundle_label']); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">₱<?php echo e(number_format($cartItem['price'], 2)); ?>

                        / unit</p>
                </div>
                <div class="flex items-center gap-2">
                    <input
                        type="number"
                        wire:model.live.debounce.500ms="cart.<?php echo e($cartItem['id']); ?>.quantity"
                        class="w-12 h-8 text-center text-sm font-black bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-400/20 focus:border-amber-400 focus:outline-none"/>
                    <button
                        wire:click="removeFromCart('<?php echo e($cartItem['id']); ?>')"
                        class="p-1.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="flex flex-col items-center justify-center h-full opacity-40">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p class="text-sm font-medium">Cart is currently empty</p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div
            class="p-5 bg-white border-t border-slate-200/60 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)] space-y-3">

            
            <div class="grid grid-cols-2 gap-3">
                
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Customer</label>
                    <select
                        wire:model.live="customer_id"
                        class="w-full text-xs font-bold py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-lg
                   focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:outline-none appearance-none
                   <?php echo e($payment_method === 'Credit' && !$customer_id ? 'border-red-300 bg-red-50' : ''); ?>">
                        <option value="">Walk-in Customer</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment_method === 'Credit' && !$customer_id): ?>
                    <p class="text-[10px] text-red-500 font-semibold mt-0.5">Required for credit</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Payment</label>
                    <select
                        wire:model.live="payment_method"
                        class="w-full text-xs font-bold py-2 px-2.5 bg-slate-50 border border-slate-200 rounded-lg
                   focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:outline-none appearance-none">
                        <option value="Cash">Cash</option>
                        <option value="Gcash">GCash</option>
                        <option value="Credit">Credit</option>
                    </select>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment_method === 'Credit' && $customer_id && $this->selectedCustomer): ?>
            <?php $sc = $this->selectedCustomer; ?>
            <div
                class="rounded-xl border p-3 space-y-1.5
        <?php echo e((float)$sc->balance >= $this->subtotal
            ? 'bg-emerald-50 border-emerald-200'
            : 'bg-red-50 border-red-200'); ?>">

                <div class="flex items-center justify-between">
                    <span
                        class="text-[10px] font-bold uppercase tracking-wider
                <?php echo e((float)$sc->balance >= $this->subtotal ? 'text-emerald-600' : 'text-red-600'); ?>">
                        Credit Balance
                    </span>
                    <span
                        class="text-sm font-extrabold
                <?php echo e((float)$sc->balance >= $this->subtotal ? 'text-emerald-700' : 'text-red-700'); ?>">
                        ₱<?php echo e(number_format($sc->balance, 2)); ?>

                    </span>
                </div>

                
                <?php $remaining = (float)$sc->balance - $this->subtotal; ?>
                <div class="flex items-center justify-between text-[11px]">
                    <span class="text-slate-500">After this sale</span>
                    <span
                        class="font-bold <?php echo e($remaining >= 0 ? 'text-slate-600' : 'text-red-600'); ?>">
                        ₱<?php echo e(number_format(max(0, $remaining), 2)); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($remaining < 0): ?>
                        <span class="text-[10px] font-semibold text-red-500">(₱<?php echo e(number_format(abs($remaining), 2)); ?>

                            over)</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((float)$sc->charges > 0): ?>
                <div
                    class="flex items-center justify-between text-[11px] pt-1 border-t border-current/10">
                    <span class="text-slate-500">Existing charges</span>
                    <span class="font-bold text-red-500">₱<?php echo e(number_format($sc->charges, 2)); ?></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((float)$sc->balance < $this->subtotal): ?>
                <p class="text-[11px] font-semibold text-red-600 pt-1">
                    ⚠ Insufficient balance to cover this sale.
                </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            
            <div
                x-data="{ stackedAmount: $wire.entangle('paid_amount') }"
                x-init="stackedAmount = stackedAmount || 0">
                
                <div class="flex items-center justify-between mb-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Tendered Amount</label>
                    <button
                        type="button"
                        x-on:click="stackedAmount = 0; $wire.set('paid_amount', 0);"
                        class="flex items-center gap-1 text-[10px] font-bold text-slate-400 hover:text-red-500 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                </div>

                
                <div class="grid grid-cols-2 gap-1.5 mb-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [1000, 500, 200, 100, 50, 20, 10, 5]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <button
                        type="button"
                        x-on:click="
                                stackedAmount = (parseFloat(stackedAmount) || 0) + <?php echo e($bill); ?>;
                                $wire.set('paid_amount', stackedAmount);
                            "
                        class="py-2.5 rounded-lg text-xs font-bold border border-slate-200 bg-slate-50 text-slate-600
                                   hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700
                                   active:scale-95 transition-all duration-100">
                        +<?php echo e($bill >= 1000 ? '1K' : '₱'.$bill); ?>

                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                
                <div>
                    <label class="text-[10px] font-medium text-slate-400 block mb-1">Exact Amount</label>
                    <input
                        type="number"
                        min="0"
                        step="1"
                        placeholder="0"
                        wire:model.live.debounce.300ms="paid_amount"
                        x-on:input="stackedAmount = parseFloat($event.target.value) || 0"
                        class="w-full py-2 px-3 bg-amber-50 border border-amber-200 rounded-lg text-sm font-bold
                               text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-400
                               focus:border-transparent transition-all"/>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('checkout_alert')): ?>
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 2500)"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mt-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600">
                    <?php echo e(session('checkout_alert')); ?>

                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                <div class="flex justify-between text-xs font-medium text-slate-500">
                    <span>Tendered</span>
                    <span class="font-bold text-slate-700">₱<?php echo e(number_format($this->paid_amount, 2)); ?></span>
                </div>
                <div class="flex justify-between text-xs font-medium text-slate-500">
                    <span>Change</span>
                    <span
                        class="font-bold <?php echo e($this->change >= 0 ? 'text-emerald-600' : 'text-red-500'); ?>">
                        ₱<?php echo e(number_format($this->change, 2)); ?>

                    </span>
                </div>
                <div
                    class="flex justify-between items-center pt-2 border-t border-slate-200/60">
                    <span class="text-sm font-bold text-slate-900">Total Amount</span>
                    <span class="text-2xl font-black text-slate-900 tracking-tight">₱<?php echo e(number_format($this->subtotal, 2)); ?></span>
                </div>
            </div>

            
            <button
                wire:click="checkout"
                wire:loading.attr="disabled"
                class="w-full py-4 bg-stone-900 hover:bg-stone-800 disabled:bg-slate-300 text-white rounded-2xl font-bold
                       transition-all shadow-lg shadow-stone-900/20 flex items-center justify-center gap-3 active:scale-[0.98]">
                <span
                    wire:loading.remove="wire:loading.remove"
                    wire:target="checkout"
                    class="flex items-center gap-2 text-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    Place Order & Complete Sale
                </span>
                <span
                    wire:loading="wire:loading"
                    wire:target="checkout"
                    class="flex items-center gap-2 text-black">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
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
                    Processing...
                </span>
            </button>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showExtraUtensilsModal): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div
            class="absolute inset-0 bg-black/40 backdrop-blur-sm"
            wire:click="closeExtraUtensilsModal"></div>

        <div
            class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h3 class="text-lg font-black text-slate-900">Select Extra Utensils</h3>
                <p class="mt-1 text-sm text-slate-500">Choose extra utensils and adjust how many you want.</p>
            </div>

            <div class="space-y-4 px-6 py-5">
                <div>
                    <label
                        class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-slate-400">Extra Utensils</label>
                    <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->extraUtensilOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utensil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <label
                            class="flex items-center justify-between gap-3 rounded-lg bg-white px-3 py-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    value="<?php echo e($utensil->id); ?>"
                                    wire:model.live="extraUtensilIds"
                                    class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-400"/>
                                <span><?php echo e($utensil->name); ?></span>
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-amber-600">₱<?php echo e(number_format($utensil->price, 2)); ?></span>
                                <div class="flex items-center rounded-lg border border-slate-200 bg-slate-50">
                                    <button
                                        type="button"
                                        wire:click.stop="decreaseExtraUtensil('<?php echo e($utensil->id); ?>')"
                                        class="px-2 py-1 text-sm font-black text-slate-500 hover:text-amber-600">
                                        -
                                    </button>
                                    <span class="min-w-8 px-2 text-center text-xs font-black text-slate-700">
                                        <?php echo e($extraUtensilQuantities[$utensil->id] ?? 0); ?>

                                    </span>
                                    <button
                                        type="button"
                                        wire:click.stop="increaseExtraUtensil('<?php echo e($utensil->id); ?>')"
                                        class="px-2 py-1 text-sm font-black text-slate-500 hover:text-amber-600">
                                        +
                                    </button>
                                </div>
                            </div>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-400">Tick an item, then use +/- to change how many to include.</p>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Extra Utensils Total</span>
                        <span class="text-xl font-black text-amber-700">₱<?php echo e(number_format($this->extraUtensilsTotal, 2)); ?></span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-slate-200 bg-white px-6 py-4">
                <button
                    type="button"
                    wire:click="closeExtraUtensilsModal"
                    class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50">
                    Cancel
                </button>
                <button
                    type="button"
                    wire:click="addExtraUtensilsToCart"
                    class="flex-1 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-amber-600">
                    Add Extra Utensils
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBudgetMealModal): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div
            class="absolute inset-0 bg-black/40 backdrop-blur-sm"
            wire:click="closeBudgetMealModal"></div>

        <div
            class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h3 class="text-lg font-black text-slate-900">Build Budget Meal</h3>
                <p class="mt-1 text-sm text-slate-500">Choose one rice, one meal, and as many utensils as you need.</p>
            </div>

            <div class="space-y-4 px-6 py-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('budget_meal_alert')): ?>
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 2500)"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600">
                    <?php echo e(session('budget_meal_alert')); ?>

                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div>
                    <label
                        class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-slate-400">Rice</label>
                    <select
                        wire:model.live="selectedBudgetRice"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
                        <option value="">Select rice</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->budgetMealRiceOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($rice->id); ?>"><?php echo e($rice->name); ?>

                            - ₱<?php echo e(number_format($rice->price, 2)); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                <div>
                    <label
                        class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-slate-400">Meal</label>
                    <select
                        wire:model.live="selectedBudgetMeal"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
                        <option value="">Select meal</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->budgetMealMainOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($meal->id); ?>"><?php echo e($meal->name); ?>

                            - ₱<?php echo e(number_format($meal->price, 2)); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                <div>
                    <label
                        class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-slate-400">Utensils</label>
                    <select
                        wire:model.live="selectedBudgetUtensil"
                        class="hidden w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
                        <option value="">Select utensils</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->budgetMealUtensilOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utensil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($utensil->id); ?>"><?php echo e($utensil->name); ?>

                            - ₱<?php echo e(number_format($utensil->price, 2)); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->budgetMealUtensilOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utensil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <label
                            class="flex items-center justify-between gap-3 rounded-lg bg-white px-3 py-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    value="<?php echo e($utensil->id); ?>"
                                    wire:model.live="selectedBudgetUtensils"
                                    class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-400"/>
                                <span><?php echo e($utensil->name); ?></span>
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-amber-600">₱<?php echo e(number_format($utensil->price, 2)); ?></span>
                                <div class="flex items-center rounded-lg border border-slate-200 bg-slate-50">
                                    <button
                                        type="button"
                                        wire:click.stop="decreaseBudgetMealUtensil('<?php echo e($utensil->id); ?>')"
                                        class="px-2 py-1 text-sm font-black text-slate-500 hover:text-amber-600">
                                        -
                                    </button>
                                    <span class="min-w-8 px-2 text-center text-xs font-black text-slate-700">
                                        <?php echo e($budgetMealUtensilQuantities[$utensil->id] ?? 0); ?>

                                    </span>
                                    <button
                                        type="button"
                                        wire:click.stop="increaseBudgetMealUtensil('<?php echo e($utensil->id); ?>')"
                                        class="px-2 py-1 text-sm font-black text-slate-500 hover:text-amber-600">
                                        +
                                    </button>
                                </div>
                            </div>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-400">Tick a utensil, then use +/- to change how many to include.</p>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Budget Meal Total</span>
                        <span class="text-xl font-black text-amber-700">₱<?php echo e(number_format($this->budgetMealTotal, 2)); ?></span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-slate-200 bg-white px-6 py-4">
                <button
                    type="button"
                    wire:click="closeBudgetMealModal"
                    class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50">
                    Cancel
                </button>
                <button
                    type="button"
                    wire:click="addBudgetMealToCart"
                    class="flex-1 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-amber-600">
                    Add Budget Meal
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <script>
        function printReceipt(url) {
            
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = url;
            iframe.onload = function () {
                setTimeout(() => {
                    iframe
                        .contentWindow
                        .focus();
                    iframe
                        .contentWindow
                        .print();
                }, 500);
            };
            document
                .body
                .appendChild(iframe);
        }(function tick() {
            const el = document.getElementById('pos-clock');
            if (el) 
                el.textContent = new Date().toLocaleTimeString('en-PH', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            setTimeout(tick, 1000);
        })();
        
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
                Alpine.store('scanner', { mode: 'cart' });
            });
    </script>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCreditConfirm && $this->selectedCustomer): ?> <?php $sc =
    $this->selectedCustomer; ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        
        <div
            class="absolute inset-0 bg-black/40 backdrop-blur-sm"
            wire:click="cancelCreditConfirm"></div>

        
        <div
            class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">

            
            <div class="h-1.5 w-full bg-amber-400"></div>

            <div class="p-6">
                
                <div class="flex items-start gap-4 mb-5">
                    <div
                        class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center shrink-0">
                        <svg
                            width="20"
                            height="20"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            style="stroke:#f59e0b">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-stone-900 leading-tight">Insufficient Balance</h3>
                        <p class="text-xs text-stone-400 font-medium mt-0.5">Credit limit exceeded for this sale</p>
                    </div>
                </div>

                
                <div
                    class="bg-stone-50 rounded-xl border border-stone-200 p-4 space-y-2.5 mb-5">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-stone-500 font-medium">Customer</span>
                        <span class="font-bold text-stone-800"><?php echo e($sc->name); ?></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-stone-500 font-medium">Available balance</span>
                        <span class="font-bold text-stone-800">₱<?php echo e(number_format($sc->balance, 2)); ?></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-stone-500 font-medium">Order total</span>
                        <span class="font-bold text-stone-800">₱<?php echo e(number_format($this->subtotal, 2)); ?></span>
                    </div>
                    <div class="border-t border-stone-200 pt-2.5 flex justify-between items-center">
                        <span class="text-xs font-bold uppercase tracking-wider text-red-500">Shortfall → Charges</span>
                        <span class="font-black text-red-600 text-base">₱<?php echo e(number_format($creditShortfall, 2)); ?></span>
                    </div>
                </div>

                
                <p class="text-xs text-stone-400 leading-relaxed mb-5">
                    The customer's balance will be set to
                    <strong class="text-stone-600">₱0.00</strong>
                    and
                    <strong class="text-red-500">₱<?php echo e(number_format($creditShortfall, 2)); ?></strong>
                    will be added to their outstanding charges. Do you want to proceed?
                </p>

                
                <div class="flex gap-3">
                    <button
                        wire:click="cancelCreditConfirm"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold border border-stone-200 bg-white text-stone-600
                               hover:bg-stone-50 transition-colors">
                        Cancel
                    </button>
                    <button
                        wire:click="confirmCreditCheckout"
                        wire:loading.attr="disabled"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-amber-500 hover:bg-amber-600
                               text-white transition-colors shadow-lg shadow-amber-200 active:scale-[0.98]">
                        <span
                            wire:loading.remove="wire:loading.remove"
                            wire:target="confirmCreditCheckout">Proceed & Charge</span>
                        <span wire:loading="wire:loading" wire:target="confirmCreditCheckout">Processing…</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\admin\Documents\GitHub\nlah\resources\views/pages/POSuser/livewire/p-o-s.blade.php ENDPATH**/ ?>