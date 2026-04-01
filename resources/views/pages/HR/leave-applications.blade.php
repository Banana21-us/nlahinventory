@php use Illuminate\Support\Facades\Storage; @endphp

<div class="max-w-7xl mx-auto py-8 px-4">
    {{-- ═══════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-50 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Human Resources</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Leave Administration</h1>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="flex gap-4">
            <div class="hidden md:flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="w-2 h-2 rounded-full bg-amber-400 mr-2"></div>
                <span class="text-xs font-bold text-gray-600 uppercase">{{ $this->pendingCount }} Pending</span>
            </div>
            <div class="hidden md:flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                <span class="text-xs font-bold text-gray-600 uppercase">{{ $this->approvedTodayCount }} Approved Today</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         LEAVE REQUESTS TABLE
    ═══════════════════════════════════════════ --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        
        {{-- Table Tool Bar --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-50 rounded-lg">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Review Queue</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    {{ $this->leaves->count() }} Total
                </span>
            </div>

            {{-- Filter & Search --}}
            <div class="flex items-center gap-2">
                <select wire:model.live="statusFilter" class="text-sm bg-white border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="all">All Status</option>
                    <option value="pending">Pending HR</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <div class="relative">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="    Search staff name…"
                        class="pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all w-64"
                    />
                </div>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Leave Info</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dept. Head</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">HR Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($this->leaves as $leave)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            {{-- Employee Info --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-500 font-bold text-xs uppercase">
                                        {{ substr($leave->user->username, 0, 2) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $leave->user->username }}</span>
                                        <span class="text-[11px] text-gray-500">{{ $leave->user->employee->department ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Leave Type & Reason --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-800">{{ $leave->leave_type }}</span>
                                    <span class="text-xs text-gray-500 truncate max-w-[150px] italic">"{{ $leave->reason }}"</span>
                                </div>
                            </td>

                            {{-- Dates --}}
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-800 font-medium">{{ $leave->start_date->format('M d, Y') }}</div>
                                <div class="text-[11px] text-gray-500">{{ $leave->total_days }} day(s) · {{ $leave->day_part }}</div>
                            </td>

                            {{-- Dept Head Status --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($leave->dept_head_status === 'approved')
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                        <span class="text-xs font-semibold text-gray-600">Cleared</span>
                                    @else
                                        <span class="text-xs font-semibold text-amber-600">Pending DH</span>
                                    @endif
                                </div>
                            </td>

                            {{-- HR Status Badge --}}
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase tracking-wider
                                    {{ $leave->hr_status === 'approved' ? 'bg-green-100 text-green-700' : 
                                       ($leave->hr_status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $leave->hr_status }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="viewDetails({{ $leave->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-bold transition-colors">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No leave applications found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         REVIEW MODAL (SLIDE-OVER STYLE)
    ═══════════════════════════════════════════ --}}
    @if($selectedLeave)
        <div class="fixed inset-0 z-50 overflow-hidden" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-gray-500/75 transition-opacity" wire:click="closeModal"></div>
            
            <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                <div class="w-screen max-w-md transform transition ease-in-out duration-500">
                    <div class="h-full flex flex-col bg-white shadow-2xl">
                        
                        {{-- Modal Header --}}
                        <div class="px-6 py-6 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-bold text-gray-900">Application Review</h2>
                                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Body --}}
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">Employee Details</p>
                                <p class="text-base font-bold text-gray-900">{{ $selectedLeave->user->username }}</p>
                                <p class="text-sm text-gray-600">{{ $selectedLeave->user->employee->department }} · {{ $selectedLeave->user->employee->position }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase">Start Date</label>
                                    <p class="text-sm font-semibold text-gray-800">{{ $selectedLeave->start_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase">End Date</label>
                                    <p class="text-sm font-semibold text-gray-800">{{ $selectedLeave->end_date->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Reason for Leave</label>
                                <div class="mt-1 p-3 bg-gray-50 rounded-lg text-sm text-gray-700 italic border border-gray-100">
                                    "{{ $selectedLeave->reason }}"
                                </div>
                            </div>

                            @if($selectedLeave->attachment)
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Supporting Document</label>
                                    <a href="{{ Storage::url($selectedLeave->attachment) }}" target="_blank" 
                                       class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A1 1 0 0111.293 2.707l5 5a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                        <span class="text-xs font-bold text-gray-700">View Medical Cert / Attachment</span>
                                    </a>
                                </div>
                            @endif

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Internal HR Remarks</label>
                                <textarea wire:model="hrRemarks" rows="3" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Notes for payroll or employee..."></textarea>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="p-6 border-t border-gray-100 bg-gray-50 flex flex-col gap-3">
                            <button wire:click="approve" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Approve Application
                            </button>
                            <button wire:click="reject" class="w-full py-3 bg-white border border-red-200 text-red-600 hover:bg-red-50 font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reject Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>