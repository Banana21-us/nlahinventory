<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
    .brand-btn-primary       { background-color: #015581; color: #ffffff; transition: background-color 0.15s ease; }
    .brand-btn-primary:hover { background-color: #01406a; }
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
    @keyframes shrink { from { width: 100% } to { width: 0% } }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Employee</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Pay-off Applications</h1>
                <p class="text-xs text-gray-500 mt-0.5">Log earned pay-off hours to convert into Payoff Leave credits</p>
            </div>
        </div>
    </div>

    {{-- NEW APPLICATION FORM --}}
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden mb-6"
         x-data="{ open: @entangle('showForm') }">

        <button @click="open = !open"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-primary-light">
                    <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" x-show="!open"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                    </svg>
                </div>
                <div class="text-left">
                    <h2 class="text-base font-bold text-gray-800">New Pay-off Application</h2>
                    <p class="text-xs text-gray-400">Click to expand and fill out the form</p>
                </div>
            </div>
            <span class="text-sm font-semibold brand-text-primary" x-text="open ? 'Minimize' : 'Apply Now'"></span>
        </button>

        <div x-show="open" x-collapse class="border-t border-gray-100">
            <div class="p-6 brand-bg-primary-light border-b border-blue-100 flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-primary">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-600">Hours are auto-computed from your start and end date/time. Only pending applications can be edited or deleted.</p>
            </div>
            <form wire:submit.prevent="save" class="p-6 bg-gray-50/30">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start Date & Time *</label>
                        <input type="datetime-local" wire:model.live="start_datetime"
                            class="brand-focus block w-full rounded-lg border border-gray-300 shadow-sm text-sm p-2.5"/>
                        @error('start_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End Date & Time *</label>
                        <input type="datetime-local" wire:model.live="end_datetime"
                            class="brand-focus block w-full rounded-lg border border-gray-300 shadow-sm text-sm p-2.5"/>
                        @error('end_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hours (Auto)</label>
                        <input type="number" step="0.01" wire:model="hours" readonly placeholder="Auto-computed"
                            class="block w-full rounded-lg border border-gray-200 bg-gray-100 text-gray-700 text-sm p-2.5 cursor-not-allowed"/>
                        @error('hours') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason</label>
                        <input type="text" wire:model="reason" placeholder="Brief reason"
                            class="brand-focus block w-full rounded-lg border border-gray-300 shadow-sm text-sm p-2.5"/>
                        @error('reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-between items-center pt-5 border-t border-gray-100 mt-5">
                    <button type="button" wire:click="deductLunchBreak"
                        @class(['flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg border transition-colors',
                            'bg-amber-50 border-amber-300 text-amber-700 hover:bg-amber-100' => !$lunch_break_deducted,
                            'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed' => $lunch_break_deducted])
                        @disabled($lunch_break_deducted)>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $lunch_break_deducted ? 'Lunch Break Deducted' : '− Lunch Break (−1 hr)' }}
                    </button>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="open = false"
                            class="text-sm text-gray-500 hover:text-gray-700 font-semibold px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="brand-btn-primary text-sm font-bold py-2.5 px-8 rounded-lg shadow active:scale-95 flex items-center gap-2">
                            <span wire:loading.remove wire:target="save">Submit Application</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Submitting…
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MY APPLICATIONS --}}
    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">My Applications</h3>
                <span class="text-xs font-semibold text-gray-500 brand-bg-teal-light brand-text-teal px-2.5 py-0.5 rounded-full">
                    {{ $applications->count() }} records
                </span>
            </div>
            <select wire:model.live="filterStatus"
                class="search-focus text-sm bg-white border border-gray-200 rounded-lg py-2 px-3">
                <option value="">All Statuses</option>
                <option value="pending">Pending (Dept Head)</option>
                <option value="dept_approved">Pending HR</option>
                <option value="hr_approved">Pending Accounting</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Start</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">End</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Approval Progress</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($applications as $app)
                        <tr class="brand-row-hover transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">{{ $app->start_datetime->format('M d, Y h:i A') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">{{ $app->end_datetime->format('M d, Y h:i A') }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold brand-text-primary">{{ $app->hours }}h</span>
                                @if($app->lunch_break_deducted)
                                    <span class="text-xs text-amber-600 ml-1">(−1 lunch)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $steps = [
                                        [
                                            'label'    => 'Dept Head',
                                            'status'   => $app->dept_head_status ?? 'pending',
                                            'approver' => $app->deptHeadApprover?->name,
                                        ],
                                        [
                                            'label'    => 'HR',
                                            'status'   => $app->hr_status ?? 'pending',
                                            'approver' => $app->hrApprover?->name,
                                        ],
                                        [
                                            'label'    => 'Accounting',
                                            'status'   => $app->accounting_status ?? 'pending',
                                            'approver' => $app->accountingApprover?->name,
                                        ],
                                    ];
                                    // If overall status is rejected, mark remaining steps as skipped
                                    $rejected = $app->status === 'rejected';
                                @endphp
                                <div class="flex items-center gap-1">
                                    @foreach($steps as $i => $step)
                                        @php
                                            $s = $step['status'];
                                            if ($rejected && $s === 'pending') {
                                                $dot   = 'bg-gray-200 text-gray-400';
                                                $icon  = '—';
                                                $tip   = 'Skipped';
                                            } elseif ($s === 'approved') {
                                                $dot  = 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300';
                                                $icon = '✓';
                                                $tip  = $step['approver'] ?? 'Approved';
                                            } elseif ($s === 'rejected') {
                                                $dot  = 'bg-red-100 text-red-600 ring-1 ring-red-300';
                                                $icon = '✗';
                                                $tip  = $step['approver'] ?? 'Rejected';
                                            } else {
                                                $dot  = 'bg-amber-50 text-amber-500 ring-1 ring-amber-300';
                                                $icon = '⏳';
                                                $tip  = 'Awaiting '.$step['label'];
                                            }
                                        @endphp
                                        <div class="flex items-center gap-1">
                                            <div title="{{ $tip }}"
                                                 class="flex flex-col items-center cursor-default group relative">
                                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $dot }}">
                                                    {{ $icon }}
                                                </span>
                                                <span class="text-[9px] font-semibold text-gray-400 mt-0.5 leading-none">{{ $step['label'] }}</span>
                                                {{-- Tooltip --}}
                                                @if($step['approver'])
                                                    <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block z-10 bg-gray-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap shadow">
                                                        {{ $step['approver'] }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if(!$loop->last)
                                                <span class="text-gray-300 text-xs font-bold mb-3">›</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                @if($app->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit({{ $app->id }})"
                                            class="brand-edit-btn rounded-md px-2.5 py-1.5 text-xs font-semibold shadow-sm transition-colors">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $app->id }})"
                                            class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No pay-off applications yet.</p>
                                    <p class="text-xs mt-1">Click "Apply Now" above to log your earned hours.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DELETE CONFIRM MODAL --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/50" wire:click="$set('confirmingDeletion', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm">
                    <div class="px-6 pt-6 pb-4 flex items-start gap-4">
                        <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-red-100">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Delete Application</h3>
                            <p class="mt-1 text-sm text-gray-500">This action cannot be undone.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                        <button wire:click="delete"
                            class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-500 active:scale-95">
                            Delete
                        </button>
                        <button wire:click="$set('confirmingDeletion', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- EDIT MODAL --}}
    @if($isEditing)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/50" wire:click="$set('isEditing', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-xl">
                    <form wire:submit.prevent="update">
                        <div class="px-6 py-4 border-b border-gray-200 brand-bg-teal-light flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg brand-bg-teal">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-800">Edit Pay-off Application</h3>
                            </div>
                            <button type="button" wire:click="$set('isEditing', false)" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start Date & Time *</label>
                                <input type="datetime-local" wire:model.live="start_datetime"
                                    class="brand-focus block w-full rounded-lg border border-gray-300 text-sm p-2.5"/>
                                @error('start_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End Date & Time *</label>
                                <input type="datetime-local" wire:model.live="end_datetime"
                                    class="brand-focus block w-full rounded-lg border border-gray-300 text-sm p-2.5"/>
                                @error('end_datetime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hours (Auto)</label>
                                <input type="number" step="0.01" wire:model="hours" readonly
                                    class="block w-full rounded-lg border border-gray-200 bg-gray-100 text-gray-700 text-sm p-2.5 cursor-not-allowed"/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason</label>
                                <input type="text" wire:model="reason"
                                    class="brand-focus block w-full rounded-lg border border-gray-300 text-sm p-2.5"/>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center gap-3 rounded-b-xl">
                            <button type="button" wire:click="deductLunchBreak"
                                @class(['flex items-center gap-2 text-sm font-semibold px-3 py-2 rounded-lg border transition-colors',
                                    'bg-amber-50 border-amber-300 text-amber-700 hover:bg-amber-100' => !$lunch_break_deducted,
                                    'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed' => $lunch_break_deducted])
                                @disabled($lunch_break_deducted)>
                                {{ $lunch_break_deducted ? '✓ Lunch Deducted' : '− Lunch (−1 hr)' }}
                            </button>
                            <div class="flex gap-3">
                                <button type="button" wire:click="$set('isEditing', false)"
                                    class="px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="brand-btn-teal text-sm font-bold px-6 py-2.5 rounded-lg shadow active:scale-95 flex items-center gap-2">
                                    <span wire:loading.remove wire:target="update">Save Changes</span>
                                    <span wire:loading wire:target="update" class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                        Saving…
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- TOAST --}}
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Success</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ session('message') }}</p>
                </div>
            </div>
            <div class="h-1" style="background-color:#f0b626;animation:shrink 4s linear forwards;"></div>
        </div>
    @endif

</div>
