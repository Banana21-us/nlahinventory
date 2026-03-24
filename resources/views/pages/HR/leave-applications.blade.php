<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <div>
            <div class="text-2xl font-bold text-gray-900">Leave Applications</div>
            <div class="text-sm text-gray-500">Manage and review employee leave requests</div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
            <div class="font-bold text-gray-900 text-base">Leave List</div>
            <div class="text-sm text-gray-500">Total: {{ $leaves->total() }} application(s)</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Employee</th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('leavetype')">
                            Leave Type
                            @if($sortField === 'leavetype')
                                <i class="fas fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs ml-1"></i>
                            @endif
                        </th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Department</th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('startdate')">
                            Duration
                            @if($sortField === 'startdate')
                                <i class="fas fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs ml-1"></i>
                            @endif
                        </th>
                        <th class="text-center text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Days</th>
                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap">Reason</th>
                        <th class="text-center text-xs font-semibold tracking-wider text-gray-500 uppercase px-5 py-3 whitespace-nowrap cursor-pointer hover:text-gray-700" wire:click="sortBy('status')">
                            Status
                            @if($sortField === 'status')
                                <i class="fas fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs ml-1"></i>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr wire:key="leave-{{ $leave->id }}" 
                            wire:click="viewLeave({{ $leave->id }})" 
                            class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                            <td class="px-5 py-4 align-middle">
                                <div class="font-semibold text-gray-900 text-sm">{{ $leave->user->username ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $leave->user->employee_number ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-4 align-middle">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $leave->leave_type_badge_class }}">
                                    {{ $leave->formatted_leave_type }}
                                </span>
                            </td>
                            <td class="px-5 py-4 align-middle text-gray-600 text-sm">{{ $leave->department }}</td>
                            <td class="px-5 py-4 align-middle text-gray-600 text-xs whitespace-nowrap">
                                {{ Carbon\Carbon::parse($leave->startdate)->format('M d, Y') }} –
                                {{ Carbon\Carbon::parse($leave->enddate)->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-4 align-middle text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $leave->totaldays }} {{ Str::plural('day', $leave->totaldays) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 align-middle text-gray-600 text-sm max-w-[200px]">
                                <span title="{{ $leave->reason }}">{{ Str::limit($leave->reason, 40) }}</span>
                            </td>
                            <td class="px-5 py-4 align-middle text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $leave->status_badge_class }}">
                                    {{ $leave->formatted_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                No leave applications found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>

    {{-- View Modal --}}
    @if($showViewModal && $selectedLeave)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-xl font-bold text-gray-900">Leave Application Details</div>
                            <div class="text-sm text-gray-500 mt-0.5">
                                #{{ $selectedLeave->id }} &middot; Submitted {{ Carbon\Carbon::parse($selectedLeave->created_at)->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" wire:click="closeModal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    {{-- Employee Info --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Employee Information</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Employee Name</div>
                                <div class="text-sm font-medium text-gray-900">{{ $selectedLeave->user->username ?? 'Unknown' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Employee Number</div>
                                <div class="text-sm font-medium text-gray-900">{{ $selectedLeave->user->employee_number ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Department</div>
                                <div class="text-sm font-medium text-gray-900">{{ $selectedLeave->department }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Position</div>
                                <div class="text-sm font-medium text-gray-900">{{ $selectedLeave->user->position ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Leave Details --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Leave Details</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Leave Type</div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $selectedLeave->leave_type_badge_class }}">
                                    {{ $selectedLeave->formatted_leave_type }}
                                </span>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Status</div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $selectedLeave->status_badge_class }}">
                                    {{ $selectedLeave->formatted_status }}
                                </span>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Start Date</div>
                                <div class="text-sm font-medium text-gray-900">{{ Carbon\Carbon::parse($selectedLeave->startdate)->format('l, F d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">End Date</div>
                                <div class="text-sm font-medium text-gray-900">{{ Carbon\Carbon::parse($selectedLeave->enddate)->format('l, F d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Total Days</div>
                                <div class="text-sm font-medium text-gray-900">{{ $selectedLeave->totaldays }} {{ Str::plural('day', $selectedLeave->totaldays) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Reason --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Reason for Leave</h4>
                        <div class="text-sm text-gray-700 leading-relaxed">{{ $selectedLeave->reason }}</div>
                    </div>

                    {{-- Remarks & Approval --}}
                    @if($selectedLeave->remarks || $selectedLeave->approved_by)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Approval Details</h4>
                            @if($selectedLeave->remarks)
                                <div class="mb-3">
                                    <div class="text-xs text-gray-500 mb-1">Remarks</div>
                                    <div class="text-sm text-gray-700">{{ $selectedLeave->remarks }}</div>
                                </div>
                            @endif
                            @if($selectedLeave->approved_by)
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Processed By</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ optional($selectedLeave->approver)->username ?? 'System' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Processed on {{ Carbon\Carbon::parse($selectedLeave->updated_at)->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4 flex gap-3 justify-end">
                    <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors"
                            wire:click="closeModal">Close</button>
                    @if($selectedLeave->status === 'pending')
                        <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors"
                                wire:click="openActionModal({{ $selectedLeave->id }}, 'rejected')">Reject</button>
                        <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors"
                                wire:click="openActionModal({{ $selectedLeave->id }}, 'approved')">Approve</button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Action Modal --}}
    @if($showActionModal && $selectedLeave)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" wire:click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-xl font-bold text-gray-900">
                            {{ $actionType === 'approved' ? 'Approve' : 'Reject' }} Leave Request
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="closeModal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <p class="text-sm text-gray-700">
                            You are about to <strong class="{{ $actionType === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $actionType === 'approved' ? 'approve' : 'reject' }}
                            </strong> the leave request from:
                        </p>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                            <div class="font-medium text-gray-900">{{ $selectedLeave->user->username ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $selectedLeave->formatted_leave_type }} &middot; 
                                {{ Carbon\Carbon::parse($selectedLeave->startdate)->format('M d') }} - 
                                {{ Carbon\Carbon::parse($selectedLeave->enddate)->format('M d, Y') }}
                                ({{ $selectedLeave->totaldays }} days)
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-semibold text-gray-700 block mb-2">
                            Remarks <span class="font-normal text-gray-500">(Optional)</span>
                        </label>
                        <textarea class="w-full border border-gray-300 rounded-lg text-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                  wire:model="actionRemarks" 
                                  rows="3"
                                  placeholder="Add any remarks or comments..."></textarea>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 px-6 py-4 flex gap-3 justify-end">
                    <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors"
                            wire:click="closeModal">Cancel</button>
                    <button type="button"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $actionType === 'approved' ? 'text-green-700 bg-green-50 hover:bg-green-100' : 'text-red-700 bg-red-50 hover:bg-red-100' }}"
                            wire:click="processLeaveAction">
                        {{ $actionType === 'approved' ? 'Confirm Approval' : 'Confirm Rejection' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if(session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="fixed bottom-4 right-4 z-50">
            <div class="bg-green-50 border border-green-200 rounded-lg shadow-lg p-4 min-w-[300px]">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-50 border border-red-200 rounded-lg shadow-lg p-4 min-w-[300px]">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

</div>