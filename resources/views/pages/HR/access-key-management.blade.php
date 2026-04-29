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

    {{-- Header --}}
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
    </div>

    @if(session('message'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

    {{-- Collapsible Form --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-6"
         x-data="{ open: @entangle('showForm') }">

        <button
            @click="open = !open"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none"
        >
            <div class="flex items-center">
                <div class="p-2 rounded-lg mr-4 brand-bg-primary-light">
                    <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Access Key Entry</h2>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="open ? 'Minimize' : 'Add New Access Key'"></span>
        </button>

        <div
            x-show="open"
            x-collapse
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="border-t border-gray-100 bg-gray-50/30"
        >
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Key Name *</label>
                    <input type="text" wire:model="name" placeholder="e.g. HR Access"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Redirect Route --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Login Redirect</label>
                    <select wire:model="redirect_to"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                        <option value="">— None (waiting area) —</option>
                        @foreach($availableRoutes as $route => $label)
                            <option value="{{ $route }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Where the user lands immediately after login.</p>
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Description</label>
                    <input type="text" wire:model="description" placeholder="Short note about what this key grants"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                </div>

                {{-- Permissions --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">
                        Module Access <span class="text-gray-400 font-normal normal-case">(check all this key can open)</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        @foreach($availablePermissions as $slug => $label)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $slug }}"
                                    class="rounded border-gray-300 text-[#015581] focus:ring-[#015581]"/>
                                <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Super Access Toggle --}}
                <div class="md:col-span-2">
                    <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border-2 transition-colors
                        {{ $is_super ? 'border-amber-400 bg-amber-50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                        <input type="checkbox" wire:model="is_super"
                            class="mt-0.5 rounded border-gray-300 text-amber-500 focus:ring-amber-400"/>
                        <div>
                            <span class="text-sm font-bold text-gray-800 flex items-center gap-1">
                                Super Access
                                @if($is_super)
                                    <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2 py-0.5 rounded ml-1">ACTIVE</span>
                                @endif
                            </span>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Bypasses ALL gate checks. This user can open every module regardless of what's checked above.
                                Only assign to fully trusted HR/admin accounts.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end items-center gap-3">
                <button type="button" @click="open = false"
                    class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                    Cancel
                </button>
                @if($isEditing)
                    <button wire:click="update"
                        class="brand-btn-primary text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95">
                        Save Changes
                    </button>
                @else
                    <button wire:click="save"
                        class="brand-btn-primary text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95">
                        Create Key
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Delete Confirm --}}
    @if($confirmingDeletion)
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
    @endif

    {{-- Search --}}
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search access keys…"
            class="brand-focus block w-full md:w-72 rounded-lg border border-gray-300 shadow-sm sm:text-sm p-2"/>
    </div>

    {{-- Cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($keys as $key)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-5 py-4 flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-gray-900">{{ $key->name }}</h3>
                        @if($key->is_super)
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">Super</span>
                        @endif
                    </div>
                    @if($key->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $key->description }}</p>
                    @endif
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <button wire:click="edit({{ $key->id }})"
                        class="text-xs font-semibold brand-text-primary hover:underline">Edit</button>
                    <button wire:click="confirmDelete({{ $key->id }})"
                        class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
            </div>

            <div class="px-5 pb-4 space-y-3">
                {{-- Redirect --}}
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        Login →
                        @if($key->redirect_to)
                            <span class="font-mono text-[#015581] font-semibold">{{ $availableRoutes[$key->redirect_to] ?? $key->redirect_to }}</span>
                        @else
                            <span class="text-gray-400 italic">Waiting Area</span>
                        @endif
                    </span>
                </div>

                {{-- Permissions --}}
                <div class="flex flex-wrap gap-1.5">
                    @if($key->is_super)
                        <span class="text-[10px] font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">All modules (super)</span>
                    @elseif(!empty($key->permissions))
                        @foreach($key->permissions as $perm)
                            <span class="text-[10px] font-semibold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">
                                {{ $availablePermissions[$perm] ?? $perm }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-[10px] text-gray-400 italic">No modules granted</span>
                    @endif
                </div>

                {{-- User count --}}
                <p class="text-xs text-gray-400">
                    <span class="font-semibold text-gray-600">{{ $key->users_count }}</span>
                    {{ Str::plural('user', $key->users_count) }} assigned
                </p>
            </div>
        </div>
        @empty
            <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-10 text-center text-sm text-gray-400">
                No access keys found. Create one to start assigning system access.
            </div>
        @endforelse
    </div>
</div>
