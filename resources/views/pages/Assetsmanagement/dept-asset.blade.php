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
    .brand-btn-outline { background-color:transparent;color:#015581;border:1px solid #015581;transition:all .15s ease; }
    .brand-btn-outline:hover { background-color:#e6f0f7; }
    .search-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(2,124,139,.2);border-color:#027c8b; }
    .brand-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(1,85,129,.2);border-color:#015581; }
    @keyframes shrink { from { width:100% } to { width:0% } }
    
    /* Disposed asset styling */
    .asset-disposed {
        position: relative;
        overflow: hidden;
        opacity: 0.75;
        background: repeating-linear-gradient(
            45deg,
            rgba(0,0,0,0.02) 0px,
            rgba(0,0,0,0.02) 20px,
            rgba(0,0,0,0.04) 20px,
            rgba(0,0,0,0.04) 40px
        );
    }
    .asset-disposed::before {
        content: "DISPOSED";
        position: absolute;
        top: 12px;
        right: -25px;
        background-color: #6b21a8;
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 4px 35px;
        transform: rotate(45deg);
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        letter-spacing: 1px;
        white-space: nowrap;
    }
    
    /* Lost asset styling */
    .asset-lost {
        position: relative;
        overflow: hidden;
        opacity: 0.75;
        background: repeating-linear-gradient(
            45deg,
            rgba(0,0,0,0.02) 0px,
            rgba(0,0,0,0.02) 20px,
            rgba(0,0,0,0.04) 20px,
            rgba(0,0,0,0.04) 40px
        );
    }
    .asset-lost::before {
        content: "LOST";
        position: absolute;
        top: 12px;
        right: -25px;
        background-color: #6b7280;
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 4px 35px;
        transform: rotate(45deg);
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        letter-spacing: 1px;
        white-space: nowrap;
    }
    
    /* Out of Service styling */
    .asset-outofservice {
        position: relative;
        overflow: hidden;
        opacity: 0.85;
        background: repeating-linear-gradient(
            45deg,
            rgba(0,0,0,0.01) 0px,
            rgba(0,0,0,0.01) 20px,
            rgba(0,0,0,0.02) 20px,
            rgba(0,0,0,0.02) 40px
        );
    }
    .asset-outofservice::before {
        content: "🔧 IN REPAIR";
        position: absolute;
        top: 12px;
        right: -25px;
        background-color: #0284c7;
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 4px 35px;
        transform: rotate(45deg);
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        letter-spacing: 1px;
        white-space: nowrap;
    }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Department Assets</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">{{ $userDepartmentName }}</h1>
                <p class="text-xs text-gray-500 mt-1">Click on any asset to view details, request repair, or report lost</p>
            </div>
        </div>
    </div>

    {{-- SEARCH BAR --}}
    <div class="mb-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assets by code, name, brand…"
                   class="search-focus pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-80 bg-white">
        </div>
    </div>

    {{-- ASSETS GRID --}}
    @if($assets && $assets->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($assets as $asset)
        @php
            $needRepair = $asset->status === 'maintenance';
            $isDisposed = $asset->status === 'disposed';
            $isLost = $asset->status === 'lost';
            $isOutOfService = $asset->status === 'out_of_service';
            $isUnavailable = $isDisposed || $isLost;
            
            $statusMap = [
                'available'     => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'Available'],
                'in_use'        => ['bg'=>'#dbeafe','color'=>'#1e40af','border'=>'#93c5fd','label'=>'In Use'],
                'out_of_service'=> ['bg'=>'#dbeafe','color'=>'#1e40af','border'=>'#93c5fd','label'=>'In Repair'],
                'maintenance'   => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Maintenance'],
                'disposed'      => ['bg'=>'#f3e8ff','color'=>'#6b21a8','border'=>'#d8b4fe','label'=>'Disposed'],
                'lost'          => ['bg'=>'#f3f4f6','color'=>'#6b7280','border'=>'#d1d5db','label'=>'Lost'],
            ];
            $sc = $statusMap[$asset->status] ?? $statusMap['active'];
            $condMap = [
                'excellent' => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'Excellent'],
                'good'      => ['bg'=>'#dbeafe','color'=>'#1e40af','border'=>'#93c5fd','label'=>'Good'],
                'fair'      => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Fair'],
                'poor'      => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Poor'],
                'critical'  => ['bg'=>'#fed7aa','color'=>'#9a3412','border'=>'#fdba74','label'=>'Critical'],
                'damaged'   => ['bg'=>'#ffedd5','color'=>'#c2410c','border'=>'#fed7aa','label'=>'Damaged'],
            ];
            $cc = $condMap[$asset->condition_status] ?? $condMap['good'];
        @endphp
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden cursor-pointer
                    hover:shadow-md transition-shadow
                    {{ $isDisposed ? 'asset-disposed' : ($isLost ? 'asset-lost' : ($isOutOfService ? 'asset-outofservice' : '')) }}"
             wire:click="{{ !$isUnavailable ? 'showDetails(' . $asset->id . ')' : '' }}">
            <div class="h-40 bg-gray-50 flex items-center justify-center overflow-hidden {{ $isUnavailable ? 'opacity-50' : '' }}">
                @if($asset->image && Storage::disk('public')->exists($asset->image))
                    <img src="{{ Storage::url($asset->image) }}" class="w-full h-full object-contain block p-1">
                @else
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                @endif
            </div>
            <div class="p-3">
                <p class="font-bold text-gray-900 text-sm truncate">{{ $asset->asset_code }}</p>
                <p class="text-xs text-gray-600 truncate mb-2">{{ $asset->name }}</p>
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                          style="background-color:{{ $cc['bg'] }};color:{{ $cc['color'] }};border:1px solid {{ $cc['border'] }}">
                        {{ $cc['label'] }}
                    </span>
                    @if(!$isUnavailable && $asset->status !== 'maintenance')
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                              style="background-color:#dbeafe;color:#1e40af;border:1px solid #93c5fd">
                            Active
                        </span>
                    @elseif(!$isUnavailable && $asset->status === 'maintenance')
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                              style="background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5">
                            Repair Requested
                        </span>
                    @elseif($isOutOfService)
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                              style="background-color:#dbeafe;color:#1e40af;border:1px solid #93c5fd">
                            🔧 In Repair
                        </span>
                    @elseif($isDisposed)
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                              style="background-color:#f3e8ff;color:#6b21a8;border:1px solid #d8b4fe">
                            Disposed
                        </span>
                    @elseif($isLost)
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                              style="background-color:#fef3c7;color:#92400e;border:1px solid #fcd34d">
                            Lost
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $assets->links() }}</div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="flex flex-col items-center text-gray-400">
            <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="text-sm font-medium">No assets in your department.</p>
            <p class="text-xs mt-1">{{ $search ? 'Try adjusting your search.' : 'Contact IT or admin to assign assets.' }}</p>
        </div>
    </div>
    @endif

    {{-- DETAILS MODAL --}}
    @if($showDetailsModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeDetailsModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg brand-bg-primary-light">
                            <svg class="w-4 h-4 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Asset Details</h3>
                    </div>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="md:w-5/12 bg-gray-50 rounded-xl flex items-center justify-center min-h-40">
                            @if($selectedAsset->image && Storage::disk('public')->exists($selectedAsset->image))
                                <img src="{{ Storage::url($selectedAsset->image) }}" class="max-w-full h-48 object-cover rounded-lg">
                            @else
                                <div class="flex flex-col items-center text-gray-300 py-8">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs mt-2">No image</p>
                                </div>
                            @endif
                        </div>
                        <div class="md:w-7/12 space-y-3 text-sm">
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Name</p><p class="font-semibold text-gray-900">{{ $selectedAsset->name }}</p></div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Asset Code</p><p class="font-mono text-gray-700">{{ $selectedAsset->asset_code }}</p></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Brand</p><p class="text-gray-700">{{ $selectedAsset->brand ?: '—' }}</p></div>
                                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Model</p><p class="text-gray-700">{{ $selectedAsset->model ?: '—' }}</p></div>
                            </div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Serial No.</p><p class="font-mono text-gray-700">{{ $selectedAsset->serial_number ?: '—' }}</p></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Status</p>
                                    @php
                                        $statusColor = match($selectedAsset->status) {
                                            'in_use' => 'bg-blue-100 text-blue-700',
                                            'maintenance' => 'bg-red-100 text-red-700',
                                            'active' => 'bg-green-100 text-green-700',
                                            'retired' => 'bg-purple-100 text-purple-700',
                                            'disposed' => 'bg-purple-100 text-purple-700',
                                            'lost' => 'bg-yellow-100 text-yellow-700',
                                            'out_of_service' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        @if($selectedAsset->status === 'out_of_service') 🔧 Out of Service (In Repair) @else {{ ucfirst(str_replace('_', ' ', $selectedAsset->status)) }} @endif
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Condition</p>
                                    @php
                                        $condColor = match($selectedAsset->condition_status) {
                                            'excellent' => 'bg-emerald-100 text-emerald-700',
                                            'good' => 'bg-green-100 text-green-700',
                                            'fair' => 'bg-yellow-100 text-yellow-700',
                                            'poor' => 'bg-orange-100 text-orange-700',
                                            'critical' => 'bg-red-100 text-red-700',
                                            'damaged' => 'bg-rose-100 text-rose-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $condColor }}">
                                        {{ ucfirst($selectedAsset->condition_status) }}
                                    </span>
                                </div>
                            </div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Location</p><p class="text-gray-700">{{ $selectedAsset->location?->name ?? '—' }}</p></div>
                             <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Maintenance Dept</p><p class="text-gray-700">{{ $selectedAsset->maintenanceDepartment->name ?? '—' }}</p></div>
                           <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Notes</p><p class="text-xs text-gray-600">{{ $selectedAsset->notes ?: 'No notes.' }}</p></div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button wire:click="closeDetailsModal"
                        class="px-4 py-2 text-sm font-semibold text-gray-700">Close</button>
                    @if($selectedAsset->status !== 'maintenance' && $selectedAsset->status !== 'disposed' && $selectedAsset->status !== 'lost' && $selectedAsset->status !== 'out_of_service')
                        <button wire:click="openRepairRequestModal({{ $selectedAsset->id }})"
                            class="brand-btn-teal text-sm font-bold px-5 py-2 rounded-lg active:scale-95">
                            Request Repair
                        </button>
                        <button wire:click="openLostReportModal({{ $selectedAsset->id }})"
                            class="text-sm font-bold px-5 py-2 rounded-lg active:scale-95"
                            style="background-color:#fef3c7;color:#92400e;border:1px solid #fcd34d;">
                            Report Lost
                        </button>
                    @elseif($selectedAsset->status === 'out_of_service')
                        <div class="text-sm text-blue-600 bg-blue-50 px-3 py-2 rounded-lg">
                            <p class="font-semibold">🔧 Asset is currently being repaired</p>
                            <p class="text-xs mt-1">This asset is in the repair queue. Once repaired, it will be available again.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- REPAIR REQUEST MODAL --}}
    @if($showRepairRequestModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeRepairRequestModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
                <form wire:submit.prevent="submitRepairRequest">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between brand-bg-teal-light">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-teal">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">Request Repair</h3>
                        </div>
                        <button type="button" wire:click="closeRepairRequestModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Issue / Problem Description *</label>
                            <textarea wire:model="repair_notes" rows="4" 
                                      placeholder="Describe what's wrong with the asset..."
                                      class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm"></textarea>
                            @error('repair_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <p class="text-xs text-yellow-700">⚠️ Once submitted, this asset will be marked as <strong>"Needs Repair"</strong> and sent to the maintenance department.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeRepairRequestModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit" 
                            class="brand-btn-teal text-sm font-bold px-5 py-2 rounded-lg active:scale-95">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- LOST REPORT MODAL --}}
    @if($showLostReportModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeLostReportModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
                <form wire:submit.prevent="submitLostReport">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between" style="background-color:#fef3c7;">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg" style="background-color:#f0b626;">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">Report Lost Asset</h3>
                        </div>
                        <button type="button" wire:click="closeLostReportModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason / Details *</label>
                            <textarea wire:model="lost_notes" rows="4" 
                                      placeholder="Please provide details about how/when the asset was lost..."
                                      class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm"></textarea>
                            @error('lost_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg">
                            <p class="text-xs text-red-700">⚠️ Warning: Once reported as lost, this asset will be marked as <strong>"LOST"</strong> and will no longer be available for assignment.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeLostReportModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit" 
                            class="text-sm font-bold px-5 py-2 rounded-lg active:scale-95"
                            style="background-color:#d97706;color:white;">
                            Confirm Lost
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- TOAST MESSAGES --}}
    @if(session()->has('message'))
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
                <p class="text-sm font-semibold text-gray-900">Success</p>
                <p class="mt-0.5 text-sm text-gray-500">{{ session('message') }}</p>
            </div>
        </div>
        <div class="h-1 brand-bg-accent" style="animation:shrink 4s linear forwards;"></div>
    </div>
    @endif

    @if(session()->has('error'))
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
                <p class="mt-0.5 text-sm text-gray-500">{{ session('error') }}</p>
            </div>
        </div>
        <div class="h-1 bg-red-400" style="animation:shrink 5s linear forwards;"></div>
    </div>
    @endif
</div>