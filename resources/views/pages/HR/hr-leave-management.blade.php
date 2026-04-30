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
    .brand-row-hover:hover { background-color: #f0f7fc; }

    @keyframes shrink { from { width: 100% } to { width: 0% } }
</style>

    {{-- ═══════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-3">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Administration</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Leave Approval</h1>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         STAT CARDS (mobile-friendly grid)
    ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div class="bg-white rounded-xl border border-yellow-200 p-3 flex flex-col items-center gap-1 shadow-sm">
            <span class="flex h-2 w-2 rounded-full brand-bg-accent animate-pulse mb-0.5"></span>
            <span class="text-2xl font-extrabold brand-text-accent leading-none">{{ $this->pendingCount }}</span>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide text-center leading-tight">Pending<br>Review</span>
        </div>
        <div class="bg-white rounded-xl border border-amber-200 p-3 flex flex-col items-center gap-1 shadow-sm">
            <span class="flex h-2 w-2 rounded-full bg-amber-500 {{ $this->cancellationCount > 0 ? 'animate-pulse' : '' }} mb-0.5"></span>
            <span class="text-2xl font-extrabold text-amber-600 leading-none">{{ $this->cancellationCount }}</span>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide text-center leading-tight">Cancel<br>Requests</span>
        </div>
        <div class="bg-white rounded-xl border border-green-200 p-3 flex flex-col items-center gap-1 shadow-sm">
            <span class="flex h-2 w-2 rounded-full bg-green-400 mb-0.5"></span>
            <span class="text-2xl font-extrabold text-green-600 leading-none">{{ $this->approvedTodayCount }}</span>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide text-center leading-tight">Approved<br>Today</span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         FILTERS
    ═══════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-4 py-3 mb-4">
        <div class="flex items-center gap-2 mb-2">
            <h3 class="text-sm font-bold text-gray-700">Queue</h3>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full font-medium">
                {{ count($this->leaves) }}
            </span>
        </div>
        <div class="flex gap-2 w-full">
            <select wire:model.live="statusFilter"
                class="search-focus min-w-0 flex-1 text-sm border border-gray-200 rounded-lg px-2 py-2 bg-white text-gray-700">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="cancellation_requested">Cancel Req.</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="Search staff…"
                   class="search-focus min-w-0 flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg"/>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MOBILE CARD LIST  (hidden on md+)
    ═══════════════════════════════════════════ --}}
    @php
        $hrStyles = [
            'approved'               => 'background-color:#dcfce7;color:#166534;border:1px solid #86efac;',
            'rejected'               => 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
            'pending'                => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
            'cancelled'              => 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;',
            'cancellation_requested' => 'background-color:#fef3c7;color:#92400e;border:1px solid #f59e0b;',
        ];
        $hrLabel = [
            'cancellation_requested' => 'Cancel Req.',
        ];
    @endphp

    <div class="md:hidden space-y-3">
        @forelse($this->leaves as $leave)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Card Top: Avatar + Name + Leave Type --}}
                <div class="px-4 pt-4 pb-3 flex items-start gap-3">
                    <button wire:click="openBalanceModal({{ $leave->user_id }}, '{{ addslashes($leave->user?->username ?? '') }}')"
                            class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0 brand-bg-primary active:opacity-80">
                        {{ strtoupper(substr($leave->user?->username ?? '?', 0, 2)) }}
                    </button>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <button wire:click="openBalanceModal({{ $leave->user_id }}, '{{ addslashes($leave->user?->username ?? '') }}')"
                                    class="text-sm font-bold text-gray-900 truncate text-left hover:underline">
                                {{ $leave->user?->username ?? '(no user)' }}
                            </button>
                            {{-- Leave type badge --}}
                            <span class="shrink-0 text-[10px] font-extrabold tracking-wide px-2 py-0.5 rounded-full"
                                  style="background-color:#e6f0f7;color:#015581;border:1px solid #b3d0e8;">
                                {{ strtoupper(explode(' ', $leave->leave_type)[0]) }}
                            </span>
                        </div>
                        <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide truncate">
                            {{ $leave->user?->employmentDetail?->department?->name ?? 'General' }}
                        </div>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="px-4 pb-3">
                    <p class="text-xs text-gray-500 italic leading-relaxed line-clamp-2">"{{ $leave->reason }}"</p>
                </div>

                {{-- Timeline Grid --}}
                <div class="px-4 pb-3 grid grid-cols-2 gap-x-4 gap-y-1">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wide text-gray-400">From</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $leave->start_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wide text-gray-400">To</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $leave->end_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wide text-gray-400">Duration</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $leave->total_days }} Day(s)</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wide text-gray-400">Day Part</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $leave->day_part }}</span>
                    </div>
                </div>

                {{-- Footer: status pills + review button --}}
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center gap-2 flex-wrap">
                    {{-- DH pill: always shows DHead's own role --}}
                    @if($leave->cancellation_status === 'cancelled')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-500 bg-gray-100 border border-gray-300 px-2 py-0.5 rounded-full">DH: Cancelled</span>
                    @elseif($leave->cancellation_status === 'dhead_approved')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-full">DH: Fwd. to HR</span>
                    @elseif($leave->dept_head_status === 'approved')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full">DH: Cleared</span>
                    @else
                        <span class="inline-flex items-center text-[10px] font-bold brand-text-accent brand-bg-accent-light border border-yellow-200 px-2 py-0.5 rounded-full">DH: Pending</span>
                    @endif

                    {{-- HR pill: reflects HR's decision including cancellation outcomes --}}
                    @if($leave->cancellation_status === 'cancelled')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-500 bg-gray-100 border border-gray-300 px-2 py-0.5 rounded-full">HR: Cancelled</span>
                    @elseif($leave->cancellation_status === 'hr_rejected')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-700 bg-red-50 border border-red-200 px-2 py-0.5 rounded-full">HR: Cancel Denied</span>
                    @elseif($leave->cancellation_status === 'dhead_approved')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-300 px-2 py-0.5 rounded-full">HR: Action Needed</span>
                    @elseif($leave->cancellation_status === 'dhead_rejected')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full">HR: Approved</span>
                    @else
                        <span class="inline-flex text-[10px] font-bold px-2 py-0.5 rounded-full"
                              style="{{ $hrStyles[$leave->hr_status] ?? $hrStyles['pending'] }}">
                            {{ $hrLabel[$leave->hr_status] ?? ucfirst($leave->hr_status) }}
                        </span>
                    @endif

                    @if($leave->cancellation_status === 'dhead_approved')
                        <button wire:click="viewDetails({{ $leave->id }})"
                                class="ml-auto inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg active:scale-95 transition-all bg-amber-500 hover:bg-amber-600 text-white">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Review Cancel
                        </button>
                    @else
                        <button wire:click="viewDetails({{ $leave->id }})"
                                class="ml-auto px-4 py-2 text-xs font-bold rounded-lg active:scale-95 transition-all"
                                style="background-color:#e6f4f5;color:#027c8b;">
                            Review
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 px-6 py-12 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium">{{ $search ? 'No applications match your search.' : 'No leave applications found.' }}</p>
                <p class="text-xs mt-1">{{ $search ? 'Try a different keyword.' : 'All caught up — nothing pending review.' }}</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════════════════════════════════════════
         DESKTOP TABLE  (hidden below md)
    ═══════════════════════════════════════════ --}}
    <div class="hidden md:block bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Leave Details</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Timeline</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dept Head</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">HR Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($this->leaves as $leave)
                        <tr class="brand-row-hover transition-colors {{ $leave->cancellation_status === 'dhead_approved' ? 'bg-amber-50/40' : '' }}">
                            <td class="px-6 py-4">
                                <button wire:click="openBalanceModal({{ $leave->user_id }}, '{{ addslashes($leave->user?->username ?? '') }}')"
                                        class="flex items-center gap-3 text-left group w-full">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 brand-bg-primary">
                                        {{ strtoupper(substr($leave->user?->username ?? '?', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 group-hover:underline">{{ $leave->user?->username ?? '(no user)' }}</div>
                                        <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">
                                            {{ $leave->user?->employmentDetail?->department?->name ?? 'General' }}
                                        </div>
                                        @if($leave->cancellation_status === 'dhead_approved')
                                            <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide bg-amber-100 text-amber-700 border border-amber-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse inline-block"></span>
                                                Cancel Review Needed
                                            </span>
                                        @endif
                                    </div>
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-800">{{ $leave->leave_type }}</div>
                                <div class="text-xs text-gray-400 truncate max-w-[180px] italic">"{{ $leave->reason }}"</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $leave->start_date->format('M d') }} – {{ $leave->end_date->format('M d, Y') }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                                    {{ $leave->total_days }} Days · {{ $leave->day_part }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($leave->cancellation_status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        DH: Cancelled
                                    </span>
                                @elseif($leave->cancellation_status === 'dhead_approved')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        DH: Fwd. to HR
                                    </span>
                                @elseif($leave->cancellation_status === 'hr_rejected')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-700 bg-orange-50 border border-orange-200 px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        DH: Cleared
                                    </span>
                                @elseif($leave->cancellation_status === 'dhead_rejected')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        DH: Denied Cancel.
                                    </span>
                                @elseif($leave->cancellation_status === 'pending')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-yellow-50 border border-yellow-200 px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        DH: Reviewing...
                                    </span>
                                @elseif($leave->dept_head_status === 'approved')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        DH: Cleared
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold brand-text-accent brand-bg-accent-light border border-yellow-200 px-2.5 py-0.5 rounded-full">
                                        DH: Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($leave->cancellation_status === 'cancelled')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full text-red-700 bg-red-50 border border-red-200">
                                        HR: Cancelled
                                    </span>
                                @elseif($leave->cancellation_status === 'hr_rejected')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full text-orange-700 bg-orange-50 border border-orange-200">
                                        HR: Cancel Denied
                                    </span>
                                @elseif($leave->cancellation_status === 'dhead_approved')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full text-amber-700 bg-amber-50 border border-amber-200">
                                        HR: Action Needed
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="{{ $hrStyles[$leave->hr_status] ?? $hrStyles['pending'] }}">
                                        {{ $hrLabel[$leave->hr_status] ?? ucfirst($leave->hr_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($leave->cancellation_status === 'dhead_approved')
                                    <button wire:click="viewDetails({{ $leave->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-bold shadow-sm transition-colors bg-amber-500 hover:bg-amber-600 text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Review Cancel
                                    </button>
                                @else
                                    <button wire:click="viewDetails({{ $leave->id }})"
                                        class="rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm transition-colors"
                                        style="background-color:#e6f4f5;color:#027c8b;"
                                        onmouseover="this.style.backgroundColor='#cde9ec'"
                                        onmouseout="this.style.backgroundColor='#e6f4f5'">
                                        Review
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">
                                        {{ $search ? 'No applications match your search.' : 'No leave applications found.' }}
                                    </p>
                                    <p class="text-xs mt-1">
                                        {{ $search ? 'Try a different keyword.' : 'All caught up — nothing pending review.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         BALANCE MODAL
    ═══════════════════════════════════════════ --}}
    @if($showBalanceModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="closeBalanceModal"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl w-full max-w-lg"
                     style="border-top: 4px solid #015581;">

                    {{-- Header --}}
                    <div class="bg-white px-6 pt-5 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm brand-bg-primary shrink-0">
                                    {{ strtoupper(substr($balanceName, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $balanceName }}</h3>
                                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Leave Balance Summary</p>
                                </div>
                            </div>
                            <button wire:click="closeBalanceModal" class="text-gray-400 hover:text-gray-600 p-1 rounded transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Year Filter --}}
                    <div class="px-6 py-3 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                        <span class="text-xs font-bold uppercase tracking-wide text-gray-400">Year</span>
                        <select wire:model.live="balanceYear"
                                class="text-sm border border-gray-200 rounded-lg px-3 py-1 bg-white text-gray-700 search-focus">
                            @for($y = now()->year; $y >= now()->year - 4; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                        <span class="text-xs text-gray-400">"Used This Year" filtered by {{ $balanceYear }}</span>
                    </div>

                    {{-- Balance Table --}}
                    <div class="overflow-y-auto max-h-[55vh]">
                        @if(count($this->balanceData) > 0)
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Used {{ $balanceYear }}</th>
                                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($this->balanceData as $row)
                                        <tr class="brand-row-hover">
                                            <td class="px-6 py-2.5 font-semibold text-gray-800 text-sm">{{ $row['label'] }}</td>
                                            <td class="px-4 py-2.5 text-center text-gray-600 text-sm">{{ number_format($row['total'], 1) }}</td>
                                            <td class="px-4 py-2.5 text-center">
                                                @if($row['consumed_year'] > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                                                        {{ number_format($row['consumed_year'], 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5 text-center">
                                                @php $rem = $row['remaining']; @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                                    {{ $rem <= 0 ? 'bg-red-50 text-red-700 border border-red-200' : ($rem <= 2 ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : 'bg-green-50 text-green-700 border border-green-200') }}">
                                                    {{ number_format($rem, 1) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="px-6 py-10 text-center text-gray-400">
                                <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium">No leave balance records found.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 px-6 py-3 flex justify-end rounded-b-xl">
                        <button wire:click="closeBalanceModal"
                                class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         REVIEW SLIDE-OVER (SIDE MODAL)
    ═══════════════════════════════════════════ --}}
    <div x-data="{ show: @entangle('isReviewing') }"
         x-show="show"
         class="fixed inset-0 z-50 overflow-hidden"
         style="display: none;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-500/75 transition-opacity"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             @click="show = false; $wire.closeModal()"></div>

        {{-- Panel --}}
        <div class="fixed inset-y-0 right-0 sm:pl-10 max-w-full flex">
            <div class="w-screen max-w-md transform transition ease-in-out duration-500"
                 x-show="show"
                 x-transition:enter="transform transition ease-in-out duration-500"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">

                <div class="h-full flex flex-col bg-white shadow-2xl overflow-hidden"
                     style="border-top-left-radius: 1rem; border-bottom-left-radius: 1rem;">

                    @if($this->selectedLeave)

                        {{-- Slide-over Header --}}
                        <div class="px-6 py-6 brand-bg-primary text-white relative">
                            <button @click="show = false; $wire.closeModal()"
                                    class="absolute top-4 right-4 p-2 rounded-full text-blue-200 hover:text-white hover:bg-white/10 transition-colors"
                                    style="min-width:40px;min-height:40px;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <p class="text-[10px] font-semibold tracking-widest uppercase opacity-70 mb-1">Administration</p>
                            <h2 class="text-lg font-bold leading-tight">Review Leave Request</h2>
                            <p class="text-xs opacity-60 mt-0.5">Application #{{ $this->selectedLeave->id }}</p>
                        </div>

                        {{-- Cancellation action banner --}}
                        @if($this->selectedLeave->cancellation_status === 'dhead_approved')
                            <div class="px-6 py-3 bg-amber-500 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse shrink-0"></span>
                                <p class="text-xs font-bold text-white uppercase tracking-wide">Cancellation Request — Awaiting HR Decision</p>
                            </div>
                        @endif

                        {{-- Scrollable Body --}}
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">

                            {{-- Employee Card --}}
                            <div class="flex items-center gap-4 p-4 brand-bg-primary-light rounded-lg border border-blue-100">
                                <div class="w-11 h-11 rounded-full brand-bg-primary flex items-center justify-center text-white font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($this->selectedLeave->user->username, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-base font-bold text-gray-900 leading-tight">{{ $this->selectedLeave->user->username }}</p>
                                    <p class="text-[10px] font-semibold brand-text-primary uppercase tracking-wide mt-0.5">
                                        {{ $this->selectedLeave->user->employmentDetail?->position ?? 'Staff Member' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Details Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Type of Leave</label>
                                    <p class="text-sm font-bold text-gray-800">{{ $this->selectedLeave->leave_type }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Total Days</label>
                                    <p class="text-sm font-bold text-gray-800">{{ $this->selectedLeave->total_days }} Day(s)</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Effective Period</label>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $this->selectedLeave->start_date->format('F d, Y') }} — {{ $this->selectedLeave->end_date->format('F d, Y') }}
                                    </p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Day Part</label>
                                    <p class="text-sm font-bold text-gray-800">{{ $this->selectedLeave->day_part }}</p>
                                </div>
                            </div>

                            {{-- Reason --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Statement of Reason</label>
                                <div class="p-4 brand-bg-accent-light border border-yellow-200 rounded-lg text-sm text-gray-700 italic leading-relaxed">
                                    "{{ $this->selectedLeave->reason }}"
                                </div>
                            </div>

                            {{-- Dept Head Status --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Department Head Status</label>
                                @if($this->selectedLeave->cancellation_status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Cancelled
                                    </span>
                                @elseif($this->selectedLeave->cancellation_status === 'dhead_approved')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        DH: Fwd. to HR
                                    </span>
                                @elseif($this->selectedLeave->cancellation_status === 'hr_rejected')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Cleared — Fwd. to HR
                                    </span>
                                @elseif($this->selectedLeave->cancellation_status === 'dhead_rejected')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        DH Denied Cancellation
                                    </span>
                                @elseif($this->selectedLeave->cancellation_status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-yellow-700 bg-yellow-50 border border-yellow-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Cancellation Requested (Awaiting DHead)
                                    </span>
                                @elseif($this->selectedLeave->dept_head_status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Cleared by Department Head
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold brand-text-accent brand-bg-accent-light border border-yellow-200 px-3 py-1.5 rounded-full">
                                        Awaiting Department Head
                                    </span>
                                @endif
                            </div>

                            {{-- HR Status --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">HR Status</label>
                                @if($this->selectedLeave->cancellation_status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        HR: Approved Cancellation
                                    </span>
                                @elseif($this->selectedLeave->cancellation_status === 'hr_rejected')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-700 bg-orange-50 border border-orange-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        HR: Denied Cancellation
                                    </span>
                                @elseif($this->selectedLeave->cancellation_status === 'dhead_approved')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        HR: Action Needed
                                    </span>
                                @else
                                    @php $s = $this->selectedLeave->hr_status; @endphp
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                                          style="{{ $hrStyles[$s] ?? $hrStyles['pending'] }}">
                                        {{ $hrLabel[$s] ?? ucfirst($s) }}
                                    </span>
                                @endif
                            </div>

                            {{-- HR Remarks --}}
                            <div class="pt-4 border-t border-gray-100">
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">HR Review Remarks</label>
                                <textarea wire:model="hrRemarks" rows="4"
                                          placeholder="Enter internal notes or feedback here..."
                                          class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white resize-none"></textarea>
                                @error('hrRemarks') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Action Footer --}}
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 space-y-3">
                            @if($this->selectedLeave->cancellation_status === 'dhead_approved')
                                {{-- Cancellation request: approve or deny --}}
                                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 font-medium">
                                    This employee has requested to cancel an approved leave. Approving will restore their credits.
                                </div>
                                <div class="flex flex-row-reverse gap-3">
                                    <button wire:click="approveCancellation" wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 text-sm font-bold shadow-sm active:scale-95 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="approveCancellation">Approve Cancellation</span>
                                        <span wire:loading wire:target="approveCancellation">Saving…</span>
                                    </button>
                                    <button wire:click="rejectCancellation" wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors active:scale-95">
                                        <span wire:loading.remove wire:target="rejectCancellation">Deny Cancellation</span>
                                        <span wire:loading wire:target="rejectCancellation">Saving…</span>
                                    </button>
                                </div>
                            @else
                                {{-- Normal leave: approve or reject --}}
                                <div class="flex flex-row-reverse gap-3">
                                    <button wire:click="approve" wire:loading.attr="disabled"
                                        class="brand-btn-teal inline-flex justify-center items-center gap-2 rounded-lg px-5 py-2 text-sm font-bold shadow-sm active:scale-95 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="approve">Approve</span>
                                        <span wire:loading wire:target="approve" class="flex items-center gap-2">
                                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                            </svg>
                                            Saving…
                                        </span>
                                    </button>
                                    <button wire:click="reject" wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-200 hover:bg-red-50 transition-colors active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            @endif
                            {{-- Mobile-friendly close --}}
                            <button wire:click="closeModal"
                                    class="sm:hidden w-full py-3 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg active:bg-gray-50 transition-colors">
                                Close
                            </button>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>

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