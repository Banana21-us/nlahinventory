<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
    .brand-btn-primary       { background-color: #015581; color: #ffffff; transition: background-color 0.15s ease; }
    .brand-btn-primary:hover { background-color: #01406a; }
    .brand-bg-teal           { background-color: #027c8b; }
    .brand-bg-teal-light     { background-color: #e6f4f5; }
    .brand-text-teal         { color: #027c8b; }
    .brand-btn-teal          { background-color: #027c8b; color: #ffffff; transition: background-color 0.15s ease; }
    .brand-btn-teal:hover    { background-color: #016070; }
    .brand-focus:focus       { outline: none; box-shadow: 0 0 0 3px rgba(1,85,129,0.2); border-color: #015581; }
    .search-focus:focus      { outline: none; box-shadow: 0 0 0 3px rgba(2,124,139,0.2); border-color: #027c8b; }
    .brand-row-hover:hover   { background-color: #f0f7fc; }
    .brand-edit-btn          { background-color: #e6f0f7; color: #015581; }
    .brand-edit-btn:hover    { background-color: #cde0ef; }
    @keyframes shrink { from { width: 100% } to { width: 0% } }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">System</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Leave Types</h1>
            </div>
        </div>
    </div>

    {{-- ADD FORM --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden"
         x-data="{ open: @entangle('showForm') }">

        <button @click="open = !open"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none">
            <div class="flex items-center">
                <div class="p-2 rounded-lg mr-4 brand-bg-primary-light">
                    <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Leave Type Entry</h2>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="open ? 'Minimize' : 'Add New Leave Type'"></span>
        </button>

        <div x-show="open" x-collapse class="p-6 border-t border-gray-100 bg-gray-50/30">
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Code *</label>
                    <input type="text" wire:model="code" placeholder="e.g. VL"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 uppercase"/>
                    @error('code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Label *</label>
                    <input type="text" wire:model="label" placeholder="e.g. Vacation Leave"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('label') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Annual Days</label>
                    <input type="number" step="0.5" wire:model="annual_days" placeholder="e.g. 15"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('annual_days') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reset Type *</label>
                    <select wire:model="reset_type" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                        <option value="anniversary">Anniversary</option>
                        <option value="january">January</option>
                        <option value="birth_month">Birth Month</option>
                        <option value="none">None</option>
                    </select>
                    @error('reset_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-3 items-center pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_paid" class="rounded border-gray-300"/>
                        <span class="text-sm font-medium text-gray-700">Paid</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="requires_attachment" class="rounded border-gray-300"/>
                        <span class="text-sm font-medium text-gray-700">Requires Attachment</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="solo_parent_only" class="rounded border-gray-300"/>
                        <span class="text-sm font-medium text-gray-700">Solo Parent Only</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="requires_admin_approval" class="rounded border-gray-300"/>
                        <span class="text-sm font-medium text-gray-700">Admin Approval</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300"/>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>

                <div class="md:col-span-3 flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-2">
                    <button type="button" @click="open = false" class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">Cancel</button>
                    <button type="submit" class="brand-btn-primary text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95 flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">Save Leave Type</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Saving…
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Leave Type List</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    {{ $leaveTypes->count() }} types
                </span>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search leave types…"
                    class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg transition-all w-56"/>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Label</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Annual Days</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Flags</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($leaveTypes as $lt)
                        <tr class="brand-row-hover transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full"
                                      style="background-color:#fef8e7;color:#b45309;border:1px solid #fde68a;">
                                    {{ $lt->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $lt->label }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $lt->annual_days ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $lt->reset_type) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if($lt->is_paid) <span class="px-1.5 py-0.5 text-xs rounded bg-blue-100 text-blue-700">Paid</span> @endif
                                    @if($lt->requires_attachment) <span class="px-1.5 py-0.5 text-xs rounded bg-yellow-100 text-yellow-700">Attachment</span> @endif
                                    @if($lt->solo_parent_only) <span class="px-1.5 py-0.5 text-xs rounded bg-pink-100 text-pink-700">Solo Parent</span> @endif
                                    @if($lt->requires_admin_approval) <span class="px-1.5 py-0.5 text-xs rounded bg-purple-100 text-purple-700">Admin Approval</span> @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($lt->is_active)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-semibold">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-500 font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $lt->id }})" class="brand-edit-btn rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm transition-colors">Edit</button>
                                <button wire:click="confirmDelete({{ $lt->id }})" class="text-red-500 hover:text-red-700 font-semibold transition-colors">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">{{ $search ? 'No leave types match your search.' : 'No leave types found.' }}</p>
                                    <p class="text-xs mt-1">{{ $search ? 'Try a different keyword.' : 'Click "Add New Leave Type" above to get started.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
            <div class="fixed inset-0 bg-gray-500/75" wire:click="$set('confirmingDeletion', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl sm:w-full sm:max-w-lg">
                    <div class="px-6 pt-6 pb-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full bg-red-100">
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Delete Leave Type</h3>
                                <p class="mt-1.5 text-sm text-gray-500">Are you sure? This cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                        <button wire:click="delete" class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-500 active:scale-95">Delete Permanently</button>
                        <button wire:click="$set('confirmingDeletion', false)" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- EDIT MODAL --}}
    @if($isEditing)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75" wire:click="$set('isEditing', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl sm:w-full sm:max-w-2xl" style="border-top: 4px solid #027c8b;">
                    <form wire:submit.prevent="update">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                                <div class="p-2 rounded-lg mr-3 brand-bg-teal-light">
                                    <svg class="w-5 h-5 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Update Leave Type</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Code *</label>
                                    <input type="text" wire:model="code" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 uppercase"/>
                                    @error('code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Label *</label>
                                    <input type="text" wire:model="label" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('label') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Annual Days</label>
                                    <input type="number" step="0.5" wire:model="annual_days" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('annual_days') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reset Type *</label>
                                    <select wire:model="reset_type" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                                        <option value="anniversary">Anniversary</option>
                                        <option value="january">January</option>
                                        <option value="birth_month">Birth Month</option>
                                        <option value="none">None</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-3 items-center">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="is_paid" class="rounded border-gray-300"/>
                                        <span class="text-sm font-medium text-gray-700">Paid</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="requires_attachment" class="rounded border-gray-300"/>
                                        <span class="text-sm font-medium text-gray-700">Requires Attachment</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="solo_parent_only" class="rounded border-gray-300"/>
                                        <span class="text-sm font-medium text-gray-700">Solo Parent Only</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="requires_admin_approval" class="rounded border-gray-300"/>
                                        <span class="text-sm font-medium text-gray-700">Admin Approval</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300"/>
                                        <span class="text-sm font-medium text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit" class="brand-btn-teal inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold shadow-sm active:scale-95 flex items-center gap-2">
                                <span wire:loading.remove wire:target="update">Save Changes</span>
                                <span wire:loading wire:target="update" class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Saving…
                                </span>
                            </button>
                            <button type="button" wire:click="$set('isEditing', false)" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- TOAST --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5">
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full brand-bg-teal-light">
                    <svg class="w-5 h-5 brand-text-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Success!</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </div>
            <div class="h-1" style="background-color:#f0b626; animation: shrink 4s linear forwards;"></div>
        </div>
    @endif

</div>
