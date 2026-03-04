<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden" x-data="{ open: @entangle('showForm') }">
    <button 
        @click="open = !open" 
        class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none"
    >
        <div class="flex items-center">
            <div class="p-2 bg-indigo-50 rounded-lg mr-4">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display: none;"></path>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Medicine Entry</h2>
        </div>
        <span class="text-sm font-medium text-indigo-600" x-text="open ? 'Minimize' : 'Add New Medicine'"></span>
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
            <div class="md:col-span-1">
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Generic Name *</label>
                <input type="text" wire:model="generic_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                @error('generic_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Brand Name</label>
                <input type="text" wire:model="brand_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Quantity</label>
                <input type="text" wire:model="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Dosage Form</label>
                <input type="text" wire:model="dosage_form" placeholder="e.g Tablet, Capsule, Syrup" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Strength</label>
                    <input type="text" wire:model="strength" placeholder="e.g 500mg, 100ml" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Unit</label>
                    <input type="text" wire:model="unit" placeholder="e.g box, bottle, piece" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Reorder Level</label>
                <input type="number" wire:model="reorder_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Description</label>
                <input type="text" wire:model="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Status</label>
                <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border bg-white">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="md:col-span-3 flex justify-end space-x-3 pt-4 border-t border-gray-100 mt-2">
                <button type="button" @click="open = false" class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-10 rounded shadow-md transition-all active:scale-95">
                    Save Medicine
                </button>
            </div>
        </form>
    </div>
</div>

    <div class="mt-10 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Current Inventory</h3>
            <span class="text-sm text-gray-500">Total Items: {{ $medicines->count() }}</span>
        </div>
        <div class="overflow-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Form/Strength</th>
                        <th class="px-6 py-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($medicines as $medicine)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $medicine->generic_name }}</div>
                            <div class="text-xs text-gray-500">{{ $medicine->brand_name ?? 'No Brand' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $medicine->dosage_form ?? 'N/A' }} 
                            <span class="text-indigo-600 font-medium">({{ $medicine->strength }})</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $medicine->description ?? 'None' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $medicine->quantity ?? 'None' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $medicine->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($medicine->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button 
                                wire:click="edit({{ $medicine->id }})" 
                                class="rounded-md bg-indigo-50 px-2.5 py-1.5 text-sm font-semibold text-indigo-700 shadow-sm hover:bg-indigo-100 transition-colors"
                            >
                                Edit
                            </button>
                            <button wire:click="confirmDelete({{ $medicine->id }})" class="text-red-600 hover:text-red-900 mx-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">No medicines found in the system.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($confirmingDeletion)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('confirmingDeletion', false)"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10">
                        <svg class="size-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-base font-semibold text-gray-900" id="modal-title">Delete Medicine</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to remove this item? This record will be permanently deleted from the inventory. This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button 
                    type="button" 
                    wire:click="delete" 
                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                >
                    Delete Permanently
                </button>
                <button 
                    type="button" 
                    wire:click="$set('confirmingDeletion', false)" 
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@if (session()->has('message'))
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
                            <svg class="size-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">Success!</p>
                            <p class="mt-1 text-sm text-gray-500">{{ session('message') }}</p>
                        </div>
                        <div class="ml-4 flex shrink-0 border-l border-gray-100 pl-3">
                            <button @click="show = false" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">Close</span>
                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

       @if($isEditing)
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('isEditing', false)"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
            <form wire:submit.prevent="update">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="flex items-center mb-6 pb-3 border-b border-gray-100">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Update Medicine Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Generic Name *</label>
                            <input type="text" wire:model="generic_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-indigo-500">
                            @error('generic_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Brand Name</label>
                            <input type="text" wire:model="brand_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Quantity</label>
                            <input type="text" wire:model="quant" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Dosage Form</label>
                            <input type="text" wire:model="dosage_form" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Strength</label>
                                <input type="text" wire:model="strength" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Unit</label>
                                <input type="text" wire:model="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Reorder Level</label>
                            <input type="number" wire:model="reorder_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Status</label>
                            <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border bg-white">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Description</label>
                            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                        Save Changes
                    </button>
                    <button type="button" wire:click="$set('isEditing', false)" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
        </div>
    </div>
</div>



