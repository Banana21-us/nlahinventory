@php
use Carbon\Carbon;
@endphp

<div class="min-h-screen bg-gray-50">

    @if(session()->has('message'))
        <div class="mx-8 mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mx-8 mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content -->
    <div class="p-8">
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Employees -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 mb-1">Total Employees</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_employees'] }}</h3>
                        <p class="text-xs text-blue-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            {{ $stats['new_hires'] }} new this month
                        </p>
                    </div>
                    <div class="bg-blue-200 p-4 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- On Leave Today -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700 mb-1">On Leave Today</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['on_leave'] }}</h3>
                        <p class="text-xs text-yellow-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ $stats['total_employees'] > 0 ? round(($stats['on_leave'] / $stats['total_employees']) * 100, 1) : 0 }}% of workforce
                        </p>
                    </div>
                    <div class="bg-yellow-200 p-4 rounded-xl">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Leaves -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700 mb-1">Pending Leaves</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['pending_leaves'] }}</h3>
                        <p class="text-xs text-orange-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Awaiting approval
                        </p>
                    </div>
                    <div class="bg-orange-200 p-4 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Approved This Month -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 mb-1">Approved This Month</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['approved_leaves'] }}</h3>
                        <p class="text-xs text-green-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Leave requests
                        </p>
                    </div>
                    <div class="bg-green-200 p-4 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Employee Growth by Role -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Employee Growth by Role</h3>
                        <p class="text-sm text-gray-500">Monthly hiring trends</p>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="usersChart"></canvas>
                </div>
            </div>

            <!-- Leave Type Distribution -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Leave Type Distribution</h3>
                        <p class="text-sm text-gray-500">By category</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">{{ array_sum($leaveStats['by_type']['data'] ?? [0]) }}</p>
                        <p class="text-sm text-gray-500">Total leaves</p>
                    </div>
                </div>
                <div class="h-80 relative">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Leave Requests -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Leave Requests</h3>
                    <span class="text-sm text-gray-500">Last 5 requests</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Employee</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Type</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Dates</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-600">Days</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-blue-600">{{ substr($leave['employee_name'], 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $leave['employee_name'] }}</span>
                                            <p class="text-xs text-gray-500">{{ $leave['employee_number'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-gray-600">{{ $leave['leavetype'] }}</td>
                                <td class="py-3 px-4 text-gray-600 text-sm">
                                    {{ Carbon::parse($leave['startdate'])->format('M d') }} - {{ Carbon::parse($leave['enddate'])->format('M d') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="text-lg font-bold text-gray-900">{{ $leave['totaldays'] }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-3 py-1 text-xs rounded-full 
                                        @if($leave['status'] == 'approved') bg-green-100 text-green-700
                                        @elseif($leave['status'] == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($leave['status'] == 'rejected') bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($leave['status']) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">No recent leave requests</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                    <span class="text-sm text-gray-500">Awaiting review</span>
                </div>
                <div class="space-y-4">
                    @forelse($pendingLeaves as $leave)
                    <div class="flex items-start p-4 border border-gray-200 rounded-lg">
                        <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900">{{ $leave['employee_name'] }}</h4>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                    {{ $leave['department'] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                <span class="font-medium">{{ $leave['leavetype'] }}:</span> 
                                {{ Carbon::parse($leave['startdate'])->format('M d') }} - {{ Carbon::parse($leave['enddate'])->format('M d, Y') }} ({{ $leave['totaldays'] }} days)
                            </p>
                            <p class="text-xs text-gray-500 mb-3">Reason: {{ $leave['reason'] }}</p>
                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="approveLeave({{ $leave['id'] }})" 
                                        wire:confirm="Are you sure you want to approve this leave?" 
                                        class="px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg transition-colors duration-200 shadow-sm flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                                <button type="button" wire:click="rejectLeave({{ $leave['id'] }})" 
                                        wire:confirm="Are you sure you want to reject this leave?" 
                                        class="px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg transition-colors duration-200 shadow-sm flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No pending approvals</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            <!-- Leave by Status -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Leave by Status</h3>
                <div class="h-64 relative">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Upcoming Leaves -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Leaves</h3>
                    <span class="text-sm text-gray-500">Next 7 days</span>
                </div>
                <div class="space-y-4">
                    @forelse($upcomingLeaves as $leave)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-xs font-medium text-blue-600">{{ substr($leave['employee_name'], 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $leave['employee_name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $leave['department'] }} • {{ $leave['leavetype'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ Carbon::parse($leave['startdate'])->format('M d') }}</p>
                            <p class="text-xs text-gray-500">{{ $leave['totaldays'] }} days</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No upcoming leaves</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Staff Members</p>
                                <p class="text-xs text-gray-500">Regular employees</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $stats['staff'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Department Heads</p>
                                <p class="text-xs text-gray-500">Management</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $stats['dept_heads'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Maintenance</p>
                                <p class="text-xs text-gray-500">Support staff</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $stats['maintenance'] }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-amber-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-amber-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Inspectors</p>
                                <p class="text-xs text-gray-500">Quality assurance</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $stats['inspectors'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:init', function() {
        // Simple chart initialization
        setTimeout(function() {
            // Employee Growth Chart
            var usersCanvas = document.getElementById('usersChart');
            if (usersCanvas) {
                var usersCtx = usersCanvas.getContext('2d');
                new Chart(usersCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Total Employees',
                            data: [5, 6, 6, 7, 7, {{ $stats['total_employees'] }}],
                            borderColor: '#3b82f6',
                            backgroundColor: '#3b82f620',
                            fill: true
                        }]
                    }
                });
            }

            // Leave Type Chart
            var categoryCanvas = document.getElementById('categoryChart');
            if (categoryCanvas) {
                var categoryCtx = categoryCanvas.getContext('2d');
                var typeLabels = @json($leaveStats['by_type']['labels'] ?? ['Sick Leave', 'Vacation Leave', 'Emergency Leave']);
                var typeData = @json($leaveStats['by_type']['data'] ?? [3, 2, 2]);
                
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: typeLabels,
                        datasets: [{
                            data: typeData,
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
                        }]
                    }
                });
            }

            // Status Chart
            var statusCanvas = document.getElementById('statusChart');
            if (statusCanvas) {
                var statusCtx = statusCanvas.getContext('2d');
                var statusLabels = @json($leaveStats['by_status']['labels'] ?? ['Pending', 'Approved', 'Rejected']);
                var statusData = @json($leaveStats['by_status']['data'] ?? [2, 4, 1]);
                
                new Chart(statusCtx, {
                    type: 'bar',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusData,
                            backgroundColor: ['#f59e0b', '#10b981', '#ef4444']
                        }]
                    }
                });
            }
        }, 100);
    });
</script>
@endpush