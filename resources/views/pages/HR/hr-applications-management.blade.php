<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
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
    .tab-active   { border-bottom: 3px solid #015581; color: #015581; font-weight: 700; }
    .tab-inactive { border-bottom: 2px solid transparent; color: #6b7280; font-weight: 500; }
    @keyframes shrink { from { width: 100% } to { width: 0% } }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-teal-light">
                <svg class="w-6 h-6 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">HR</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Applications Management</h1>
            </div>
        </div>
        <div class="text-xs text-gray-400 bg-white px-3 py-1 rounded-full shadow-sm">
            HR · {{ auth()->user()->name }}
        </div>
    </div>

    {{-- TABS --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200"
         x-data="{ activeTab: 'overtime' }">

        {{-- Tab Headers --}}
        <div class="border-b border-gray-200 bg-gray-50 px-6 pt-2 flex gap-6">
            <button @click="activeTab = 'overtime'"
                    :class="activeTab === 'overtime' ? 'tab-active' : 'tab-inactive'"
                    class="py-3 text-sm transition-all duration-200 focus:outline-none">
                ⏱ Overtime
                <span class="ml-1.5 bg-gray-200 text-gray-700 text-xs px-1.5 py-0.5 rounded-full">{{ $overtimes->count() }}</span>
            </button>
            <button @click="activeTab = 'payoff'"
                    :class="activeTab === 'payoff' ? 'tab-active' : 'tab-inactive'"
                    class="py-3 text-sm transition-all duration-200 focus:outline-none">
                💰 Pay-off
                <span class="ml-1.5 bg-gray-200 text-gray-700 text-xs px-1.5 py-0.5 rounded-full">{{ $payoffs->count() }}</span>
            </button>
            <button @click="activeTab = 'mine'"
                    :class="activeTab === 'mine' ? 'tab-active' : 'tab-inactive'"
                    class="py-3 text-sm transition-all duration-200 focus:outline-none">
                👤 My Applications
                <span class="ml-1.5 bg-gray-200 text-gray-700 text-xs px-1.5 py-0.5 rounded-full">{{ $myOvertimes->count() + $myPayoffs->count() }}</span>
            </button>
        </div>

        {{-- Shared filter toolbar (hidden on My Applications tab) --}}
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center"
             x-show="activeTab !== 'mine'">
            <div></div>
            <div class="flex items-center gap-3">
                <select wire:model.live="filterStatus" class="search-focus text-sm bg-white border border-gray-200 rounded-lg py-2 px-3">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search employee…"
                        class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg w-48"/>
                </div>
            </div>
        </div>

        {{-- ── OVERTIME TAB ── --}}
        <div x-show="activeTab === 'overtime'" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Start</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">End</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($overtimes as $app)
                            @php
                                $badge = match($app->status) {
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default    => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <tr class="brand-row-hover transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $app->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->user->employee_number }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $app->type === 'overtime' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                                        {{ $app->type === 'overtime' ? 'Overtime' : 'On Call' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $app->start_datetime->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $app->end_datetime->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold brand-text-teal">{{ $app->hours }}h</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $app->reason ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $badge }} capitalize">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($app->status === 'pending')
                                            <button wire:click="approveOvertime({{ $app->id }})"
                                                class="text-green-600 hover:text-green-800 font-semibold transition-colors text-xs">Approve</button>
                                            <button wire:click="rejectOvertime({{ $app->id }})"
                                                class="text-red-500 hover:text-red-700 font-semibold transition-colors text-xs">Reject</button>
                                        @endif
                                        <button wire:click="editOvertime({{ $app->id }})" class="brand-edit-btn rounded-md px-2.5 py-1.5 text-xs font-semibold shadow-sm transition-colors">Edit</button>
                                        <button wire:click="deleteOvertime({{ $app->id }})" class="text-red-500 hover:text-red-700 font-semibold transition-colors text-xs">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-14 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium">No overtime applications found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── PAY-OFF TAB ── --}}
        <div x-show="activeTab === 'payoff'" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Start</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">End</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($payoffs as $app)
                            @php
                                $badge = match($app->status) {
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default    => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <tr class="brand-row-hover transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $app->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->user->employee_number }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $app->start_datetime->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $app->end_datetime->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold brand-text-teal">{{ $app->hours }}h</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $app->reason ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $badge }} capitalize">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($app->status === 'pending')
                                            <button wire:click="approvePayoff({{ $app->id }})"
                                                class="text-green-600 hover:text-green-800 font-semibold transition-colors text-xs">Approve</button>
                                            <button wire:click="rejectPayoff({{ $app->id }})"
                                                class="text-red-500 hover:text-red-700 font-semibold transition-colors text-xs">Reject</button>
                                        @endif
                                        <button wire:click="editPayoff({{ $app->id }})" class="brand-edit-btn rounded-md px-2.5 py-1.5 text-xs font-semibold shadow-sm transition-colors">Edit</button>
                                        <button wire:click="deletePayoff({{ $app->id }})" class="text-red-500 hover:text-red-700 font-semibold transition-colors text-xs">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium">No pay-off applications found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── MY APPLICATIONS TAB ── --}}
        <div x-show="activeTab === 'mine'" x-cloak class="p-6 space-y-6">

            {{-- My Overtime Form --}}
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden"
                 x-data="{ open: @entangle('myOtForm') }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-100 transition-colors focus:outline-none">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-lg brand-bg-primary-light">
                            <svg class="w-4 h-4 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">⏱ New Overtime Application</span>
                    </div>
                    <span class="text-xs font-medium brand-text-primary" x-text="open ? 'Minimize' : 'Apply'"></span>
                </button>
                <div x-show="open" x-collapse class="px-5 pb-5 border-t border-gray-200 pt-4 bg-white">
                    <form wire:submit.prevent="saveMyOvertime">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Type *</label>
                                <select wire:model="myOt_type" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                                    <option value="overtime">Overtime</option>
                                    <option value="on_call">On Call</option>
                                </select>
                                @error('myOt_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start *</label>
                                <input type="datetime-local" wire:model.live="myOt_start_datetime"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                @error('myOt_start_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End *</label>
                                <input type="datetime-local" wire:model.live="myOt_end_datetime"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                @error('myOt_end_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hours</label>
                                <input type="number" step="0.01" wire:model="myOt_hours" readonly placeholder="Auto-computed"
                                    class="block w-full rounded-md border border-gray-200 bg-gray-100 text-gray-700 shadow-sm sm:text-sm p-2 cursor-not-allowed"/>
                                @error('myOt_hours') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason</label>
                                <input type="text" wire:model="myOt_reason" placeholder="Brief reason"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="brand-btn-teal text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- My Pay-off Form --}}
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden"
                 x-data="{ open: @entangle('myPoForm') }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-100 transition-colors focus:outline-none">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-lg brand-bg-teal-light">
                            <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">💰 New Pay-off Application</span>
                    </div>
                    <span class="text-xs font-medium brand-text-teal" x-text="open ? 'Minimize' : 'Apply'"></span>
                </button>
                <div x-show="open" x-collapse class="px-5 pb-5 border-t border-gray-200 pt-4 bg-white">
                    <form wire:submit.prevent="saveMyPayoff">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start *</label>
                                <input type="datetime-local" wire:model.live="myPo_start_datetime"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                @error('myPo_start_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End *</label>
                                <input type="datetime-local" wire:model.live="myPo_end_datetime"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                @error('myPo_end_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hours</label>
                                <input type="number" step="0.01" wire:model="myPo_hours" readonly placeholder="Auto-computed"
                                    class="block w-full rounded-md border border-gray-200 bg-gray-100 text-gray-700 shadow-sm sm:text-sm p-2 cursor-not-allowed"/>
                                @error('myPo_hours') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason</label>
                                <input type="text" wire:model="myPo_reason" placeholder="Brief reason"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="brand-btn-teal text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- My Overtime Records --}}
            @if($myOvertimes->count())
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">My Overtime Records</h4>
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <table class="w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Start</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">End</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hours</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($myOvertimes as $app)
                                @php $badge = match($app->status) { 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', default => 'bg-yellow-100 text-yellow-700' }; @endphp
                                <tr class="brand-row-hover">
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $app->type === 'overtime' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                                            {{ $app->type === 'overtime' ? 'Overtime' : 'On Call' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $app->start_datetime->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $app->end_datetime->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-3 font-semibold brand-text-primary">{{ $app->hours }}h</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $badge }} capitalize">{{ $app->status }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($app->status === 'pending')
                                            <button wire:click="deleteMyOvertime({{ $app->id }})" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- My Pay-off Records --}}
            @if($myPayoffs->count())
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">My Pay-off Records</h4>
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <table class="w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Start</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">End</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hours</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Reason</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($myPayoffs as $app)
                                @php $badge = match($app->status) { 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', default => 'bg-yellow-100 text-yellow-700' }; @endphp
                                <tr class="brand-row-hover">
                                    <td class="px-4 py-3 text-gray-700">{{ $app->start_datetime->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $app->end_datetime->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-3 font-semibold brand-text-teal">{{ $app->hours }}h</td>
                                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $app->reason ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $badge }} capitalize">{{ $app->status }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($app->status === 'pending')
                                            <button wire:click="deleteMyPayoff({{ $app->id }})" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($myOvertimes->isEmpty() && $myPayoffs->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm font-medium">No applications yet. Use the forms above to apply.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- OVERTIME EDIT MODAL --}}
    @if($editingOvertime)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75" wire:click="$set('editingOvertime', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl sm:w-full sm:max-w-2xl" style="border-top: 4px solid #027c8b;">
                    <form wire:submit.prevent="updateOvertime">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                                <div class="p-2 rounded-lg mr-3 brand-bg-teal-light">
                                    <svg class="w-5 h-5 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Edit Overtime Application</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employee *</label>
                                    <select wire:model="ot_user_id" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                                        <option value="">— Select Employee —</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('ot_user_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Type *</label>
                                    <select wire:model="ot_type" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                                        <option value="overtime">Overtime</option>
                                        <option value="on_call">On Call</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                                    <select wire:model="ot_status" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hours</label>
                                    <input type="number" step="0.01" wire:model="ot_hours" readonly
                                        class="block w-full rounded-md border border-gray-200 bg-gray-100 text-gray-700 shadow-sm sm:text-sm p-2 cursor-not-allowed"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start Date & Time *</label>
                                    <input type="datetime-local" wire:model.live="ot_start_datetime" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('ot_start_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End Date & Time *</label>
                                    <input type="datetime-local" wire:model.live="ot_end_datetime" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('ot_end_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason</label>
                                    <input type="text" wire:model="ot_reason" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit" class="brand-btn-teal inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold shadow-sm active:scale-95">Save Changes</button>
                            <button type="button" wire:click="$set('editingOvertime', false)" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- PAY-OFF EDIT MODAL --}}
    @if($editingPayoff)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75" wire:click="$set('editingPayoff', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl sm:w-full sm:max-w-2xl" style="border-top: 4px solid #015581;">
                    <form wire:submit.prevent="updatePayoff">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center mb-5 pb-4 border-b border-gray-100">
                                <div class="p-2 rounded-lg mr-3 brand-bg-primary-light">
                                    <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Edit Pay-off Application</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employee *</label>
                                    <select wire:model="po_user_id" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                                        <option value="">— Select Employee —</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('po_user_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                                    <select wire:model="po_status" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start Date & Time *</label>
                                    <input type="datetime-local" wire:model.live="po_start_datetime" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('po_start_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End Date & Time *</label>
                                    <input type="datetime-local" wire:model.live="po_end_datetime" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('po_end_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hours</label>
                                    <input type="number" step="0.01" wire:model="po_hours" readonly
                                        class="block w-full rounded-md border border-gray-200 bg-gray-100 text-gray-700 shadow-sm sm:text-sm p-2 cursor-not-allowed"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason</label>
                                    <input type="text" wire:model="po_reason" class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit" class="brand-btn-teal inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold shadow-sm active:scale-95">Save Changes</button>
                            <button type="button" wire:click="$set('editingPayoff', false)" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
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
                    <p class="text-sm font-semibold text-gray-900">Done</p>
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
