{{-- resources/views/pages/users/dhead-leave.blade.php --}}

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
    .brand-btn-primary { background-color: #015581; color: #fff; transition: background-color 0.15s; }
    .brand-btn-primary:hover { background-color: #01406a; }
    .brand-focus:focus { outline: none; box-shadow: 0 0 0 3px rgba(1,85,129,0.2); border-color: #015581; }
    .search-focus:focus { outline: none; box-shadow: 0 0 0 3px rgba(2,124,139,0.2); border-color: #027c8b; }
    .brand-row-hover:hover { background-color: #f0f7fc; }
    .tab-active   { border-bottom: 3px solid #015581; color: #015581; font-weight: 700; }
    .tab-inactive { border-bottom: 2px solid transparent; color: #6b7280; font-weight: 500; }
    @keyframes shrink { from { width:100% } to { width:0% } }
    .animate-shrink { animation: shrink 4s linear forwards; }
</style>

    {{-- ── FLASH MESSAGE ── --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5">
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full brand-bg-teal-light">
                    <svg class="w-5 h-5 brand-text-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Success</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ session('message') }}</p>
                </div>
            </div>
            <div class="h-1 brand-bg-accent animate-shrink"></div>
        </div>
    @endif

    {{-- ── PAGE HEADER ── --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Department Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Leave Approvals</h1>
            </div>
        </div>
        <div class="text-xs text-gray-400 bg-white px-3 py-1 rounded-full shadow-sm">
            Dept Head · {{ auth()->user()->name }}
        </div>
    </div>

    {{-- ── SUMMARY CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Pending --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pending Review</p>
                <p class="text-2xl font-black brand-text-accent">{{ $pendingCount }}</p>
            </div>
            <div class="w-8 h-8 rounded-full brand-bg-accent-light flex items-center justify-center">
                <svg class="w-4 h-4 brand-text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        {{-- Approved this month --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Approved This Month</p>
                <p class="text-2xl font-black brand-text-primary">{{ $approvedThisMonth }}</p>
            </div>
            <div class="w-8 h-8 rounded-full brand-bg-primary-light flex items-center justify-center">
                <svg class="w-4 h-4 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        {{-- On Leave Today --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Staff on Leave Today</p>
                <p class="text-2xl font-black brand-text-teal">{{ $onLeaveToday }}</p>
            </div>
            <div class="w-8 h-8 rounded-full brand-bg-teal-light flex items-center justify-center">
                <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
        {{-- Status --}}
        <div class="brand-bg-primary-light p-4 rounded-xl shadow-sm border border-blue-100 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold brand-text-primary uppercase tracking-widest">System Status</p>
                <p class="text-sm font-bold text-gray-800">Operational</p>
            </div>
            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
        </div>
    </div>

    {{-- ── COLLAPSIBLE LEAVE FORM ── --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-8"
         x-data="{ openForm: false }">
        <button @click="openForm = !openForm"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none">
            <div class="flex items-center">
                <div class="p-2 rounded-lg mr-4 brand-bg-primary-light">
                    <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!openForm"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="openForm" style="display:none"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Leave Entry</h2>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="openForm ? 'Minimize' : 'File a Leave'"></span>
        </button>

        <div x-show="openForm" x-cloak x-collapse
             class="p-6 border-t border-gray-100 bg-gray-50/30">
            <form wire:submit.prevent="submitLeave" class="space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Nature of Leave *</label>
                        <x-custom-select
                            wire-property="form.leave_type"
                            :current="$form['leave_type']"
                            :options="$leaveTypeOptions"
                            placeholder="Select Type…"
                            :error="$errors->first('form.leave_type')"
                        />
                        @if($blWindow)
                            <p class="text-[10px] text-pink-600 font-medium mt-1.5">
                                Valid window: {{ $blWindow['start']->format('M d') }} – {{ $blWindow['end']->format('M d, Y') }}
                            </p>
                        @endif
                    </div>
                    <div class="md:col-span-2 brand-bg-primary-light rounded-md border border-blue-100 px-4 py-3 flex items-center justify-between"
                         wire:key="credits-panel-{{ $form['leave_type'] }}">
                        <div>
                            <p class="text-[10px] font-bold brand-text-primary uppercase tracking-wide">{{ $creditLabel }}</p>
                            <p class="text-xl font-bold text-gray-800 mt-0.5">
                                @if($showCredits)
                                    {{ $availableCredits }} <span class="text-sm font-semibold text-gray-500">Days</span>
                                @else
                                    <span class="text-sm font-semibold text-gray-400 italic">Unlimited</span>
                                @endif
                            </p>
                        </div>
                        <div class="p-2 rounded-lg brand-bg-primary">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Credit cap error --}}
                @error('form.total_days')
                    <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 font-medium">
                        {{ $message }}
                    </div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start Date *</label>
                        <input type="date" wire:model.live="form.start_date" required
                               class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('form.start_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End Date *</label>
                        <input type="date" wire:model.live="form.end_date" required
                               class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                               @if($vlMaxEndDate) max="{{ $vlMaxEndDate }}" @endif
                               @if(!empty($form['start_date'])) min="{{ $form['start_date'] }}" @endif/>
                        @if($vlRemainingThisYear !== null)
                            <span class="text-xs mt-1 block {{ $vlRemainingThisYear <= 5 ? 'text-amber-600' : 'text-gray-400' }}">
                                {{ $vlRemainingThisYear }} VL day(s) left for {{ \Carbon\Carbon::parse($form['start_date'])->year }} (max 20/year)
                            </span>
                        @endif
                        @error('form.end_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Duration</label>
                        <div class="flex items-center gap-2">
                            <x-custom-select
                                wire-property="form.day_part"
                                :current="$form['day_part']"
                                :options="[
                                    ['value' => 'Full', 'label' => 'Full Day'],
                                    ['value' => 'AM',   'label' => 'AM Half'],
                                    ['value' => 'PM',   'label' => 'PM Half'],
                                ]"
                                placeholder="Select…"
                            />
                            <div class="px-3 py-2 brand-bg-teal-light rounded-md font-bold brand-text-teal text-sm shrink-0 border border-teal-100 whitespace-nowrap">
                                {{ $form['total_days'] ?? 0 }}d
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason / Justification *</label>
                        <textarea wire:model="form.reason" rows="3" required
                                  placeholder="Briefly explain the purpose of your leave..."
                                  class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 resize-none"></textarea>
                        @error('form.reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">
                        Attachment <span class="text-gray-400 normal-case font-normal">(Optional — Medical Certificate, etc.)</span>
                    </label>
                    <label class="flex items-center justify-center w-full h-20 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-teal-light">
                                <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Click to upload file</p>
                                <p class="text-[10px] text-gray-400 font-medium">PDF, JPG or PNG — max 5MB</p>
                            </div>
                        </div>
                        <input type="file" wire:model="attachment" class="hidden"/>
                    </label>
                    @error('attachment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-2">
                    <button type="button" @click="openForm = false"
                            class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">Cancel</button>
                    <button type="submit"
                            class="brand-btn-primary text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95 flex items-center gap-2 disabled:opacity-70"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitLeave">Submit Application</span>
                        <span wire:loading wire:target="submitLeave" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Submitting…
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABS ── --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200"
         x-data="{ activeTab: 'incoming' }">

        <div class="border-b border-gray-200 bg-gray-50 px-6 pt-2 flex gap-6">
            <button @click="activeTab = 'incoming'"
                    :class="activeTab === 'incoming' ? 'tab-active' : 'tab-inactive'"
                    class="py-3 text-sm font-semibold transition-all duration-200 focus:outline-none">
                📋 Incoming Requests
                <span class="ml-1.5 bg-gray-200 text-gray-700 text-xs px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
            </button>
            <button @click="activeTab = 'cancellations'"
                    :class="activeTab === 'cancellations' ? 'tab-active' : 'tab-inactive'"
                    class="py-3 text-sm font-semibold transition-all duration-200 focus:outline-none">
                🔄 Cancellation Requests
                @if($cancellationPendingCount > 0)
                    <span class="ml-1.5 bg-amber-100 text-amber-700 text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $cancellationPendingCount }}</span>
                @else
                    <span class="ml-1.5 bg-gray-200 text-gray-700 text-xs px-1.5 py-0.5 rounded-full">0</span>
                @endif
            </button>
            <button @click="activeTab = 'payoffs'"
                    :class="activeTab === 'payoffs' ? 'tab-active' : 'tab-inactive'"
                    class="py-3 text-sm font-semibold transition-all duration-200 focus:outline-none">
                💰 Pay-off Requests
                @if($pendingPayoffs->count() > 0)
                    <span class="ml-1.5 bg-emerald-100 text-emerald-700 text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $pendingPayoffs->count() }}</span>
                @else
                    <span class="ml-1.5 bg-gray-200 text-gray-700 text-xs px-1.5 py-0.5 rounded-full">0</span>
                @endif
            </button>
            <button @click="activeTab = 'myrequests'"
                    :class="activeTab === 'myrequests' ? 'tab-active' : 'tab-inactive'"
                    class="py-3 text-sm font-semibold transition-all duration-200 focus:outline-none">
                📄 My Leave Requests
                <span class="ml-1.5 bg-gray-200 text-gray-700 text-xs px-1.5 py-0.5 rounded-full">{{ $myLeaves->count() }}</span>
            </button>
        </div>

        {{-- ── INCOMING REQUESTS TABLE ── --}}
        <div x-show="activeTab === 'incoming'" x-cloak>
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
                <h3 class="text-md font-bold text-gray-700">Staff Leave Applications</h3>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Search employee name or leave type..."
                       class="search-focus pl-4 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg transition-all w-64"/>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leave Info</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($leaves as $leave)
                            <tr class="brand-row-hover transition-colors">
                                {{-- Employee --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full brand-bg-primary flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($leave->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">{{ $leave->user->name ?? '—' }}</div>
                                            <div class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">
                                                ID: {{ $leave->user->id ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{-- Leave Info --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-700">{{ $leaveTypeMap[$leave->leave_type] ?? $leave->leave_type }}</div>
                                    <div class="text-[10px] text-gray-400 italic">
                                        {{ Str::limit($leave->reason, 30) }}
                                    </div>
                                </td>
                                {{-- Duration --}}
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }}
                                        –
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-[10px] brand-text-teal font-bold uppercase">
                                        {{ $leave->total_days }} Day(s)
                                    </div>
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending'  => 'background:#fef8e7;color:#b45309;',
                                            'approved' => 'background:#e6f4f5;color:#027c8b;',
                                            'rejected' => 'background:#fef2f2;color:#dc2626;',
                                        ];
                                        $style = $statusColors[$leave->dept_head_status] ?? 'background:#f3f4f6;color:#6b7280;';
                                    @endphp
                                    <span class="px-2.5 py-0.5 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase tracking-wide"
                                          style="{{ $style }}">
                                        {{ $leave->dept_head_status }}
                                    </span>
                                </td>
                                {{-- Action --}}
                                <td class="px-6 py-4 text-right">
                                    @if ($leave->dept_head_status === 'pending')
                                        <button wire:click="openReviewModal({{ $leave->id }})"
                                                class="brand-btn-primary px-4 py-1.5 rounded text-xs font-bold shadow-sm transition-transform active:scale-95">
                                            Review
                                        </button>
                                    @else
                                        <button wire:click="openReviewModal({{ $leave->id }})"
                                                class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm italic">
                                    No applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── CANCELLATION REQUESTS TABLE ── --}}
        <div x-show="activeTab === 'cancellations'" x-cloak>
            <div class="bg-amber-50 px-6 py-3 border-b border-amber-100 flex flex-wrap gap-3 justify-between items-center">
                <h3 class="text-md font-bold text-amber-800">Staff Cancellation Requests</h3>
                <p class="text-xs text-amber-600">Staff requesting to cancel a previously approved leave. Approve to forward to HR, or reject to keep the leave active.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leave Info</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Reason</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($cancellationLeaves as $cl)
                            <tr class="brand-row-hover transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($cl->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">{{ $cl->user->name ?? '—' }}</div>
                                            <div class="text-[10px] text-gray-400 font-medium">
                                                {{ $cl->user->employmentDetail?->department?->name ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-700">{{ $leaveTypeMap[$cl->leave_type] ?? $cl->leave_type }}</div>
                                    <div class="text-[10px] text-amber-600 font-bold uppercase">{{ $cl->total_days }}d · {{ $cl->day_part }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($cl->start_date)->format('M d') }}
                                        –
                                        {{ \Carbon\Carbon::parse($cl->end_date)->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 italic max-w-[180px]">
                                    {{ Str::limit($cl->reason, 50) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div x-data="{ confirm: null }" class="flex items-center justify-end gap-2">
                                        <template x-if="confirm === null">
                                            <span class="flex gap-2">
                                                <button @click="confirm = 'approve'"
                                                        class="px-3 py-1.5 rounded text-xs font-bold text-white bg-green-600 hover:bg-green-700 transition-colors">
                                                    Approve
                                                </button>
                                                <button @click="confirm = 'reject'"
                                                        class="px-3 py-1.5 rounded text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition-colors">
                                                    Reject
                                                </button>
                                            </span>
                                        </template>
                                        <template x-if="confirm === 'approve'">
                                            <span class="flex items-center gap-1">
                                                <span class="text-xs text-green-700 font-medium">Forward to HR?</span>
                                                <button wire:click="approveCancellationRequest({{ $cl->id }})"
                                                        class="text-xs font-bold text-white bg-green-600 hover:bg-green-700 px-2 py-0.5 rounded">Yes</button>
                                                <button @click="confirm = null" class="text-xs text-gray-400 hover:text-gray-600 px-1">No</button>
                                            </span>
                                        </template>
                                        <template x-if="confirm === 'reject'">
                                            <span class="flex items-center gap-1">
                                                <span class="text-xs text-red-600 font-medium">Deny & keep leave?</span>
                                                <button wire:click="rejectCancellationRequest({{ $cl->id }})"
                                                        class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-2 py-0.5 rounded">Yes</button>
                                                <button @click="confirm = null" class="text-xs text-gray-400 hover:text-gray-600 px-1">No</button>
                                            </span>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm italic">
                                    No pending cancellation requests.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── PAY-OFF APPROVAL QUEUE ── --}}
        <div x-show="activeTab === 'payoffs'" x-cloak
             x-data="{
                 confirmOpen: false, confirmId: null, confirmAction: null,
                 open(id, action) { this.confirmId = id; this.confirmAction = action; this.confirmOpen = true; },
                 confirm() {
                     if (this.confirmAction === 'approve') $wire.approvePayoff(this.confirmId);
                     else $wire.rejectPayoff(this.confirmId);
                     this.confirmOpen = false;
                 }
             }">
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                <h3 class="text-md font-bold text-gray-700">Pay-off Applications — Pending Your Approval</h3>
                <p class="text-xs text-gray-400 mt-0.5">Approve or reject employee pay-off requests from your department.</p>
            </div>

            @if($pendingPayoffs->isEmpty())
                <div class="px-6 py-14 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm font-medium">No pending pay-off requests.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($pendingPayoffs as $po)
                        <div class="px-6 py-4 flex flex-wrap gap-4 justify-between items-center hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-sm font-bold text-gray-500">
                                    {{ strtoupper(substr($po->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $po->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $po->start_datetime->format('M d, Y h:i A') }} → {{ $po->end_datetime->format('M d, Y h:i A') }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-bold text-[#015581]">{{ $po->hours }}h</span>
                                        @if($po->redemption_type === 'cash')
                                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">Cash</span>
                                        @else
                                            <span class="text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full">Leave</span>
                                        @endif
                                        @if($po->reason)
                                            <span class="text-xs text-gray-400 italic">{{ Str::limit($po->reason, 60) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="open({{ $po->id }}, 'approve')"
                                    class="text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg px-4 py-2 transition-colors active:scale-95">
                                    Approve
                                </button>
                                <button @click="open({{ $po->id }}, 'reject')"
                                    class="text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg px-4 py-2 transition-colors active:scale-95">
                                    Reject
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Confirm Modal --}}
            <div x-show="confirmOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="fixed inset-0 bg-gray-900/50" @click="confirmOpen = false"></div>
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm">
                        <div class="px-6 pt-6 pb-4 flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-amber-100">
                                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900" x-text="confirmAction === 'approve' ? 'Approve Pay-off?' : 'Reject Pay-off?'"></h3>
                                <p class="mt-1 text-sm text-gray-500" x-text="confirmAction === 'approve' ? 'This will forward the request to HR.' : 'This action cannot be undone.'"></p>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button @click="confirm()"
                                :class="confirmAction === 'approve' ? 'bg-emerald-600 hover:bg-emerald-500' : 'bg-red-600 hover:bg-red-500'"
                                class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-bold text-white active:scale-95"
                                x-text="confirmAction === 'approve' ? 'Yes, Approve' : 'Yes, Reject'">
                            </button>
                            <button @click="confirmOpen = false"
                                class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── MY LEAVE REQUESTS TABLE ── --}}
        <div x-show="activeTab === 'myrequests'" x-cloak>
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
                <h3 class="text-md font-bold text-gray-700">My Submitted Leaves</h3>
                <input wire:model.live.debounce.300ms="mySearch" type="text"
                       placeholder="Search by type or reason..."
                       class="search-focus pl-4 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg transition-all w-64"/>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leave Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Days</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Filed On</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dept Head</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">HR Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Feedback</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($myLeaves as $leave)
                            @php
                                $myLeaveLabel = $leaveTypeMap[$leave->leave_type] ?? $leave->leave_type;
                                $hrColors = [
                                    'pending'                => 'background:#fef8e7;color:#b45309;',
                                    'approved'               => 'background:#e6f4f5;color:#027c8b;',
                                    'rejected'               => 'background:#fef2f2;color:#dc2626;',
                                    'cancelled'              => 'background:#f3f4f6;color:#6b7280;',
                                    'cancellation_requested' => 'background:#fef3c7;color:#92400e;border:1px solid #f59e0b;',
                                ];
                            @endphp
                            <tr class="brand-row-hover transition-colors">
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;">
                                        {{ $myLeaveLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }}
                                        –
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mt-0.5">
                                        {{ $leave->day_part }} Day
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full brand-bg-teal-light brand-text-teal"
                                          style="border:1px solid #a5d8dd;">
                                        {{ $leave->total_days }}d
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    {{ \Carbon\Carbon::parse($leave->date_requested ?? $leave->created_at)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="{{ $statusColors[$leave->dept_head_status] ?? '' }}">
                                        {{ $leave->dept_head_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="{{ $hrColors[$leave->hr_status ?? 'pending'] ?? '' }}">
                                        {{ $leave->hr_status === 'cancellation_requested' ? 'Cancel Requested' : ucfirst($leave->hr_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($leave->rejection_reason)
                                        <span class="text-xs text-red-600 font-medium italic">
                                            {{ Str::limit($leave->rejection_reason, 45) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                                {{-- Action --}}
                                <td class="px-6 py-4 text-right" wire:key="dhead-action-{{ $leave->id }}">
                                    @if($leave->hr_status === 'pending')
                                        <div x-data="{ confirm: false }" class="flex items-center justify-end gap-1">
                                            <button x-show="!confirm" @click.prevent="confirm = true"
                                                    class="px-2.5 py-1 rounded text-xs font-bold text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 transition-colors">
                                                Delete
                                            </button>
                                            <template x-if="confirm">
                                                <span class="flex items-center gap-1">
                                                    <span class="text-xs text-red-500 font-medium">Sure?</span>
                                                    <button wire:click="cancelMyLeave({{ $leave->id }})"
                                                            class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-2 py-0.5 rounded transition-colors">Yes</button>
                                                    <button @click="confirm = false"
                                                            class="text-xs text-gray-400 hover:text-gray-600 px-1">No</button>
                                                </span>
                                            </template>
                                        </div>
                                    @elseif($leave->hr_status === 'approved')
                                        <div x-data="{ confirm: false }" class="flex items-center justify-end gap-1">
                                            <button x-show="!confirm" @click.prevent="confirm = true"
                                                    class="px-2.5 py-1 rounded text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 hover:bg-purple-100 transition-colors whitespace-nowrap">
                                                Cancel Leave
                                            </button>
                                            <template x-if="confirm">
                                                <span class="flex items-center gap-1">
                                                    <span class="text-xs text-purple-600 font-medium">Request?</span>
                                                    <button wire:click="requestCancellation({{ $leave->id }})"
                                                            class="text-xs font-bold text-white bg-purple-500 hover:bg-purple-600 px-2 py-0.5 rounded transition-colors">Yes</button>
                                                    <button @click="confirm = false"
                                                            class="text-xs text-gray-400 hover:text-gray-600 px-1">No</button>
                                                </span>
                                            </template>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-14 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-sm font-medium">You have not filed any leave applications yet.</p>
                                        <p class="text-xs mt-1">Click "File a Leave" above to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── REVIEW MODAL ── --}}
    @if ($showReviewModal && $selectedLeave)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100"
                 wire:click.outside="closeModal">

                <div class="brand-bg-primary p-4 flex justify-between items-center text-white">
                    <h3 class="font-bold tracking-tight">Application Review</h3>
                    <button wire:click="closeModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-100">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Employee</p>
                            <p class="text-lg font-bold text-gray-800">{{ $selectedLeave->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Date Range</p>
                            <p class="text-sm font-bold brand-text-primary">
                                {{ \Carbon\Carbon::parse($selectedLeave->start_date)->format('M d') }}
                                –
                                {{ \Carbon\Carbon::parse($selectedLeave->end_date)->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Reason for Leave</p>
                        <div class="bg-gray-50 p-3 rounded text-sm text-gray-700 italic border-l-4 border-blue-500">
                            "{{ $selectedLeave->reason }}"
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                            Reviewer Remarks (Optional)
                        </label>
                        <textarea wire:model="remarks" rows="3"
                                  class="brand-focus w-full rounded-md border border-gray-200 text-sm p-3 bg-gray-50"
                                  placeholder="Add comments or rejection reason..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-8">
                        <button wire:click="process('rejected')"
                                class="bg-red-50 text-red-600 font-bold py-3 rounded-lg text-sm hover:bg-red-100 transition-colors border border-red-200 uppercase tracking-widest"
                                wire:loading.attr="disabled">
                            Reject
                        </button>
                        <button wire:click="process('approved')"
                                class="brand-bg-primary text-white font-bold py-3 rounded-lg text-sm hover:opacity-90 transition-opacity uppercase tracking-widest shadow-lg shadow-blue-900/20"
                                wire:loading.attr="disabled">
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>