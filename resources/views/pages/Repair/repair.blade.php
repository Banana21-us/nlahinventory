
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-50 rounded-lg">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Repair Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">{{ $userDepartmentName }} - Assets Needing Repair</h1>
                <p class="text-xs text-gray-500 mt-1">Assets with maintenance status or poor/fair condition</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-3"><div class="flex justify-between"><div><p class="text-xs text-gray-500">Needs Repair</p><p class="text-2xl font-bold text-amber-600">{{ $assets->where('status', 'maintenance')->count() }}</p></div><div class="p-2 bg-amber-100 rounded-full"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div></div></div>
        <div class="bg-white rounded-lg shadow-md p-3"><div class="flex justify-between"><div><p class="text-xs text-gray-500">Poor Condition</p><p class="text-2xl font-bold text-red-600">{{ $assets->where('condition_status', 'poor')->count() }}</p></div><div class="p-2 bg-red-100 rounded-full"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div></div></div>
        <div class="bg-white rounded-lg shadow-md p-3"><div class="flex justify-between"><div><p class="text-xs text-gray-500">Fair Condition</p><p class="text-2xl font-bold text-yellow-600">{{ $assets->where('condition_status', 'fair')->count() }}</p></div><div class="p-2 bg-yellow-100 rounded-full"><svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div></div></div>
    </div>

    <div class="mb-6"><div class="relative max-w-md"><svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assets..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg"></div></div>

    @if($assets && $assets->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($assets as $asset)
            @php $needRepair = $asset->status === 'maintenance'; @endphp
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden cursor-pointer hover:shadow-lg transition {{ $needRepair ? 'border-l-4 border-l-red-500' : ($asset->condition_status === 'poor' ? 'border-l-4 border-l-amber-500' : 'border-l-4 border-l-yellow-500') }}" wire:click="showDetails({{ $asset->id }})">
                <div class="h-32 bg-gray-100">
                    @if($asset->image && Storage::disk('public')->exists($asset->image))
                        <img src="{{ Storage::url($asset->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    @endif
                    <div class="absolute top-2 right-2"><span class="text-white text-xs font-bold px-2 py-1 rounded-full {{ $needRepair ? 'bg-red-600' : ($asset->condition_status === 'poor' ? 'bg-amber-600' : 'bg-yellow-600') }}">{{ $needRepair ? 'Needs Repair' : ucfirst($asset->condition_status) }}</span></div>
                </div>
                <div class="p-3"><p class="font-bold text-gray-900 text-sm truncate">{{ $asset->asset_code }}</p><p class="font-semibold text-gray-800 text-xs truncate">{{ $asset->name }}</p></div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $assets->links() }}</div>
    @else
    <div class="bg-white rounded-xl shadow-md p-12 text-center"><div class="flex flex-col items-center"><div class="p-4 bg-green-100 rounded-full mb-4"><svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><h3 class="text-lg font-bold text-gray-700 mb-2">No Assets Need Repair</h3><p class="text-sm text-gray-500">All assets are in good condition.</p></div></div>
    @endif

    <!-- Details Modal -->
    @if($showDetailsModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeDetailsModal()">
        <div class="fixed inset-0 bg-gray-500/75"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl sm:w-full sm:max-w-2xl">
                <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4 rounded-t-xl"><div class="flex justify-between"><h3 class="text-lg font-bold text-white">Asset Details</h3><button wire:click="closeDetailsModal" class="text-white/80">✕</button></div></div>
                <div class="px-6 py-5"><div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/2 bg-gray-50 rounded-lg p-4 flex justify-center">@if($selectedAsset->image && Storage::disk('public')->exists($selectedAsset->image))<img src="{{ Storage::url($selectedAsset->image) }}" class="max-w-full h-48 object-cover rounded-lg">@else<div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center"><svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>@endif</div>
                    <div class="md:w-1/2 space-y-3"><div><p class="text-xs font-bold text-gray-400">Asset Name</p><p class="font-semibold">{{ $selectedAsset->name }}</p></div><div><p class="text-xs font-bold text-gray-400">Asset Code</p><p>{{ $selectedAsset->asset_code }}</p></div><div class="grid grid-cols-2 gap-3"><div><p class="text-xs font-bold text-gray-400">Brand</p><p>{{ $selectedAsset->brand ?: 'N/A' }}</p></div><div><p class="text-xs font-bold text-gray-400">Model</p><p>{{ $selectedAsset->model ?: 'N/A' }}</p></div></div><div><p class="text-xs font-bold text-gray-400">Serial Number</p><p>{{ $selectedAsset->serial_number ?: 'N/A' }}</p></div><div class="grid grid-cols-2 gap-3"><div><p class="text-xs font-bold text-gray-400">Status</p><span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">{{ ucfirst(str_replace('_', ' ', $selectedAsset->status)) }}</span></div><div><p class="text-xs font-bold text-gray-400">Condition</p><span class="px-2 py-1 rounded-full text-xs font-semibold {{ $selectedAsset->condition_status === 'poor' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($selectedAsset->condition_status) }}</span></div></div><div><p class="text-xs font-bold text-gray-400">Location</p><p>{{ $selectedAsset->location->name ?? 'N/A' }}</p></div><div><p class="text-xs font-bold text-gray-400">Notes</p><p class="text-sm">{{ $selectedAsset->notes ?: 'No notes.' }}</p></div></div>
                </div></div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl"><button wire:click="closeDetailsModal" class="px-4 py-2 text-sm font-semibold text-gray-700">Close</button><button wire:click="openRepairModal({{ $selectedAsset->id }})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold">Complete Repair</button></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Repair Modal -->
    @if($showRepairModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" @click.away="$wire.closeRepairModal()">
        <div class="fixed inset-0 bg-gray-500/75"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl sm:w-full sm:max-w-lg">
                <form wire:submit.prevent="completeRepair">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-xl"><div class="flex justify-between"><h3 class="text-lg font-bold text-white">Complete Repair</h3><button type="button" wire:click="closeRepairModal" class="text-white/80">✕</button></div></div>
                    <div class="px-6 py-5 space-y-4">
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Issue / Problem *</label><textarea wire:model="repair_issue" rows="3" class="w-full rounded-md border p-2" placeholder="Describe what was repaired..."></textarea>@error('repair_issue')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror</div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Repair Cost</label><div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span><input type="number" step="0.01" wire:model="repair_cost" class="w-full rounded-md border p-2 pl-8" placeholder="0.00"></div></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Additional Notes</label><textarea wire:model="repair_notes" rows="2" class="w-full rounded-md border p-2" placeholder="Any additional information..."></textarea></div>
                        <div class="bg-blue-50 rounded-lg p-3 text-xs text-blue-700">After completing the repair: Asset status will change to "In Use" and condition to "Good"</div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl"><button type="button" wire:click="closeRepairModal" class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button><button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold">Complete Repair</button></div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if(session()->has('message'))<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm">{{ session('message') }}</div>@endif
</div>