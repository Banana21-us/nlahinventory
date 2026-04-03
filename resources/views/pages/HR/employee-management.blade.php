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
    @keyframes beat {
        0%, 100% { transform: scale(1);   opacity: 1; }
        40%       { transform: scale(1.5); opacity: 1; }
        60%       { transform: scale(.9);  opacity: .8; }
    }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">HR</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Employee Management</h1>
            </div>
        </div>
    </div>

    {{-- ADD EMPLOYEE COLLAPSIBLE FORM --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden"
         x-data="{ open: @entangle('showForm'), tab: 'personal' }">

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
                <h2 class="text-lg font-bold text-gray-800">Employee Entry</h2>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="open ? 'Minimize' : 'Add New Employee'"></span>
        </button>

        <div x-show="open" x-collapse
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="border-t border-gray-100">

            {{-- Tabs --}}
            <div class="flex border-b border-gray-200 bg-gray-50/50">
                <button type="button" @click="tab = 'personal'"
                    :class="tab === 'personal' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm transition-colors">
                    Personal Info
                </button>
                <button type="button" @click="tab = 'employment'"
                    :class="tab === 'employment' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm transition-colors">
                    Employment Details
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 bg-gray-50/30">

                {{-- TAB: Personal Info --}}
                <div x-show="tab === 'personal'" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employee Number *</label>
                        <input type="text" wire:model="employee_number" placeholder="e.g. EMP-0001"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('employee_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Link to System User</label>
                        <select wire:model="user_id"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option value="">— None —</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} {{ $u->employee_number ? "({$u->employee_number})" : '' }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Biometric ID</label>
                        <input type="text" wire:model="biometric_id" placeholder="ZKTeco ID"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Last Name *</label>
                        <input type="text" wire:model="last_name"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">First Name *</label>
                        <input type="text" wire:model="first_name"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Middle Name</label>
                        <input type="text" wire:model="middle_name"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Extension (Jr., Sr.)</label>
                        <input type="text" wire:model="extension" placeholder="Jr."
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Birth Date *</label>
                        <input type="date" wire:model="birth_date"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('birth_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Place of Birth</label>
                        <input type="text" wire:model="place_of_birth"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Gender *</label>
                        <select wire:model="gender"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Civil Status</label>
                        <select wire:model="civil_status"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option value="">— Select —</option>
                            <option>Single</option>
                            <option>Married</option>
                            <option>Widowed</option>
                            <option>Separated</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Citizenship</label>
                        <input type="text" wire:model="citizenship"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Religion</label>
                        <input type="text" wire:model="religion"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Blood Type</label>
                        <select wire:model="blood_type"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option value="">— Select —</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                <option value="{{ $bt }}">{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Height (cm)</label>
                        <input type="text" wire:model="height" placeholder="e.g. 165"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Weight (kg)</label>
                        <input type="text" wire:model="weight" placeholder="e.g. 60"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Mobile No.</label>
                        <input type="text" wire:model="mobile_no" placeholder="09XX XXX XXXX"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Telephone</label>
                        <input type="text" wire:model="telephone"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Email Address</label>
                        <input type="email" wire:model="email_add"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Permanent Address</label>
                        <textarea wire:model="p_address" rows="2"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"></textarea>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Current Address</label>
                        <textarea wire:model="c_address" rows="2"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Emergency Contact Person</label>
                        <input type="text" wire:model="contact_person"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Emergency Contact Number</label>
                        <input type="text" wire:model="contact_number"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div class="md:col-span-3 flex justify-end pt-4 border-t border-gray-100 mt-2">
                        <button type="button" @click="tab = 'employment'"
                            class="brand-btn-teal text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95">
                            Next: Employment Details →
                        </button>
                    </div>
                </div>

                {{-- TAB: Employment Details --}}
                <div x-show="tab === 'employment'" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Department *</label>
                        <select wire:model="department_id"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option value="">— Select Department —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Position *</label>
                        <input type="text" wire:model="position"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('position') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Rank</label>
                        <input type="text" wire:model="rank" placeholder="e.g. SG-15"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employment Status *</label>
                        <select wire:model="employment_status"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option>Probationary</option>
                            <option>Regular</option>
                            <option>Contractual</option>
                            <option>Casual</option>
                        </select>
                        @error('employment_status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hiring Date *</label>
                        <input type="date" wire:model="hiring_date"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @error('hiring_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Regularization Date</label>
                        <input type="date" wire:model="regularization_date"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">PRC License No.</label>
                        <input type="text" wire:model="license_no"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">License Expiry</label>
                        <input type="date" wire:model="license_expiry"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div class="flex items-center gap-3 pt-5">
                        <input type="checkbox" wire:model="re_membership" id="re_membership_add"
                            class="w-4 h-4 rounded border-gray-300 brand-text-primary">
                        <label for="re_membership_add" class="text-sm font-medium text-gray-700">RE Membership</label>
                    </div>

                    <div class="md:col-span-3 mt-2 pt-4 border-t border-gray-100">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">Government IDs</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">PhilHealth No.</label>
                                <input type="text" wire:model="philhealth_no"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Pag-IBIG No.</label>
                                <input type="text" wire:model="pagibig_no"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">TIN No.</label>
                                <input type="text" wire:model="tin_no"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">SSS No.</label>
                                <input type="text" wire:model="sss_no"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">GSIS No.</label>
                                <input type="text" wire:model="gsis_no"
                                    class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-3 flex justify-between items-center pt-4 border-t border-gray-100 mt-2">
                        <button type="button" @click="tab = 'personal'"
                            class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                            ← Back
                        </button>
                        <div class="flex gap-3">
                            <button type="button" @click="open = false"
                                class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                                Cancel
                            </button>
                            <button type="submit"
                                class="brand-btn-primary text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95 flex items-center gap-2">
                                <span wire:loading.remove wire:target="save">Save Employee</span>
                                <span wire:loading wire:target="save" class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Saving…
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- EMPLOYEE TABLE --}}
    <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Employee List</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    {{ $employees->count() }} {{ Str::plural('employee', $employees->count()) }}
                </span>
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search employees…"
                    class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg transition-all w-56"/>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hired</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($employees as $emp)
                        @php
                            $statusStyles = [
                                'Regular'      => 'background-color:#dcfce7;color:#166534;border:1px solid #86efac;',
                                'Probationary' => 'background-color:#fef9c3;color:#854d0e;border:1px solid #fde047;',
                                'Contractual'  => 'background-color:#e6f4f5;color:#027c8b;border:1px solid #a5d8dd;',
                                'Casual'       => 'background-color:#f3f4f6;color:#374151;border:1px solid #d1d5db;',
                            ];
                            $status = $emp->employmentDetail?->employment_status;
                        @endphp
                        <tr class="brand-row-hover transition-colors cursor-pointer"
                            wire:click="view({{ $emp->id }})">

                            <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-600">
                                {{ $emp->employee_number }}
                            </td>

                            <!-- under development -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 brand-bg-primary">
                                            {{ strtoupper(substr($emp->last_name, 0, 1)) }}
                                            
                                        </div>

                                        
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold text-gray-900">
                                            {{ $emp->last_name }}, {{ $emp->first_name }} {{ $emp->middle_name ? substr($emp->middle_name,0,1).'.' : '' }}
                                            
                                            @if(is_null($emp->user_id))
                                            <span class="absolute -top-2 -right-2 text-red-500 text-[500px] font-black text-sm leading-none select-none"
                                                style="animation: beat 1s ease-in-out infinite;"> &ensp; !</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $emp->gender }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->employmentDetail?->department?->name ?? '—' }}</td>

                            <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->employmentDetail?->position ?? '—' }}</td>

                            <td class="px-6 py-4">
                                @if($status)
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          style="{{ $statusStyles[$status] ?? 'background-color:#f3f4f6;color:#374151;' }}">
                                        {{ $status }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $emp->employmentDetail?->hiring_date?->format('M d, Y') ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2" @click.stop>
                                <button wire:click="edit({{ $emp->id }})"
                                    class="brand-edit-btn rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm transition-colors">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $emp->id }})"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-sm font-medium">
                                        {{ $search ? 'No employees match your search.' : 'No employees found.' }}
                                    </p>
                                    <p class="text-xs mt-1">
                                        {{ $search ? 'Try a different keyword.' : 'Click "Add New Employee" above to get started.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- VIEW MODAL (row click) --}}
    @if($isViewing && $viewEmployee)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('isViewing', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl w-full max-w-3xl"
                     style="border-top: 4px solid #015581;">

                    {{-- Header --}}
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-xl brand-bg-primary shrink-0">
                                    {{ strtoupper(substr($viewEmployee->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $viewEmployee->last_name }}, {{ $viewEmployee->first_name }} {{ $viewEmployee->middle_name }}
                                        @if($viewEmployee->extension) {{ $viewEmployee->extension }} @endif
                                    </h3>
                                    <p class="text-sm text-gray-500 font-mono">{{ $viewEmployee->employee_number }}</p>
                                    @if($viewEmployee->employmentDetail)
                                        <p class="text-sm brand-text-teal font-semibold">{{ $viewEmployee->employmentDetail->position }} — {{ $viewEmployee->employmentDetail->department?->name ?? '—' }}</p>
                                    @endif
                                </div>
                            </div>
                            <button wire:click="$set('isViewing', false)" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-5 overflow-y-auto max-h-[65vh]">
                        @php
                            $detail = $viewEmployee->employmentDetail;
                        @endphp

                        {{-- Personal Info --}}
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Personal Information</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3 text-sm mb-6">
                            <div><span class="block text-xs text-gray-400 font-semibold">Birth Date</span>{{ $viewEmployee->birth_date?->format('M d, Y') ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Place of Birth</span>{{ $viewEmployee->place_of_birth ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Gender</span>{{ $viewEmployee->gender }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Civil Status</span>{{ $viewEmployee->civil_status ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Citizenship</span>{{ $viewEmployee->citizenship ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Religion</span>{{ $viewEmployee->religion ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Blood Type</span>{{ $viewEmployee->blood_type ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Height</span>{{ $viewEmployee->height ? $viewEmployee->height.' cm' : '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Weight</span>{{ $viewEmployee->weight ? $viewEmployee->weight.' kg' : '—' }}</div>
                        </div>

                        {{-- Contact --}}
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Contact Details</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3 text-sm mb-6">
                            <div><span class="block text-xs text-gray-400 font-semibold">Mobile</span>{{ $viewEmployee->mobile_no ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Telephone</span>{{ $viewEmployee->telephone ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Email</span>{{ $viewEmployee->email_add ?? '—' }}</div>
                            <div class="col-span-2 md:col-span-3"><span class="block text-xs text-gray-400 font-semibold">Permanent Address</span>{{ $viewEmployee->p_address ?? '—' }}</div>
                            <div class="col-span-2 md:col-span-3"><span class="block text-xs text-gray-400 font-semibold">Current Address</span>{{ $viewEmployee->c_address ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Emergency Contact</span>{{ $viewEmployee->contact_person ?? '—' }}</div>
                            <div><span class="block text-xs text-gray-400 font-semibold">Emergency Number</span>{{ $viewEmployee->contact_number ?? '—' }}</div>
                        </div>

                        @if($detail)
                            {{-- Employment --}}
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Employment Details</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3 text-sm mb-6">
                                <div><span class="block text-xs text-gray-400 font-semibold">Department</span>{{ $detail->department?->name ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">Position</span>{{ $detail->position }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">Rank</span>{{ $detail->rank ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">Status</span>{{ $detail->employment_status }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">Hiring Date</span>{{ $detail->hiring_date?->format('M d, Y') }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">Regularization Date</span>{{ $detail->regularization_date?->format('M d, Y') ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">PRC License No.</span>{{ $detail->license_no ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">License Expiry</span>{{ $detail->license_expiry?->format('M d, Y') ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">RE Membership</span>{{ $detail->re_membership ? 'Yes' : 'No' }}</div>
                            </div>

                            {{-- Government IDs --}}
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Government IDs</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3 text-sm">
                                <div><span class="block text-xs text-gray-400 font-semibold">PhilHealth No.</span>{{ $detail->philhealth_no ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">Pag-IBIG No.</span>{{ $detail->pagibig_no ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">TIN No.</span>{{ $detail->tin_no ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">SSS No.</span>{{ $detail->sss_no ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">GSIS No.</span>{{ $detail->gsis_no ?? '—' }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-xl">
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $viewEmployee->id }}); $set('isViewing', false)"
                                class="brand-edit-btn rounded-lg px-4 py-2 text-sm font-bold shadow-sm transition-colors">
                                Edit Employee
                            </button>
                            <button wire:click="confirmDelete({{ $viewEmployee->id }}); $set('isViewing', false)"
                                class="text-red-500 hover:text-red-700 text-sm font-semibold px-4 py-2 transition-colors">
                                Delete
                            </button>
                        </div>
                        <button wire:click="$set('isViewing', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- EDIT MODAL --}}
    @if($isEditing)
        <div class="fixed inset-0 z-50 overflow-y-auto"
             x-data="{ tab: 'personal' }">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('isEditing', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl w-full max-w-3xl"
                     style="border-top: 4px solid #027c8b;">
                    <form wire:submit.prevent="update">

                        {{-- Modal Header --}}
                        <div class="bg-white px-6 pt-6 pb-0">
                            <div class="flex items-center mb-4">
                                <div class="p-2 rounded-lg mr-3 brand-bg-teal-light">
                                    <svg class="w-5 h-5 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Update Employee Information</h3>
                            </div>
                        </div>

                        {{-- Edit Tabs --}}
                        <div class="flex border-b border-gray-200 bg-gray-50/50 px-6">
                            <button type="button" @click="tab = 'personal'"
                                :class="tab === 'personal' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-3 text-sm transition-colors -mb-px">
                                Personal Info
                            </button>
                            <button type="button" @click="tab = 'employment'"
                                :class="tab === 'employment' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-3 text-sm transition-colors -mb-px">
                                Employment Details
                            </button>
                        </div>

                        <div class="px-6 py-5 overflow-y-auto max-h-[60vh]">

                            {{-- Edit: Personal --}}
                            <div x-show="tab === 'personal'" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employee Number *</label>
                                    <input type="text" wire:model="employee_number"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('employee_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Link to System User</label>
                                    <select wire:model="user_id"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                        <option value="">— None —</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} {{ $u->employee_number ? "({$u->employee_number})" : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Biometric ID</label>
                                    <input type="text" wire:model="biometric_id"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Last Name *</label>
                                    <input type="text" wire:model="last_name"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">First Name *</label>
                                    <input type="text" wire:model="first_name"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Middle Name</label>
                                    <input type="text" wire:model="middle_name"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Extension</label>
                                    <input type="text" wire:model="extension" placeholder="Jr."
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Birth Date *</label>
                                    <input type="date" wire:model="birth_date"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('birth_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Place of Birth</label>
                                    <input type="text" wire:model="place_of_birth"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Gender *</label>
                                    <select wire:model="gender"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Civil Status</label>
                                    <select wire:model="civil_status"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                        <option value="">— Select —</option>
                                        <option>Single</option>
                                        <option>Married</option>
                                        <option>Widowed</option>
                                        <option>Separated</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Citizenship</label>
                                    <input type="text" wire:model="citizenship"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Religion</label>
                                    <input type="text" wire:model="religion"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Blood Type</label>
                                    <select wire:model="blood_type"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                        <option value="">— Select —</option>
                                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                            <option value="{{ $bt }}">{{ $bt }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Height (cm)</label>
                                    <input type="text" wire:model="height"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Weight (kg)</label>
                                    <input type="text" wire:model="weight"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Mobile No.</label>
                                    <input type="text" wire:model="mobile_no"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Telephone</label>
                                    <input type="text" wire:model="telephone"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Email Address</label>
                                    <input type="email" wire:model="email_add"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Permanent Address</label>
                                    <textarea wire:model="p_address" rows="2"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Current Address</label>
                                    <textarea wire:model="c_address" rows="2"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Emergency Contact Person</label>
                                    <input type="text" wire:model="contact_person"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Emergency Contact Number</label>
                                    <input type="text" wire:model="contact_number"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                            </div>

                            {{-- Edit: Employment --}}
                            <div x-show="tab === 'employment'" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Department *</label>
                                    <select wire:model="department_id"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                        <option value="">— Select Department —</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Position *</label>
                                    <input type="text" wire:model="position"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('position') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Rank</label>
                                    <input type="text" wire:model="rank"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Employment Status *</label>
                                    <select wire:model="employment_status"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                        <option>Probationary</option>
                                        <option>Regular</option>
                                        <option>Contractual</option>
                                        <option>Casual</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Hiring Date *</label>
                                    <input type="date" wire:model="hiring_date"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @error('hiring_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Regularization Date</label>
                                    <input type="date" wire:model="regularization_date"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">PRC License No.</label>
                                    <input type="text" wire:model="license_no"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">License Expiry</label>
                                    <input type="date" wire:model="license_expiry"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div class="flex items-center gap-3 pt-5">
                                    <input type="checkbox" wire:model="re_membership" id="re_membership_edit"
                                        class="w-4 h-4 rounded border-gray-300">
                                    <label for="re_membership_edit" class="text-sm font-medium text-gray-700">RE Membership</label>
                                </div>

                                <div class="md:col-span-2 mt-2 pt-4 border-t border-gray-100">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">Government IDs</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">PhilHealth No.</label>
                                            <input type="text" wire:model="philhealth_no"
                                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Pag-IBIG No.</label>
                                            <input type="text" wire:model="pagibig_no"
                                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">TIN No.</label>
                                            <input type="text" wire:model="tin_no"
                                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">SSS No.</label>
                                            <input type="text" wire:model="sss_no"
                                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">GSIS No.</label>
                                            <input type="text" wire:model="gsis_no"
                                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                            <button type="submit"
                                class="brand-btn-teal inline-flex justify-center rounded-lg px-5 py-2 text-sm font-bold shadow-sm active:scale-95 items-center gap-2">
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

    {{-- DELETE CONFIRMATION MODAL --}}
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
                                <h3 class="text-base font-bold text-gray-900">Delete Employee</h3>
                                <p class="mt-1.5 text-sm text-gray-500">
                                    Are you sure you want to remove this employee? Their employment details will also be deleted. This action cannot be undone.
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
