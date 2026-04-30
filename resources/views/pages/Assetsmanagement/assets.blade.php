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
                <p class="text-xs text-gray-500 mt-1">Click on any row to view full asset details</p>
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
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Lifespan (Years)</label>
                    <input type="number" wire:model="lifespan_years" min="1" max="50" step="1" placeholder="e.g., 5"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2">
                    @error('lifespan_years') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End of Life</label>
                    <input type="text" wire:model="end_of_life" readonly
                        class="block w-full rounded-md border border-gray-200 bg-gray-50 text-gray-500 sm:text-sm p-2">
                    <p class="text-xs text-gray-400 mt-1">Auto-calculated</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                    <select wire:model="status"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2 bg-white">
                        <option value="available">Available</option>
                        <option value="in_use">In Use</option>
                        <option value="out_of_service">Out of Service (In Repair)</option>
                        <option value="maintenance">Under Maintenance</option>
                        <option value="disposed">Disposed</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Condition</label>
                    <select wire:model="condition_status"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2 bg-white">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                        <option value="critical">Critical</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                        Maintenance Department <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="maintenance_department_id"
                        class="brand-focus block w-full rounded-md border border-gray-300 sm:text-sm p-2 bg-white">
                        <option value="">-- Select Maintenance Department --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }} ({{ $department->code }})</option>
                        @endforeach
                    </select>
                    @error('maintenance_department_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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

        {{-- Table - Clickable Rows --}}
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
                            $isOutOfService = $asset->status === 'out_of_service';
                            $isMaintenance = $asset->status === 'maintenance';
                            $statusMap = [
                                'available'     => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'Available'],
                                'in_use'        => ['bg'=>'#dbeafe','color'=>'#1e40af','border'=>'#93c5fd','label'=>'In Use'],
                                'out_of_service'=> ['bg'=>'#fed7aa','color'=>'#9a3412','border'=>'#fdba74','label'=>'In Repair'],
                                'maintenance'   => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Maintenance'],
                                'disposed'      => ['bg'=>'#fecaca','color'=>'#7f1d1d','border'=>'#ef4444','label'=>'Disposed'],
                                'lost'          => ['bg'=>'#f3f4f6','color'=>'#6b7280','border'=>'#d1d5db','label'=>'Lost'],
                            ];
                            $condMap = [
                                'excellent' => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'Excellent'],
                                'good'      => ['bg'=>'#2fc963','color'=>'#234d31','border'=>'#277340','label'=>'Good'],
                                'fair'      => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Fair'],
                                'poor'      => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Poor'],
                                'critical'  => ['bg'=>'#fed7aa','color'=>'#9a3412','border'=>'#fdba74','label'=>'Critical'],
                                'damaged'   => ['bg'=>'#fecaca','color'=>'#7f1d1d','border'=>'#ef4444','label'=>'Damaged'],
                            ];
                            $sc = $statusMap[$asset->status] ?? $statusMap['available'];
                            $cc = $condMap[$asset->condition_status] ?? $condMap['good'];
                            $isDisposed = $asset->status === 'disposed';
                        @endphp
                        <tr wire:key="asset-{{ $asset->id }}" class="brand-row-hover transition-colors cursor-pointer {{ $isDisposed ? 'opacity-75' : '' }} {{ $isOutOfService ? 'bg-orange-50' : '' }}" wire:click="showDetails({{ $asset->id }})">
                            <td class="px-4 py-3" wire:click.stop>
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
                                    @if($isOutOfService)
                                         {{ $sc['label'] }}
                                    @elseif($isMaintenance)
                                        ⚠️ {{ $sc['label'] }}
                                    @else
                                        {{ $sc['label'] }}
                                    @endif
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
                            <td class="px-4 py-3 text-right" wire:click.stop>
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$isDisposed)
                                        <button wire:click="edit({{ $asset->id }})"
                                            class="brand-edit-btn text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">Edit</button>
                                    @else
                                        <button disabled
                                            class="bg-gray-100 text-gray-400 text-xs font-semibold px-3 py-1.5 rounded-lg cursor-not-allowed"
                                            title="Disposed assets cannot be edited">
                                            Edit
                                        </button>
                                    @endif
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

    {{-- ASSET DETAILS MODAL --}}
    @if($showDetailsModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeDetailsModal()">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex h-350 items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl w-100 max-w-[30%]">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-white/20 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7.5 12 3l9 4.5M4.5 10.5V16L12 21l7.5-5v-5.5M12 12l9-4.5M12 12 3 7.5"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-md font-bold">Asset Details</h3>
                                <p class="text-xs text-sky-200">{{ $selectedAsset->asset_code }}</p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeDetailsModal" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-5 py-4 max-h-[40vh] overflow-y-auto">
                    <div class="flex flex-col md:flex-row gap-5">
                        <!-- Left Column - Image -->
                        <div class="md:w-2/5 bg-gray-50 rounded-lg p-3 flex items-center justify-center">
                            @if($selectedAsset->image && Storage::disk('public')->exists($selectedAsset->image))
                                <img src="{{ Storage::url($selectedAsset->image) }}" class="w-full max-w-[30px] h-5 object-cover rounded-lg shadow-sm">
                            @else
                                <div class="w-full max-w-[30px] h-5 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column - Details -->
                        <div class="md:w-3/5 space-y-2 ml-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Asset Name</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $selectedAsset->name }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Asset Code</p>
                                    <p class="text-sm font-mono text-gray-700">{{ $selectedAsset->asset_code }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Brand</p>
                                    <p class="text-sm text-gray-700">{{ $selectedAsset->brand ?: 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Model</p>
                                    <p class="text-sm text-gray-700">{{ $selectedAsset->model ?: 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Category</p>
                                    <p class="text-sm text-gray-700">{{ $selectedAsset->category ?: 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Serial #</p>
                                    <p class="text-sm font-mono text-gray-700">{{ $selectedAsset->serial_number ?: 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Purchase Date</p>
                                    <p class="text-sm text-gray-700">{{ $selectedAsset->purchase_date ? date('M d, Y', strtotime($selectedAsset->purchase_date)) : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Purchase Cost</p>
                                    <p class="text-sm text-gray-700">₱{{ number_format($selectedAsset->purchase_cost, 2) }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Lifespan</p>
                                    <p class="text-sm text-gray-700">{{ $selectedAsset->lifespan_years ? $selectedAsset->lifespan_years . ' years' : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">End of Life</p>
                                    <p class="text-sm text-gray-700">{{ $selectedAsset->end_of_life ? date('M d, Y', strtotime($selectedAsset->end_of_life)) : 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Status</p>
                                    @php
                                        $statusColor = match($selectedAsset->status) {
                                            'available' => 'bg-emerald-100 text-emerald-700',
                                            'in_use' => 'bg-blue-100 text-blue-700',
                                            'out_of_service' => 'bg-orange-100 text-orange-700',
                                            'maintenance' => 'bg-amber-100 text-amber-700',
                                            'disposed' => 'bg-red-100 text-red-700',
                                            'lost' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        @if($selectedAsset->status === 'out_of_service')
                                            🔧 Out of Service (In Repair)
                                        @elseif($selectedAsset->status === 'maintenance')
                                            ⚠️ Maintenance
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $selectedAsset->status)) }}
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Condition</p>
                                    @php
                                        $conditionColor = match($selectedAsset->condition_status) {
                                            'excellent' => 'bg-emerald-100 text-emerald-700',
                                            'good' => 'bg-green-100 text-green-700',
                                            'fair' => 'bg-yellow-100 text-yellow-700',
                                            'poor' => 'bg-orange-100 text-orange-700',
                                            'critical' => 'bg-red-100 text-red-700',
                                            'damaged' => 'bg-rose-100 text-rose-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $conditionColor }}">{{ ucfirst($selectedAsset->condition_status) }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Maintenance Dept</p>
                                    <p class="text-sm text-gray-700">{{ $selectedAsset->maintenanceDepartment->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Assignment</p>
                                    @php $isAssigned = $selectedAsset->department_id && $selectedAsset->location_id; @endphp
                                    @if($isAssigned)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Assigned</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Unassigned</span>
                                    @endif
                                </div>
                            </div>

                            @if($isAssigned)
                                <div class="bg-gray-50 rounded-lg p-2">
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Assigned To</p>
                                    <div class="grid grid-cols-2 gap-2 mt-1">
                                        <div>
                                            <span class="text-xs text-gray-500">Dept:</span>
                                            <span class="text-xs font-medium text-gray-800">{{ $selectedAsset->department->name ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-xs text-gray-500">Location:</span>
                                            <span class="text-xs font-medium text-gray-800">{{ $selectedAsset->location->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($selectedAsset->notes)
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Notes</p>
                                    <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded-lg">{{ $selectedAsset->notes }}</p>
                                </div>
                            @endif

                            <div class="pt-1 text-[10px] text-gray-400 border-t">
                                <p>Created: {{ $selectedAsset->created_at ? date('M d, Y h:i A', strtotime($selectedAsset->created_at)) : 'N/A' }}</p>
                                <p>Updated: {{ $selectedAsset->updated_at ? date('M d, Y h:i A', strtotime($selectedAsset->updated_at)) : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-5 py-3 flex justify-end gap-2 rounded-b-xl">
                    @php $isDisposed = $selectedAsset->status === 'disposed'; @endphp
                    @if(!$isDisposed)
                        <button type="button" wire:click="edit({{ $selectedAsset->id }})"
                            class="inline-flex justify-center rounded-lg bg-sky-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-sky-700 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                    @else
                        <button type="button" disabled
                            class="inline-flex justify-center rounded-lg bg-gray-400 px-3 py-1.5 text-xs font-bold text-white shadow-sm cursor-not-allowed">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit (Disabled)
                        </button>
                    @endif
                    <button type="button" wire:click="closeDetailsModal" 
                        class="inline-flex justify-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

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

    {{-- TOAST MESSAGES --}}
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
                    <p class="text-sm font-semibold text-gray-900">Success!</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ $toastMessage }}</p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 brand-bg-accent" style="animation:shrink 4s linear forwards;"></div>
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
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-red-400" style="animation:shrink 5s linear forwards;"></div>
        </div>
    @endif
</div>