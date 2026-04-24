<div class="max-w-7xl mx-auto py-8 px-4">
    <!-- Success Message -->
    @if($showSuccess)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-5 right-5 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ $successMessage }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-50 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Assets Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Transfer & Assign Assets</h1>
                <p class="text-xs text-gray-500 mt-1">Assign unassigned assets to departments and locations</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Available Assets List -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Available Assets (Unassigned)</h3>
                <p class="text-xs text-gray-500 mt-1">Assets waiting to be assigned to a department</p>
                <div class="mt-3 relative">
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Search by asset code, name, or serial number..." 
                        class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    />
                </div>
            </div>
            <div class="divide-y max-h-[500px] overflow-y-auto">
                @forelse($availableAssets as $asset)
                <div 
                    class="p-4 hover:bg-gray-50 cursor-pointer transition-all {{ $selectedAsset && $selectedAsset->id == $asset->id ? 'bg-indigo-50 border-l-4 border-l-indigo-500' : '' }}" 
                    wire:click="selectAsset({{ $asset->id }})"
                >
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-bold text-gray-900">{{ $asset->asset_code }}</div>
                            <div class="text-sm font-semibold text-gray-700">{{ $asset->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="inline-block mr-3">Brand: {{ $asset->brand ?: 'N/A' }}</span>
                                <span class="inline-block">Model: {{ $asset->model ?: 'N/A' }}</span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                SN: {{ $asset->serial_number ?? 'N/A' }} | Condition: {{ ucfirst($asset->condition_status) }}
                            </div>
                        </div>
                        @if($selectedAsset && $selectedAsset->id == $asset->id)
                            <div class="text-indigo-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm font-medium">No available assets found.</p>
                    <p class="text-xs mt-1">Please register assets first in "Assets Inventory" page.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Assignment Form -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Assign Asset</h3>
                <p class="text-xs text-gray-500 mt-1">Fill in the details to assign the selected asset</p>
            </div>
            
            <div class="p-6">
                @if($selectedAsset)
                <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 mb-2">Selected Asset</p>
                    <p class="font-bold text-gray-900 text-lg">{{ $selectedAsset->asset_code }}</p>
                    <p class="text-gray-700">{{ $selectedAsset->name }}</p>
                    <div class="grid grid-cols-2 gap-2 mt-2 text-sm">
                        <div><span class="text-gray-500">Brand:</span> {{ $selectedAsset->brand ?: 'N/A' }}</div>
                        <div><span class="text-gray-500">Model:</span> {{ $selectedAsset->model ?: 'N/A' }}</div>
                        <div><span class="text-gray-500">Serial #:</span> {{ $selectedAsset->serial_number ?: 'N/A' }}</div>
                        <div><span class="text-gray-500">Condition:</span> {{ ucfirst($selectedAsset->condition_status) }}</div>
                    </div>
                </div>
                @endif

                <form wire:submit.prevent="assignAsset">
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                            Department ID <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            wire:model="to_department_id" 
                            placeholder="Enter department ID (e.g., 1, 2, 3...)" 
                            class="w-full rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2"
                            {{ !$selectedAsset ? 'disabled' : '' }}
                        >
                        @error('to_department_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="to_location_id" 
                            class="w-full rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2 bg-white"
                            {{ !$selectedAsset ? 'disabled' : '' }}
                        >
                            <option value="">Select location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('to_location_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                            Remarks / Notes
                        </label>
                        <textarea 
                            wire:model="remarks" 
                            rows="3" 
                            placeholder="Optional: Add any notes about this assignment (e.g., purpose, condition notes, etc.)"
                            class="w-full rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 p-2"
                            {{ !$selectedAsset ? 'disabled' : '' }}
                        ></textarea>
                        @error('remarks') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button 
                            type="submit" 
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition-all active:scale-95 flex items-center justify-center gap-2"
                            {{ !$selectedAsset ? 'disabled' : '' }}
                        >
                            <span wire:loading.remove wire:target="assignAsset">Assign Asset</span>
                            <span wire:loading wire:target="assignAsset" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Processing...
                            </span>
                        </button>
                        @if($selectedAsset)
                        <button 
                            type="button" 
                            wire:click="resetForm" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Clear
                        </button>
                        @endif
                    </div>
                </form>

                @if(!$selectedAsset && $availableAssets->count() > 0)
                <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-xs text-yellow-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Select an asset from the left panel to begin assignment.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recently Assigned Assets -->
    @if($assignedAssets->count() > 0)
    <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="text-lg font-bold text-gray-800">Recently Assigned Assets</h3>
            <p class="text-xs text-gray-500 mt-1">Latest 10 assigned assets</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Asset Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Department ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($assignedAssets as $asset)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $asset->asset_code }}</td>
                        <td class="px-6 py-4">{{ $asset->name }}</td>
                        <td class="px-6 py-4">{{ $asset->department_id }}</td>
                        <td class="px-6 py-4">{{ $asset->location->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $asset->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>