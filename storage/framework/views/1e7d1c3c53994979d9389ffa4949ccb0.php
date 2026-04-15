<div class="max-w-6xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
    .brand-btn-primary       { background-color: #015581; color: #fff; transition: background-color 0.15s; }
    .brand-btn-primary:hover { background-color: #01406a; }
    .brand-focus:focus       { outline: none; box-shadow: 0 0 0 3px rgba(1,85,129,0.2); border-color: #015581; }
    .brand-row-hover:hover   { background-color: #f0f7fc; }
</style>

    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">HR</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Access Key Management</h1>
            </div>
        </div>
        <button wire:click="$set('showForm', true)"
            class="brand-btn-primary text-sm font-bold py-2 px-5 rounded shadow-md active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Access Key
        </button>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('message')): ?>
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
    <div class="bg-white shadow-md rounded-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-base font-bold text-gray-800">
                <?php echo e($isEditing ? 'Edit Access Key' : 'Create New Access Key'); ?>

            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Define what this key unlocks and where it redirects the user after login.</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Key Name *</label>
                <input type="text" wire:model="name" placeholder="e.g. HR Access"
                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
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
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Login Redirect</label>
                <select wire:model="redirect_to"
                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                    <option value="">— None (waiting area) —</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($route); ?>"><?php echo e($label); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <p class="text-xs text-gray-400 mt-1">Where the user lands immediately after login.</p>
            </div>

            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Description</label>
                <input type="text" wire:model="description" placeholder="Short note about what this key grants"
                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
            </div>

            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">
                    Module Access <span class="text-gray-400 font-normal normal-case">(check all this key can open)</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availablePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" wire:model="selectedPermissions" value="<?php echo e($slug); ?>"
                                class="rounded border-gray-300 text-[#015581] focus:ring-[#015581]"/>
                            <span class="text-sm text-gray-700 group-hover:text-gray-900"><?php echo e($label); ?></span>
                        </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            
            <div class="md:col-span-2">
                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border-2 transition-colors
                    <?php echo e($is_super ? 'border-amber-400 bg-amber-50' : 'border-gray-200 bg-white hover:border-gray-300'); ?>">
                    <input type="checkbox" wire:model="is_super"
                        class="mt-0.5 rounded border-gray-300 text-amber-500 focus:ring-amber-400"/>
                    <div>
                        <span class="text-sm font-bold text-gray-800 flex items-center gap-1">
                            Super Access
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($is_super): ?>
                                <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2 py-0.5 rounded ml-1">ACTIVE</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Bypasses ALL gate checks. This user can open every module regardless of what's checked above.
                            Only assign to fully trusted HR/admin accounts.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditing): ?>
                <button wire:click="update"
                    class="brand-btn-primary text-sm font-bold py-2 px-6 rounded shadow-md active:scale-95">
                    Save Changes
                </button>
            <?php else: ?>
                <button wire:click="save"
                    class="brand-btn-primary text-sm font-bold py-2 px-6 rounded shadow-md active:scale-95">
                    Create Key
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <button wire:click="$set('showForm', false); $set('isEditing', false)"
                class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                Cancel
            </button>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmingDeletion): ?>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 w-80">
            <h3 class="text-base font-bold text-gray-900 mb-2">Delete Access Key?</h3>
            <p class="text-sm text-gray-500 mb-1">Users assigned to this key will lose access (redirect to waiting area).</p>
            <p class="text-sm text-gray-500 mb-5">This cannot be undone.</p>
            <div class="flex gap-3 justify-end">
                <button wire:click="$set('confirmingDeletion', false)"
                    class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">Cancel</button>
                <button wire:click="delete"
                    class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-5 rounded shadow-md active:scale-95">
                    Delete
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search access keys…"
            class="brand-focus block w-full md:w-72 rounded-lg border border-gray-300 shadow-sm sm:text-sm p-2"/>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $keys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-5 py-4 flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-gray-900"><?php echo e($key->name); ?></h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key->is_super): ?>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">Super</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key->description): ?>
                        <p class="text-xs text-gray-400 mt-0.5"><?php echo e($key->description); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <button wire:click="edit(<?php echo e($key->id); ?>)"
                        class="text-xs font-semibold brand-text-primary hover:underline">Edit</button>
                    <button wire:click="confirmDelete(<?php echo e($key->id); ?>)"
                        class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
            </div>

            <div class="px-5 pb-4 space-y-3">
                
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        Login →
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key->redirect_to): ?>
                            <span class="font-mono text-[#015581] font-semibold"><?php echo e($availableRoutes[$key->redirect_to] ?? $key->redirect_to); ?></span>
                        <?php else: ?>
                            <span class="text-gray-400 italic">Waiting Area</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                </div>

                
                <div class="flex flex-wrap gap-1.5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key->is_super): ?>
                        <span class="text-[10px] font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">All modules (super)</span>
                    <?php elseif(!empty($key->permissions)): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $key->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <span class="text-[10px] font-semibold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">
                                <?php echo e($availablePermissions[$perm] ?? $perm); ?>

                            </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php else: ?>
                        <span class="text-[10px] text-gray-400 italic">No modules granted</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <p class="text-xs text-gray-400">
                    <span class="font-semibold text-gray-600"><?php echo e($key->users_count); ?></span>
                    <?php echo e(Str::plural('user', $key->users_count)); ?> assigned
                </p>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-10 text-center text-sm text-gray-400">
                No access keys found. Create one to start assigning system access.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\admin\Documents\GitHub\nlah\resources\views/pages/HR/access-key-management.blade.php ENDPATH**/ ?>