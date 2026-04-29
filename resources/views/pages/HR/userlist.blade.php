<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }

    .brand-bg-accent         { background-color: #f0b626; }
    .brand-bg-accent-light   { background-color: #fef8e7; }
    .brand-text-accent       { color: #f0b626; }

    .brand-bg-teal           { background-color: #027c8b; }
    .brand-bg-teal-light     { background-color: #e6f4f5; }
    .brand-text-teal         { color: #027c8b; }

    .brand-btn-primary {
        background-color: #015581;
        color: #ffffff;
        transition: background-color 0.15s ease;
    }
    .brand-btn-primary:hover { background-color: #01406a; }

    .brand-btn-teal {
        background-color: #027c8b;
        color: #ffffff;
        transition: background-color 0.15s ease;
    }
    .brand-btn-teal:hover { background-color: #016070; }

    .brand-focus:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(1, 85, 129, 0.2);
        border-color: #015581;
    }
    .search-focus:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 124, 139, 0.2);
        border-color: #027c8b;
    }
    .brand-row-hover:hover   { background-color: #f0f7fc; }
    .brand-edit-btn          { background-color: #e6f0f7; color: #015581; }
    .brand-edit-btn:hover    { background-color: #cde0ef; }

    @keyframes shrink { from { width: 100% } to { width: 0% } }
</style>

    {{-- ═══════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">System</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">User List</h1>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ADD USER COLLAPSIBLE FORM
    ═══════════════════════════════════════════ --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden"
         x-data="{ open: @entangle('showForm') }">

        {{-- Toggle Button --}}
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
                <h2 class="text-lg font-bold text-gray-800">User Entry</h2>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="open ? 'Minimize' : 'Add New User'"></span>
        </button>

        {{-- Collapsible Body --}}
        <div
            x-show="open"
            x-collapse
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-6 border-t border-gray-100 bg-gray-50/30"
        >
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employee Number *</label>
                    <input type="text" wire:model="employee_number" placeholder="e.g. EMP-0001"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('employee_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Full Name *</label>
                    <input type="text" wire:model="name" placeholder="Juan dela Cruz"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Username *</label>
                    <input type="text" wire:model="username" placeholder="juan.delacruz"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('username') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Email *</label>
                    <input type="email" wire:model="email" placeholder="juan@example.com"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Password *</label>
                    <input type="password" wire:model="password"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Confirm Password *</label>
                    <input type="password" wire:model="password_confirmation"
                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                </div>

                <div class="md:col-span-3 flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-2">
                    <button type="button" @click="open = false"
                        class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="brand-btn-primary text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95 flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">Save User</span>
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

    {{-- ═══════════════════════════════════════════
         SEARCH + COUNT BAR
    ═══════════════════════════════════════════ --}}
    <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm px-4 py-3 mb-4">
        <div class="flex items-center gap-2 mb-2">
            <h3 class="text-sm font-bold text-gray-700">User List</h3>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full font-medium">
                {{ $users->count() }} {{ Str::plural('user', $users->count()) }}
            </span>
        </div>
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="Search users…"
                   class="search-focus w-full pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg"/>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MOBILE CARD LIST  (hidden on md+)
    ═══════════════════════════════════════════ --}}
    <div class="md:hidden space-y-3">
        @forelse($users as $user)
            @php $position = $user->employmentDetail?->position; @endphp
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden {{ $user->is_active ? '' : 'opacity-60' }}">

                {{-- Card Header: avatar + name + status toggle --}}
                <div class="px-4 pt-4 pb-3 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0 brand-bg-primary">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</p>
                            {{-- Active toggle --}}
                            <div wire:ignore>
                                <button
                                    x-data="{ on: {{ $user->is_active ? 'true' : 'false' }} }"
                                    x-on:click="on = !on; $wire.toggleActive({{ $user->id }})"
                                    :class="on ? 'bg-green-500' : 'bg-red-400'"
                                    class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-300 border-2 border-black focus:outline-none">
                                    <span :style="on ? 'transform:translateX(1.5rem)' : 'transform:translateX(0.25rem)'"
                                          style="transition:transform 300ms ease-in-out;"
                                          class="inline-block h-4 w-4 rounded-full bg-white shadow"></span>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 truncate">{{ $user->username }}</p>
                    </div>
                </div>

                {{-- Details grid --}}
                <div class="px-4 pb-3 grid grid-cols-2 gap-x-4 gap-y-2">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wide text-gray-400">Employee #</span>
                        <span class="text-sm font-mono font-semibold text-gray-700">{{ $user->employee_number ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wide text-gray-400">Position</span>
                        @if($position)
                            <span class="text-xs font-semibold truncate block" style="color:#015581;">{{ $position }}</span>
                        @else
                            <span class="text-xs text-gray-400 italic">Not assigned</span>
                        @endif
                    </div>
                    <div class="col-span-2">
                        <span class="block text-[10px] font-bold uppercase tracking-wide text-gray-400">Email</span>
                        <span class="text-xs text-gray-600 truncate block">{{ $user->email }}</span>
                    </div>
                </div>

                {{-- Footer: access key + actions --}}
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center gap-2">
                    <select
                        @change="$wire.confirmAccessKeyChange({{ $user->id }}, parseInt($event.target.value) || null)"
                        class="brand-focus min-w-0 flex-1 text-xs border border-gray-200 rounded-lg px-2 py-1.5 bg-white text-gray-700">
                        <option value="">— No Key —</option>
                        @foreach($accessKeys as $key)
                            <option value="{{ $key->id }}" {{ $user->access_key_id == $key->id ? 'selected' : '' }}>
                                {{ $key->name }}
                            </option>
                        @endforeach
                    </select>
                    <button wire:click="edit({{ $user->id }})"
                            class="brand-edit-btn rounded-lg px-3 py-1.5 text-xs font-bold shadow-sm shrink-0">
                        Edit
                    </button>
                    <button wire:click="confirmDelete({{ $user->id }})"
                            class="text-red-500 text-xs font-bold shrink-0 px-2 py-1.5">
                        Del
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 px-6 py-12 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm font-medium">{{ $search ? 'No users match your search.' : 'No users found in the system.' }}</p>
                <p class="text-xs mt-1">{{ $search ? 'Try a different keyword.' : 'Click "Add New User" above to get started.' }}</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════
         DESKTOP TABLE  (hidden below md)
    ═══════════════════════════════════════════ --}}
    <div class="hidden md:block bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Access Key</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($users as $user)
                        @php $position = $user->employmentDetail?->position; @endphp
                        <tr class="brand-row-hover transition-colors {{ $user->is_active ? '' : 'opacity-60' }}">
                            <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-600">{{ $user->employee_number }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 brand-bg-primary">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($position)
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="background-color:#e6f0f7;color:#015581;border:1px solid #bfdbee;">
                                        {{ $position }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div wire:ignore>
                                    <button
                                        x-data="{ on: {{ $user->is_active ? 'true' : 'false' }} }"
                                        x-on:click="on = !on; $wire.toggleActive({{ $user->id }})"
                                        :title="on ? 'Click to deactivate' : 'Click to activate'"
                                        :class="on ? 'bg-green-600' : 'bg-red-600'"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 ease-in-out border-2 border-black focus:outline-none focus:ring-4 focus:ring-yellow-400">
                                        <span :style="on ? 'transform: translateX(1.5rem)' : 'transform: translateX(0.25rem)'"
                                              style="transition: transform 300ms ease-in-out;"
                                              class="inline-block h-4 w-4 rounded-full bg-white shadow"></span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <select
                                    @change="$wire.confirmAccessKeyChange({{ $user->id }}, parseInt($event.target.value) || null)"
                                    class="brand-focus text-xs border border-gray-300 rounded-md px-2 py-1 bg-white text-gray-700 cursor-pointer max-w-[130px]">
                                    <option value="">— No Key —</option>
                                    @foreach($accessKeys as $key)
                                        <option value="{{ $key->id }}" {{ $user->access_key_id == $key->id ? 'selected' : '' }}>
                                            {{ $key->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $user->id }})"
                                    class="brand-edit-btn rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm transition-colors">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $user->id }})"
                                    class="text-red-500 hover:text-red-700 font-semibold transition-colors">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium">{{ $search ? 'No users match your search.' : 'No users found in the system.' }}</p>
                                    <p class="text-xs mt-1">{{ $search ? 'Try a different keyword.' : 'Click "Add New User" above to get started.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         DELETE CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('confirmingDeletion', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl sm:w-full sm:max-w-lg">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full bg-red-100">
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Delete User</h3>
                                <p class="mt-1.5 text-sm text-gray-500">
                                    Are you sure you want to remove this user? This record will be permanently deleted. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                        <button type="button" wire:click="delete"
                            class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-bold text-white shadow-sm bg-red-600 hover:bg-red-500 transition-colors active:scale-95">
                            Delete Permanently
                        </button>
                        <button type="button" wire:click="$set('confirmingDeletion', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         ACCESS KEY CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    @if($confirmingAccessKey)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('confirmingAccessKey', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl sm:w-full sm:max-w-lg">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full brand-bg-primary-light">
                                <svg class="w-6 h-6 brand-text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Update Access Key</h3>
                                <p class="mt-1.5 text-sm text-gray-500">
                                    Are you sure you want to change this user's access key? This will affect which modules the user can access.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                        <button type="button" wire:click="applyAccessKeyChange"
                            class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-bold text-white shadow-sm brand-btn-primary transition-colors active:scale-95">
                            Confirm Change
                        </button>
                        <button type="button" wire:click="$set('confirmingAccessKey', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         EDIT MODAL
    ═══════════════════════════════════════════ --}}
    @if($isEditing)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('isEditing', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl sm:w-full sm:max-w-2xl"
                     style="border-top: 4px solid #027c8b;">
                    <form wire:submit.prevent="update">
                        <div class="bg-white px-6 pt-6 pb-4">
                            <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                                <div class="p-2 rounded-lg mr-3 brand-bg-teal-light">
                                    <svg class="w-5 h-5 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Update User Information</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employee Number *</label>
                                    <input type="text" wire:model="employee_number"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('employee_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Full Name *</label>
                                    <input type="text" wire:model="name"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Email *</label>
                                    <input type="email" wire:model="email"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                                        New Password
                                        <span class="text-gray-400 normal-case font-normal">(leave blank to keep current)</span>
                                    </label>
                                    <input type="password" wire:model="password"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Confirm New Password</label>
                                    <input type="password" wire:model="password_confirmation"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit"
                                class="brand-btn-teal inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold shadow-sm active:scale-95 flex items-center gap-2">
                                <span wire:loading.remove wire:target="update">Save Changes</span>
                                <span wire:loading wire:target="update" class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Saving…
                                </span>
                            </button>
                            <button type="button" wire:click="$set('isEditing', false)"
                                class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         TOAST NOTIFICATION
    ═══════════════════════════════════════════ --}}
    @if (session()->has('message'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5"
        >
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
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </div>
            {{-- Gold accent progress bar --}}
            <div class="h-1" style="background-color:#f0b626; animation: shrink 4s linear forwards;"></div>
        </div>
    @endif

</div>