@php use Illuminate\Support\Facades\Storage; @endphp

<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-50 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Assets Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Assign Assets</h1>
                <p class="text-xs text-gray-500 mt-1">Assign unassigned assets or transfer assigned ones</p>
            </div>
        </div>
    </div>

    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assets..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <div class="flex gap-2">
            <button wire:click="$set('filter', 'all')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">All</button>
            <button wire:click="$set('filter', 'unassigned')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'unassigned' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Unassigned</button>
            <button wire:click="$set('filter', 'assigned')" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'assigned' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Assigned</button>
        </div>
    </div>

    @if($allAssets->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($allAssets as $asset)
            @php $isAssigned = $asset->department_id && $asset->location_id; @endphp
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden {{ $isAssigned ? 'border-l-4 border-l-green-500' : 'border-l-4 border-l-amber-500' }}">
                <div class="h-32 bg-gray-100">
                    @if($asset->image && Storage::disk('public')->exists($asset->image))
                        <img src="{{ Storage::url($asset->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2">
                        <span class="text-white text-xs font-bold px-2 py-1 rounded-full shadow-md {{ $isAssigned ? 'bg-green-600' : 'bg-amber-600' }}">{{ $isAssigned ? 'Assigned' : 'Unassigned' }}</span>
                    </div>
                </div>
                <div class="p-3">
                    <p class="font-bold text-gray-900 text-sm truncate">{{ $asset->asset_code }}</p>
                    <p class="font-semibold text-gray-800 text-xs truncate">{{ $asset->name }}</p>
                    <div class="mt-2 pt-2 border-t border-gray-100">
                        @if(!$isAssigned)
                            <button wire:click="openAssignModal({{ $asset->id }})" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-1.5 rounded-lg">Assign</button>
                        @else
                            <div class="flex gap-1">
                                <button wire:click="openTransferModal({{ $asset->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-1.5 rounded-lg">Transfer</button>
                                <button wire:click="confirmUnassign({{ $asset->id }})" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-1.5 rounded-lg">Unassign</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12 text-center">
        <div class="flex flex-col items-center">
            <div class="p-4 bg-gray-100 rounded-full mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">No Assets Found</h3>
            <p class="text-sm text-gray-500">No assets available. Please register assets first.</p>
        </div>
    </div>
    @endif

    <!-- Assign Modal -->
    @if($showModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeModal()">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl sm:w-full sm:max-w-lg">
                <form wire:submit.prevent="assignAsset">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <div class="flex justify-between">
                            <h3 class="text-lg font-bold text-white">Assign Asset</h3>
                            <button type="button" wire:click="closeModal" class="text-white/80 hover:text-white">✕</button>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="bg-indigo-50 rounded-lg p-3">
                            <p class="font-bold text-gray-900">{{ $selectedAsset->asset_code }}</p>
                            <p class="text-sm text-gray-700">{{ $selectedAsset->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Department *</label>
                            <select wire:model="to_department_id" class="w-full rounded-md border p-2">
                                <option value="">Select department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('to_department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Location *</label>
                            <select wire:model="to_location_id" class="w-full rounded-md border p-2">
                                <option value="">Select location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('to_location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Remarks</label>
                            <textarea wire:model="remarks" rows="2" class="w-full rounded-md border p-2"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold">Confirm Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Transfer Modal -->
    @if($showTransferModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeTransferModal()">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl sm:w-full sm:max-w-lg">
                <form wire:submit.prevent="transferAsset">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex justify-between">
                            <h3 class="text-lg font-bold text-white">Transfer Asset</h3>
                            <button type="button" wire:click="closeTransferModal" class="text-white/80 hover:text-white">✕</button>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="font-bold text-gray-900">{{ $selectedAsset->asset_code }}</p>
                            <p class="text-sm text-gray-700">{{ $selectedAsset->name }}</p>
                            <div class="mt-1 text-xs">
                                <div>Current Dept: <span class="font-medium">{{ $selectedAsset->department->name ?? 'N/A' }}</span></div>
                                <div>Current Location: <span class="font-medium">{{ $selectedAsset->location->name ?? 'N/A' }}</span></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">New Department *</label>
                            <select wire:model="to_department_id" class="w-full rounded-md border p-2">
                                <option value="">Select department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('to_department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">New Location *</label>
                            <select wire:model="to_location_id" class="w-full rounded-md border p-2">
                                <option value="">Select location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('to_location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Transfer Reason</label>
                            <textarea wire:model="remarks" rows="2" class="w-full rounded-md border p-2"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeTransferModal" class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold">Confirm Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Unassign Confirmation Modal -->
    @if($showUnassignModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeUnassignModal()">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl sm:w-full sm:max-w-lg">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-11 h-11 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Unassign Asset</h3>
                            <p class="mt-1.5 text-sm text-gray-500">Are you sure you want to unassign <strong>{{ $selectedAsset->asset_code }} - {{ $selectedAsset->name }}</strong>?</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" wire:click="closeUnassignModal" class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                    <button type="button" wire:click="unassignAsset" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold">Yes, Unassign</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (session()->has('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-5 right-5 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm">
        {{ session('message') }}
    </div>
    @endif
</div>