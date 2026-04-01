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

<div class="max-w-7xl mx-auto py-8 px-4">
    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Employee Self-Service</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Leave Application</h1>
            </div>
        </div>
    </div>

    {{-- COLLAPSIBLE FORM --}}
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
                <h2 class="text-lg font-bold text-gray-800">Leave Entry</h2>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="open ? 'Minimize' : 'File a Leave'"></span>
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
            <form wire:submit.prevent="save" class="space-y-6">
                {{-- Row 1: Leave Type & Credits --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Nature of Leave *</label>
                        <select 
                            wire:model.live="leave_type"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option value="">Select Type…</option>
                            <option value="Vacation Leave">Vacation Leave (VL)</option>
                            <option value="Sick Leave">Sick Leave (SL)</option>
                            <option value="Pay-Off">Pay-Off</option>
                            <option value="Compassionate Leave">Compassionate Leave</option>
                            <option value="Leave Without Pay">Leave Without Pay (LWOP)</option>
                            <option value="Birthday Leave">Birthday Leave</option>
                            <option value="Single Parent Leave">Single Parent Leave</option>
                            <option value="Maternity Leave">Maternity Leave</option>
                            <option value="Paternity Leave">Paternity Leave</option>
                        </select>
                        @error('leave_type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 brand-bg-primary-light rounded-md border border-blue-100 px-4 py-3 flex items-center justify-between"
                         wire:key="credits-panel-{{ $leave_type }}">
                        <div>
                            <p class="text-[10px] font-bold brand-text-primary uppercase tracking-wide">
                                {{ $creditLabel }}
                            </p>
                            <p class="text-xl font-bold text-gray-800 mt-0.5">
                                @if($showCredits)
                                    {{ $availableCredits }}
                                    <span class="text-sm font-semibold text-gray-500">Days</span>
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
                {{-- ↑ Row 1 grid closed here (was missing before) --}}

                {{-- Row 2: Dates & Duration --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Start Date *</label>
                        <input type="date" wire:model.live="start_date"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('start_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">End Date *</label>
                        <input type="date" wire:model.live="end_date"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('end_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Duration</label>
                        <div class="flex items-center gap-2">
                            <select wire:model.live="day_part"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                <option value="Full">Full Day</option>
                                <option value="AM">AM Half</option>
                                <option value="PM">PM Half</option>
                            </select>
                            <div class="px-3 py-2 brand-bg-teal-light rounded-md font-bold brand-text-teal text-sm shrink-0 border border-teal-100 whitespace-nowrap">
                                {{ $total_days ?? 0 }}d
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 3: Reason & Reliever --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reason / Justification *</label>
                        <textarea wire:model="reason" rows="3"
                                  placeholder="Briefly explain the purpose of your leave..."
                                  class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 resize-none"></textarea>
                        @error('reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Designated Reliever</label>
                        <input type="text" wire:model.live="reliever"
                            placeholder="e.g. Juan dela Cruz"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        <p class="text-[10px] text-gray-400 mt-1.5 italic font-medium leading-tight">
                            Reliever will be notified to cover your duties during your absence.
                        </p>
                        @error('reliever') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Row 4: Attachment --}}
                <div class="pt-2 border-t border-gray-100">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">
                        Attachment <span class="text-gray-400 normal-case font-normal">(Optional — Medical Certificate, etc.)</span>
                    </label>
                    <label class="flex items-center justify-center w-full h-20 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-teal-light">
                                <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
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

                {{-- Form Footer --}}
                <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-2">
                    <button type="button" @click="open = false"
                        class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold py-2 px-10 rounded shadow-md
                               transition-all active:scale-95 flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">Save Item</span>
                        <span
                            wire:loading="wire:loading"
                            wire:target="save"
                            class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"/>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Saving…
                        </span>
                    </button>
                </div>

            </form>
                                    <button wire:click="save" type="button">TEST SAVE</button>

        </div>
    </div>

    {{-- MY LEAVE APPLICATIONS TABLE --}}
    <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        {{-- Table Header --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">My Leave Applications</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    {{ $leaves->count() }} {{ Str::plural('record', $leaves->count()) }}
                </span>
            </div>

            <div class="relative">
                <input
                    wire:model.debounce.300ms="search"
                    type="text"
                    placeholder="Search applications…"
                    class="search-focus pl-4 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg transition-all w-56"
                />
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Filed On</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dept Head</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">HR Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Feedback</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

                    @php
                        $deptBadge = [
                            'approved' => 'background-color:#dcfce7;color:#166534;border:1px solid #86efac;',
                            'rejected' => 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                            'pending'  => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                        ];
                        $hrBadge = [
                            'approved'  => 'background-color:#dcfce7;color:#166534;border:1px solid #86efac;',
                            'rejected'  => 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                            'pending'   => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                            'cancelled' => 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;',
                        ];
                        // Keys now match the full leave_type values stored in DB
                        $typeBadge = [
                            'Vacation Leave'     => 'background-color:#e6f4f5;color:#027c8b;border:1px solid #a5d8dd;',
                            'Sick Leave'         => 'background-color:#ede9fe;color:#6b21a8;border:1px solid #c4b5fd;',
                            'Pay-Off'            => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                            'Compassionate Leave'=> 'background-color:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                            'Leave Without Pay'  => 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;',
                            'Birthday Leave'     => 'background-color:#fce7f3;color:#9d174d;border:1px solid #f9a8d4;',
                            'Single Parent Leave'=> 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                            'Maternity Leave'    => 'background-color:#fce7f3;color:#9d174d;border:1px solid #f9a8d4;',
                            'Paternity Leave'    => 'background-color:#e6f4f5;color:#027c8b;border:1px solid #a5d8dd;',
                        ];
                    @endphp

                    @forelse($leaves as $leave)
                        <tr class="brand-row-hover transition-colors">

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="{{ $typeBadge[$leave->leave_type] ?? 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;' }}">
                                    {{ $leave->leave_type }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mt-0.5">
                                    {{ $leave->day_part }} Day
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full brand-bg-teal-light brand-text-teal" style="border:1px solid #a5d8dd;">
                                    {{ $leave->total_days }}d
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($leave->date_requested)->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="{{ $deptBadge[$leave->dept_head_status] ?? $deptBadge['pending'] }}">
                                    {{ ucfirst($leave->dept_head_status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="{{ $hrBadge[$leave->hr_status] ?? $hrBadge['pending'] }}">
                                    {{ ucfirst($leave->hr_status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($leave->rejection_reason)
                                    <span class="text-xs text-red-600 font-medium italic">{{ Str::limit($leave->rejection_reason, 45) }}</span>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">
                                        {{ $search ? 'No applications match your search.' : 'You have not filed any leave applications yet.' }}
                                    </p>
                                    <p class="text-xs mt-1">
                                        {{ $search ? 'Try a different keyword.' : 'Click "File a Leave" above to get started.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
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
            <div class="h-1" style="background-color:#f0b626; animation: shrink 4s linear forwards;"></div>
        </div>
    @endif

</div>