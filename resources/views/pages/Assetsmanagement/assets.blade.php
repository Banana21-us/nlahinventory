@php use Illuminate\Support\Facades\Storage; @endphp

<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
    .brand-bg-teal           { background-color: #027c8b; }
    .brand-bg-teal-light     { background-color: #e6f4f5; }
    .brand-text-teal         { color: #027c8b; }
    .brand-bg-accent         { background-color: #f0b626; }
    .brand-btn-primary { background-color:#015581;color:#fff;transition:background-color .15s ease; }
    .brand-btn-primary:hover { background-color:#01406a; }
    .brand-btn-teal { background-color:#027c8b;color:#fff;transition:background-color .15s ease; }
    .brand-btn-teal:hover { background-color:#016070; }
    .brand-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(1,85,129,.2);border-color:#015581; }
    .search-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(2,124,139,.2);border-color:#027c8b; }
    .brand-row-hover:hover { background-color:#f0f7fc; }
    .brand-edit-btn { background-color:#e6f0f7;color:#015581; }
    .brand-edit-btn:hover { background-color:#cde0ef; }
    @keyframes shrink { from { width:100% } to { width:0% } }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7.5 12 3l9 4.5M4.5 10.5V16L12 21l7.5-5v-5.5M12 12l9-4.5M12 12 3 7.5"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Assets Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Assets Inventory</h1>
            </div>
        </div>
        <button wire:click="openForm"
            class="brand-btn-primary text-sm font-bold py-2 px-5 rounded-lg shadow-md active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Asset
        </button>
    </div>

    {{-- ADD / EDIT FORM --}}
    @if($showForm)
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between brand-bg-primary-light">
            <div class="flex items-center gap-3">
                <div class="p-2 brand-bg-primary rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7 12 3 4 7m16 0v10l-8 4-8-4V7m16 0-8 4m-8-4 8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800">{{ $isEditing ? 'Edit Asset' : 'Register New Asset' }}</h3>
                    <p class="text-xs text-gray-500">Fill in the asset details below</p>
                </div>
            </div>
            <button type="button" wire:click="cancelForm"
                class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'save' }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Asset Code *</label>
                    <input type="text" wire:model="asset_code" placeholder="ASSET-001"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                    @error('asset_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Asset Name *</label>
                    <input type="text" wire:model="name" placeholder="Dell XPS 15"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Category</label>
                    <input type="text" wire:model="category" placeholder="Electronics, Furniture…"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Brand</label>
                    <input type="text" wire:model="brand" placeholder="Dell, HP, Epson…"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Model</label>
                    <input type="text" wire:model="model" placeholder="XPS 15, ProBook 450…"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Serial Number</label>
                    <input type="text" wire:model="serial_number" placeholder="SN-123456"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                    @error('serial_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Purchase Date</label>
                    <input type="date" wire:model="purchase_date"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Purchase Cost</label>
                    <input type="number" step="0.01" wire:model="purchase_cost" placeholder="0.00"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                    <select wire:model="status"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2 bg-white">
                        <option value="active">Active (Available)</option>
                        <option value="in_use">In Use (Assigned)</option>
                        <option value="maintenance">Under Maintenance</option>
                        <option value="retired">Retired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Condition</label>
                    <select wire:model="condition_status"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2 bg-white">
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
                <div class="md:col-span-2 xl:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Notes</label>
                    <textarea wire:model="notes" rows="3" placeholder="Additional notes about this asset…"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2"></textarea>
                </div>
                <div class="md:col-span-2 xl:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Asset Image</label>
                    <div class="mt-1 flex items-center gap-4">
                        @if($existing_image)
                            <div class="relative">
                                <img src="{{ Storage::url($existing_image) }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                <button type="button" wire:click="$set('existing_image', null)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @elseif($image)
                            <div class="relative">
                                <img src="{{ $image->temporaryUrl() }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                <button type="button" wire:click="$set('image', null)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" wire:model="image" accept="image/*"
                                class="block w-full text-sm text-gray-500
                                       file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                       file:text-sm file:font-bold file:brand-btn-primary file:cursor-pointer
                                       bg-white border border-gray-200 rounded-md p-1">
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF — max 2 MB</p>
                        </div>
                    </div>
                    @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-4">
                <button type="button" wire:click="cancelForm"
                    class="text-sm font-semibold text-gray-600 hover:text-gray-800 px-4 py-2">Cancel</button>
                <button type="submit"
                    class="brand-btn-primary text-sm font-bold py-2 px-6 rounded-lg shadow-md active:scale-95">
                    {{ $isEditing ? 'Update Asset' : 'Register Asset' }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- TABLE PANEL --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        {{-- Toolbar --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">All Assets</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    {{ $assets->total() }} {{ Str::plural('record', $assets->total()) }}
                </span>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assets…"
                    class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg w-52"/>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Asset Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Brand / Model</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Condition</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Assignment</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($assets as $asset)
                        @php
                            $isAssigned = $asset->department_id && $asset->location_id;
                            $statusMap = [
                                'active'      => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'Active'],
                                'in_use'      => ['bg'=>'#dbeafe','color'=>'#1e40af','border'=>'#93c5fd','label'=>'In Use'],
                                'maintenance' => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Maintenance'],
                                'retired'     => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Retired'],
                            ];
                            $condMap = [
                                'good' => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'Good'],
                                'fair' => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Fair'],
                                'poor' => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Poor'],
                            ];
                            $sc = $statusMap[$asset->status] ?? $statusMap['active'];
                            $cc = $condMap[$asset->condition_status] ?? $condMap['good'];
                        @endphp
                        <tr class="brand-row-hover transition-colors">
                            <td class="px-4 py-3">
                                @if($asset->image && Storage::disk('public')->exists($asset->image))
                                    <img src="{{ Storage::url($asset->image) }}" class="w-11 h-11 object-cover rounded-lg border border-gray-200">
                                @else
                                    <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-bold text-gray-900">{{ $asset->asset_code }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $asset->created_at?->format('M d, Y') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-800">{{ $asset->name }}</p>
                                @if($asset->serial_number)
                                    <p class="text-xs text-gray-400">SN: {{ $asset->serial_number }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $asset->brand ?: '—' }}
                                @if($asset->model) <span class="text-gray-400">({{ $asset->model }})</span> @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="background-color:{{ $sc['bg'] }};color:{{ $sc['color'] }};border:1px solid {{ $sc['border'] }}">
                                    {{ $sc['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="background-color:{{ $cc['bg'] }};color:{{ $cc['color'] }};border:1px solid {{ $cc['border'] }}">
                                    {{ $cc['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($isAssigned)
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="background-color:#f3e8ff;color:#6b21a8;border:1px solid #d8b4fe">Assigned</span>
                                @else
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="background-color:#f3f4f6;color:#6b7280;border:1px solid #d1d5db">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $asset->id }})"
                                        class="brand-edit-btn text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">Edit</button>
                                    <button wire:click="confirmDelete({{ $asset->id }})"
                                        class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M3 7.5 12 3l9 4.5M4.5 10.5V16L12 21l7.5-5v-5.5M12 12l9-4.5M12 12 3 7.5"/>
                                    </svg>
                                    <p class="text-sm font-medium">No asset records found.</p>
                                    <p class="text-xs mt-1">{{ $search ? 'Try adjusting your search.' : 'Click "Add New Asset" to get started.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $assets->links() }}
        </div>
    </div>

    {{-- DELETE CONFIRM MODAL --}}
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="cancelDelete"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-11 h-11 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Delete Asset Record</h3>
                            <p class="mt-1.5 text-sm text-gray-500">Are you sure you want to remove this asset? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" wire:click="cancelDelete"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="button" wire:click="delete"
                        class="px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 active:scale-95">Delete Permanently</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- TOAST --}}
    @if($toastMessage)
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Done</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ $toastMessage }}</p>
                </div>
            </div>
            <div class="h-1" style="background-color:#f0b626;animation:shrink 4s linear forwards;"></div>
        </div>
    @endif

    @if($toastError)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-red-200">
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full bg-red-50">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Error</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ $toastError }}</p>
                </div>
            </div>
            <div class="h-1 bg-red-400" style="animation:shrink 5s linear forwards;"></div>
        </div>
    @endif

</div>
