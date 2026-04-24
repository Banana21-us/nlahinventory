<div class="max-w-7xl mx-auto py-8 px-4">

    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg" style="background-color:#e6f0f7;">
                <svg class="w-6 h-6" style="color:#015581;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Hospital Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Patient Registry</h1>
            </div>
        </div>
    </div>

    
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden" x-data="{ open: <?php if ((object) ('showForm') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showForm'->value()); ?>')<?php echo e('showForm'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showForm'); ?>')<?php endif; ?> }">
        <button
            @click="open = !open"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none"
        >
            <div class="flex items-center">
                <div class="p-2 rounded-lg mr-4" style="background-color:#e6f0f7;">
                    <svg class="w-5 h-5" style="color:#015581;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none;"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Patient Entry</h2>
            </div>
            <span class="text-sm font-medium" style="color:#015581;" x-text="open ? 'Minimize' : 'Register New Patient'"></span>
        </button>

        <div
            x-show="open"
            x-collapse
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="p-6 border-t border-gray-100 bg-gray-50/30"
        >
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Full Name *</label>
                    <input type="text" wire:model="full_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2 border">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Age</label>
                    <input type="number" wire:model="age" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2 border">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Gender</label>
                    <select wire:model="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2 border bg-white">
                        <option value="">Select...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Contact Number</label>
                    <input type="text" wire:model="contact_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2 border">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Medical History / Allergies</label>
                    <textarea wire:model="medical_history" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2 border"></textarea>
                </div>

                <div class="md:col-span-3 flex justify-end space-x-3 pt-4 border-t border-gray-100 mt-2">
                    <button type="button" @click="open = false" class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4">
                        Cancel
                    </button>
                    <button type="submit" class="text-white text-sm font-bold py-2 px-10 rounded shadow-md transition-all active:scale-95"
                            style="background-color:#015581;"
                            onmouseover="this.style.backgroundColor='#01406a'"
                            onmouseout="this.style.backgroundColor='#015581'">
                        Register Patient
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="mt-10 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Patient Records</h3>
            <span class="text-sm text-gray-500">Total Patients: <?php echo e($patients->total()); ?></span>
        </div>
        <div class="overflow-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age / Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono font-semibold" style="color:#027c8b;">
                            <?php echo e($patient->patient_number); ?>

                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900"><?php echo e($patient->full_name); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($patient->age); ?>yrs / <?php echo e($patient->gender); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($patient->contact_number); ?></td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="<?php echo e(route('medmission.patient.details', $patient->id)); ?>" wire:navigate
                               class="rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm transition-colors"
                               style="background-color:#e6f0f7;color:#015581;"
                               onmouseover="this.style.backgroundColor='#cde0ef'"
                               onmouseout="this.style.backgroundColor='#e6f0f7'">
                                View Details
                            </a>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-100"
                    class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="shrink-0">
                                <svg class="size-6" style="color:#027c8b;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900">Success!</p>
                                <p class="mt-1 text-sm text-gray-500"><?php echo e(session('message')); ?></p>
                            </div>
                            <div class="ml-4 flex shrink-0 border-l border-gray-100 pl-3">
                                <button @click="show = false" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="h-1" style="background-color:#f0b626; animation: shrink 4s linear forwards;"></div>
                </div>
                <style>@keyframes shrink { from { width:100% } to { width:0% } }</style>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        <div class="p-4 border-t border-gray-100">
            <?php echo e($patients->links()); ?>

        </div>
    </div>

</div><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/medmission/patient-manager.blade.php ENDPATH**/ ?>