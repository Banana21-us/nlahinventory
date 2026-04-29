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
            <div class="border-b border-gray-200 bg-gray-50/50">
                <div class="grid grid-cols-2 gap-1 p-1 sm:flex sm:items-center sm:gap-0">
                    <button type="button" @click="tab = 'personal'"
                        :class="tab === 'personal' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                        class="w-full px-4 py-3 text-xs sm:text-sm transition-colors rounded-sm sm:rounded-none">
                        Personal Info
                    </button>
                    <button type="button" @click="tab = 'employment'"
                        :class="tab === 'employment' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                        class="w-full px-4 py-3 text-xs sm:text-sm transition-colors rounded-sm sm:rounded-none">
                        Employment Details
                    </button>
                    <button type="button" @click="tab = 'finance'"
                        :class="tab === 'finance' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                        class="w-full px-4 py-3 text-xs sm:text-sm transition-colors rounded-sm sm:rounded-none">
                        Finance Details
                    </button>
                    <button type="button" @click="tab = 'dependents'"
                        :class="tab === 'dependents' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                        class="w-full px-4 py-3 text-xs sm:text-sm transition-colors rounded-sm sm:rounded-none">
                        Dependents Details
                    </button>
                </div>
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
                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Emergency Contact Relationship</label>
                                    <input type="text" wire:model="contact_relationship"
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

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">Position(s) * <span class="text-gray-400 font-normal normal-case">(check all that apply)</span></label>
                        <div class="flex flex-wrap gap-x-6 gap-y-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            @foreach($positions as $pos)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="selectedPositions" value="{{ $pos->name }}"
                                        class="rounded border-gray-300 text-[#015581] focus:ring-[#015581]"/>
                                    <span class="text-sm text-gray-700">{{ $pos->name }}</span>
                                </label>
                            @endforeach
                        </div>
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

                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" wire:model="is_solo_parent" id="is_solo_parent_create"
                               class="w-4 h-4 rounded border-gray-300 accent-[#015581] cursor-pointer"/>
                        <label for="is_solo_parent_create" class="text-xs font-bold uppercase tracking-wide text-gray-500 cursor-pointer select-none">
                            Solo Parent
                            <span class="normal-case font-normal text-gray-400 ml-1">(RA 8972 — 7 days SPL)</span>
                        </label>
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

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Religion Membership</label>
                        <input type="text" wire:model="re_membership" placeholder="Local church or Denomination"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
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

                    <div class="md:col-span-3 mt-4 mb-1">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">System Access</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Access Key</label>
                        <select wire:model="access_key_id"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                            <option value="">— None (waiting area) —</option>
                            @foreach($accessKeys as $key)
                                <option value="{{ $key->id }}">{{ $key->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Determines what the user can open after login.</p>
                    </div>

                    <div class="md:col-span-3 flex justify-between items-center pt-4 border-t border-gray-100 mt-2">
                        <button type="button" @click="tab = 'personal'"
                            class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                            ← Back
                        </button>
                        <button type="button" @click="tab = 'finance'"
                            class="brand-btn-teal text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95">
                            Next: Finance Details →
                        </button>
                    </div>
                </div>

                {{-- TAB: Finance Details --}}
                <div x-show="tab === 'finance'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3 mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Compensation</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Wage Factor (₱)</label>
                        <input type="number" step="0.01" wire:model.live="wage_factor"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                            placeholder="e.g. 30000"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                            Active Percentage Rate (%) <span class="text-gray-400 font-normal normal-case">e.g. 47</span>
                        </label>
                        <input type="number" step="0.01" min="0" max="100" wire:model.live="salary_rate"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                            placeholder="e.g. 47"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                            Monthly Rate <span class="text-teal-500 font-normal normal-case">(% × Wage Factor)</span>
                        </label>
                        <input type="number" step="0.01" wire:model="monthly_rate" disabled
                            class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                            Daily Rate <span class="text-teal-500 font-normal normal-case">(monthly × 12 / 262)</span>
                        </label>
                        <input type="number" step="0.01" wire:model="daily_rate" disabled
                            class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Probi Rate</label>
                        <input type="number" step="0.01" wire:model="probi_rate"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Grocery Allowance</label>
                        <input type="number" step="0.01" wire:model.live="grocery_allowance"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">COLA</label>
                        <input type="number" step="0.01" wire:model.live="cola"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                            Total Compensation <span class="text-teal-500 font-normal normal-case">(auto)</span>
                        </label>
                        <input type="number" step="0.01" disabled
                            :value="((parseFloat($wire.monthly_rate) || 0) + (parseFloat($wire.grocery_allowance) || 0) + (parseFloat($wire.cola) || 0)).toFixed(2)"
                            class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                    </div>

                    <div class="md:col-span-3 mt-4 mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Percentage Rate Scale</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Min Rate (%)</label>
                        <input type="number" step="0.01" min="0" max="100" wire:model="min_scale"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                            placeholder="e.g. 47"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Max Rate (%)</label>
                        <input type="number" step="0.01" min="0" max="100" wire:model="max_scale"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                            placeholder="e.g. 82"/>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Night Diff Factor</label>
                        <input type="number" step="0.01" wire:model="night_diff_factor"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>



                    {{-- Leave Balances --}}
                    <div class="md:col-span-3 mt-4 mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Leave Balances</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Vacation Leave Total</label>
                        <input type="number" step="0.01" min="0" wire:model="vl_total"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Vacation Leave Consumed</label>
                        <input type="number" step="0.01" min="0" wire:model="vl_consumed"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Sick Leave Total</label>
                        <input type="number" step="0.01" min="0" wire:model="sl_total"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Sick Leave Consumed</label>
                        <input type="number" step="0.01" min="0" wire:model="sl_consumed"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Birthday Leave Total</label>
                        <input type="number" step="0.01" min="0" wire:model="bl_total"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Birthday Leave Consumed</label>
                        <input type="number" step="0.01" min="0" wire:model="bl_consumed"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Solo Parent Leave Total</label>
                        <input type="number" step="0.01" min="0" wire:model="spl_total"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Solo Parent Leave Consumed</label>
                        <input type="number" step="0.01" min="0" wire:model="spl_consumed"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Bereavement Leave Total</label>
                        <input type="number" step="0.01" min="0" wire:model="syl_total"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Bereavement Leave Consumed</label>
                        <input type="number" step="0.01" min="0" wire:model="syl_consumed"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Maternity Leave Total <span class="text-gray-400 font-normal normal-case">(Female)</span></label>
                        <input type="number" step="0.01" min="0" wire:model="ml_total"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Maternity Leave Consumed <span class="text-gray-400 font-normal normal-case">(Female)</span></label>
                        <input type="number" step="0.01" min="0" wire:model="ml_consumed"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Paternity Leave Total <span class="text-gray-400 font-normal normal-case">(Male)</span></label>
                        <input type="number" step="0.01" min="0" wire:model="pl_total"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Paternity Leave Consumed <span class="text-gray-400 font-normal normal-case">(Male)</span></label>
                        <input type="number" step="0.01" min="0" wire:model="pl_consumed"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    </div>
                    <div></div>

                    {{-- Picture & Signature --}}
                    <div class="md:col-span-3 mt-4 mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Photo & Signature</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Picture</label>
                        <input type="file" wire:model="picture" accept="image/*"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @if($picture)
                            <div class="mt-2 relative w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ $picture->temporaryUrl() }}" alt="Picture preview" class="w-full h-full object-cover"/>
                            </div>
                        @endif
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Signature</label>
                        <input type="file" wire:model="signature" accept="image/*"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                        @if($signature)
                            <div class="mt-2 relative w-20 h-12 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ $signature->temporaryUrl() }}" alt="Signature preview" class="w-full h-full object-cover"/>
                            </div>
                        @endif
                    </div>

                    <div class="md:col-span-3 flex justify-between items-center pt-4 border-t border-gray-100 mt-2">
                        <button type="button" @click="tab = 'employment'"
                            class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                            ← Back
                        </button>
                        <button type="button" @click="tab = 'dependents'"
                            class="brand-btn-teal text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95">
                            Next: Dependents →
                        </button>
                    </div>
                </div>

                {{-- TAB: Dependents --}}
                <div x-show="tab === 'dependents'">
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-3">Add New Dependent</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3"
                            x-data="{
                                calcAge(bday) {
                                    if (!bday) return null;
                                    const today = new Date();
                                    const b = new Date(bday);
                                    let age = today.getFullYear() - b.getFullYear();
                                    if (today.getMonth() < b.getMonth() || (today.getMonth() === b.getMonth() && today.getDate() < b.getDate())) age--;
                                    return age >= 0 ? age : null;
                                }
                            }">
                            <input type="text" wire:model="new_dependent.lastname" placeholder="Last Name"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            <input type="text" wire:model="new_dependent.firstname" placeholder="First Name"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            <input type="text" wire:model="new_dependent.middlename" placeholder="Middle Name"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            <input type="text" wire:model="new_dependent.extension" placeholder="Extension (Jr.)"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            <input type="text" wire:model="new_dependent.relationship" placeholder="Relationship"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            <select wire:model="new_dependent.gender"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <input type="date" wire:model="new_dependent.birthday"
                                x-on:change="$wire.set('new_dependent.age', calcAge($event.target.value))"
                                class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                            <input type="number" wire:model="new_dependent.age" placeholder="Age (auto)"
                                disabled
                                class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                        </div>
                        <button type="button" wire:click="addDependent"
                            class="mt-3 brand-btn-primary text-sm font-bold py-1.5 px-4 rounded shadow-sm active:scale-95">
                            Add Dependent
                        </button>
                    </div>

                    @if(count($dependents) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Relationship</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Gender</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Birthday</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Age</th>
                                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($dependents as $index => $dep)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $dep['lastname'] }}, {{ $dep['firstname'] }} {{ $dep['middlename'] }} {{ $dep['extension'] }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">{{ $dep['relationship'] }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $dep['gender'] }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $dep['birthday'] }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $dep['age'] }}</td>
                                            <td class="px-4 py-2 text-right">
                                                <button wire:click="removeDependent({{ $index }})"
                                                    class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">No dependents added yet.</p>
                    @endif

                    <div class="md:col-span-3 flex justify-between items-center pt-4 border-t border-gray-100 mt-4">
                        <button type="button" @click="tab = 'finance'"
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

    {{-- SEARCH BAR --}}
    <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm px-4 py-3 flex items-center gap-3">
        <div class="relative flex-1 min-w-0">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search employees…"
                class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg w-full transition-all"/>
        </div>
        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium whitespace-nowrap flex-shrink-0">
            {{ $employees->count() }} {{ Str::plural('employee', $employees->count()) }}
        </span>
    </div>

    {{-- MOBILE CARD LIST --}}
    <div class="md:hidden mt-4 space-y-3">
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
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <button wire:click="view({{ $emp->id }})" class="w-full text-left px-4 py-3 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0 brand-bg-primary">
                        {{ strtoupper(substr($emp->last_name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-gray-900 truncate">
                            {{ $emp->last_name }}, {{ $emp->first_name }} {{ $emp->middle_name ? substr($emp->middle_name,0,1).'.' : '' }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $emp->employee_number }} · {{ $emp->gender }}</p>
                    </div>
                    @if($status)
                        <span class="flex-shrink-0 px-2 py-0.5 text-xs font-semibold rounded-full"
                              style="{{ $statusStyles[$status] ?? 'background-color:#f3f4f6;color:#374151;' }}">
                            {{ $status }}
                        </span>
                    @endif
                </button>
                <div class="px-4 pb-3 grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-600">
                    <div><span class="font-medium text-gray-400">Dept:</span> {{ $emp->employmentDetail?->department?->name ?? '—' }}</div>
                    <div><span class="font-medium text-gray-400">Position:</span> {{ $emp->employmentDetail?->position ?? '—' }}</div>
                    <div><span class="font-medium text-gray-400">Hired:</span> {{ $emp->employmentDetail?->hiring_date?->format('M d, Y') ?? '—' }}</div>
                </div>
                <div class="bg-gray-50 px-4 py-2.5 flex justify-end gap-3 border-t border-gray-100">
                    <button wire:click="edit({{ $emp->id }})"
                        class="brand-edit-btn rounded-md px-3 py-1.5 text-xs font-semibold shadow-sm transition-colors">
                        Edit
                    </button>
                    <button wire:click="confirmDelete({{ $emp->id }})"
                        class="text-red-500 hover:text-red-700 text-xs font-semibold transition-colors px-1.5 py-1.5">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <p class="text-sm font-medium">{{ $search ? 'No employees match your search.' : 'No employees found.' }}</p>
            </div>
        @endforelse
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-teal-light">
                <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Employee List</h3>
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
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 brand-bg-primary">
                                        {{ strtoupper(substr($emp->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">
                                            {{ $emp->last_name }}, {{ $emp->first_name }} {{ $emp->middle_name ? substr($emp->middle_name,0,1).'.' : '' }}
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
                            <div class="flex items-center gap-1">
                                <a href="{{ route('HR.employees.salary-slip', $viewEmployee->id) }}"
                                   target="_blank"
                                   title="Print Salary Slip"
                                   class="text-gray-400 hover:text-teal-600 transition-colors p-1 rounded">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.75 19.817m.001-5.988.72-.096M6.75 19.817l-1.5-5.656M16.5 13.829c.24.03.48.062.72.096m0 0L18.75 19.817M17.22 13.829l-1.5 5.988M3 8.625c0-1.036.84-1.875 1.875-1.875h13.25C19.16 6.75 20 7.589 20 8.625v2.25c0 1.036-.84 1.875-1.875 1.875H4.875A1.875 1.875 0 0 1 3 10.875v-2.25Z"/>
                                    </svg>
                                </a>
                                <button wire:click="$set('isViewing', false)" class="text-gray-400 hover:text-gray-600 p-1 rounded">
                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                                    </svg>
                                </button>
                            </div>
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
                                <div>
                                    <span class="block text-xs text-gray-400 font-semibold">Solo Parent</span>
                                    @if($viewEmployee?->is_solo_parent)
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 rounded-full px-2 py-0.5">Yes — SPL eligible</span>
                                    @else
                                        <span class="text-sm text-gray-600">No</span>
                                    @endif
                                </div>
                                <div><span class="block text-xs text-gray-400 font-semibold">PRC License No.</span>{{ $detail->license_no ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">License Expiry</span>{{ $detail->license_expiry?->format('M d, Y') ?? '—' }}</div>
                                <div><span class="block text-xs text-gray-400 font-semibold">RE Membership</span>{{ $detail->re_membership ?: '—' }}</div>
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
                            <button type="button" @click="tab = 'finance'"
                                :class="tab === 'finance' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-3 text-sm transition-colors -mb-px">
                                Finance Details
                            </button>
                            <button type="button" @click="tab = 'dependents'"
                                :class="tab === 'dependents' ? 'brand-text-primary border-b-2 border-[#015581] bg-white font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-3 text-sm transition-colors -mb-px">
                                Dependents
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
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Religion Membership</label>
                                    <input type="text" wire:model="re_membership" placeholder="e.g. PRC Number"
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

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Emergency Contact Relationship</label>
                                    <input type="text" wire:model="contact_relationship"
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

                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">Position(s) * <span class="text-gray-400 font-normal normal-case">(check all that apply)</span></label>
                                    <div class="flex flex-wrap gap-x-6 gap-y-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        @foreach($positions as $pos)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" wire:model="selectedPositions" value="{{ $pos->name }}"
                                                    class="rounded border-gray-300 text-[#015581] focus:ring-[#015581]"/>
                                                <span class="text-sm text-gray-700">{{ $pos->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                        @error('selectedPositions') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                                        <option>Regular</option>
                                        <option>Probationary</option>
                                        <option>Contractual</option>
                                        <option>Reliever</option>
                                        <option>Part Time</option>
                                        <option>Outsourced</option>
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

                                <div class="flex items-center gap-3 pt-6">
                                    <input type="checkbox" wire:model="is_solo_parent" id="is_solo_parent_edit"
                                           class="w-4 h-4 rounded border-gray-300 accent-[#015581] cursor-pointer"/>
                                    <label for="is_solo_parent_edit" class="text-xs font-bold uppercase tracking-wide text-gray-500 cursor-pointer select-none">
                                        Solo Parent
                                        <span class="normal-case font-normal text-gray-400 ml-1">(RA 8972 — 7 days SPL)</span>
                                    </label>
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

                            {{-- Edit: Employment Details nav + access key --}}
                            <div x-show="tab === 'employment'" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-0 pt-0 -mt-4">
                                <div class="md:col-span-3 mt-4 mb-1">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">System Access</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Access Key</label>
                                    <select wire:model="access_key_id"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                        <option value="">— None (waiting area) —</option>
                                        @foreach($accessKeys as $key)
                                            <option value="{{ $key->id }}">{{ $key->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">Determines what the user can open after login.</p>
                                </div>
                                <div class="md:col-span-3 flex justify-between items-center pt-4 border-t border-gray-100 mt-2">
                                    <button type="button" @click="tab = 'personal'"
                                        class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                                        ← Back
                                    </button>
                                    <button type="button" @click="tab = 'finance'"
                                        class="brand-btn-teal text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95">
                                        Next: Finance Details →
                                    </button>
                                </div>
                            </div>

                            {{-- Edit: Finance --}}
                            <div x-show="tab === 'finance'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-3 mb-2">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Compensation</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                                        Active Percentage Rate (%) <span class="text-gray-400 font-normal normal-case">e.g. 47</span>
                                    </label>
                                    <input type="number" step="0.01" min="0" max="100" wire:model.live="salary_rate"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                                        placeholder="e.g. 47"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                                        Monthly Rate <span class="text-teal-500 font-normal normal-case">(% × Wage Factor)</span>
                                    </label>
                                    <input type="number" step="0.01" wire:model="monthly_rate" disabled
                                        class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                                        Daily Rate <span class="text-teal-500 font-normal normal-case">(monthly × 12 / 262)</span>
                                    </label>
                                    <input type="number" step="0.01" wire:model="daily_rate" disabled
                                        class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Probi Rate</label>
                                    <input type="number" step="0.01" wire:model="probi_rate"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Grocery Allowance</label>
                                    <input type="number" step="0.01" wire:model.live="grocery_allowance"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Night Diff Factor</label>
                                    <input type="number" step="0.01" wire:model="night_diff_factor"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">COLA</label>
                                    <input type="number" step="0.01" wire:model.live="cola"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                                        Total Compensation <span class="text-teal-500 font-normal normal-case">(auto)</span>
                                    </label>
                                    <input type="number" step="0.01" disabled
                                        :value="((parseFloat($wire.monthly_rate) || 0) + (parseFloat($wire.grocery_allowance) || 0) + (parseFloat($wire.cola) || 0)).toFixed(2)"
                                        class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                                </div>

                                <div class="md:col-span-3 mt-4 mb-2">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Percentage Rate Scale</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Min Rate (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" wire:model="min_scale"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                                        placeholder="e.g. 47"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Max Rate (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" wire:model="max_scale"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                                        placeholder="e.g. 82"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Wage Factor (₱)</label>
                                    <input type="number" step="0.01" wire:model.live="wage_factor"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"
                                        placeholder="e.g. 30000"/>
                                </div>

                                {{-- Leave Balances --}}
                                <div class="md:col-span-3 mt-4 mb-2">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Leave Balances</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Vacation Leave Total</label>
                                    <input type="number" step="0.01" min="0" wire:model="vl_total"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Vacation Leave Consumed</label>
                                    <input type="number" step="0.01" min="0" wire:model="vl_consumed"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div></div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Sick Leave Total</label>
                                    <input type="number" step="0.01" min="0" wire:model="sl_total"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Sick Leave Consumed</label>
                                    <input type="number" step="0.01" min="0" wire:model="sl_consumed"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div></div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Birthday Leave Total</label>
                                    <input type="number" step="0.01" min="0" wire:model="bl_total"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Birthday Leave Consumed</label>
                                    <input type="number" step="0.01" min="0" wire:model="bl_consumed"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div></div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Solo Parent Leave Total</label>
                                    <input type="number" step="0.01" min="0" wire:model="spl_total"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Solo Parent Leave Consumed</label>
                                    <input type="number" step="0.01" min="0" wire:model="spl_consumed"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div></div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Bereavement Leave Total</label>
                                    <input type="number" step="0.01" min="0" wire:model="syl_total"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Bereavement Leave Consumed</label>
                                    <input type="number" step="0.01" min="0" wire:model="syl_consumed"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div></div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Maternity Leave Total <span class="text-gray-400 font-normal normal-case">(Female)</span></label>
                                    <input type="number" step="0.01" min="0" wire:model="ml_total"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Maternity Leave Consumed <span class="text-gray-400 font-normal normal-case">(Female)</span></label>
                                    <input type="number" step="0.01" min="0" wire:model="ml_consumed"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div></div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Paternity Leave Total <span class="text-gray-400 font-normal normal-case">(Male)</span></label>
                                    <input type="number" step="0.01" min="0" wire:model="pl_total"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Paternity Leave Consumed <span class="text-gray-400 font-normal normal-case">(Male)</span></label>
                                    <input type="number" step="0.01" min="0" wire:model="pl_consumed"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                </div>
                                <div></div>

                                {{-- Picture & Signature --}}
                                <div class="md:col-span-3 mt-4 mb-2">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Photo & Signature</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Picture</label>
                                    <input type="file" wire:model="picture" accept="image/*"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @if($picture)
                                        <div class="mt-2 relative w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ $picture->temporaryUrl() }}" alt="Picture preview" class="w-full h-full object-cover"/>
                                        </div>
                                    @endif
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Signature</label>
                                    <input type="file" wire:model="signature" accept="image/*"
                                        class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                    @if($signature)
                                        <div class="mt-2 relative w-20 h-12 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ $signature->temporaryUrl() }}" alt="Signature preview" class="w-full h-full object-cover"/>
                                        </div>
                                    @endif
                                </div>

                                <div class="md:col-span-3 flex justify-between items-center pt-4 border-t border-gray-100 mt-2">
                                    <button type="button" @click="tab = 'employment'"
                                        class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                                        ← Back
                                    </button>
                                    <button type="button" @click="tab = 'dependents'"
                                        class="brand-btn-teal text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95">
                                        Next: Dependents →
                                    </button>
                                </div>
                            </div>

                            {{-- Edit: Dependents --}}
                            <div x-show="tab === 'dependents'">
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-3">Add New Dependent</p>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3"
                                        x-data="{
                                            calcAge(bday) {
                                                if (!bday) return null;
                                                const today = new Date();
                                                const b = new Date(bday);
                                                let age = today.getFullYear() - b.getFullYear();
                                                if (today.getMonth() < b.getMonth() || (today.getMonth() === b.getMonth() && today.getDate() < b.getDate())) age--;
                                                return age >= 0 ? age : null;
                                            }
                                        }">
                                        <input type="text" wire:model="new_dependent.lastname" placeholder="Last Name"
                                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        <input type="text" wire:model="new_dependent.firstname" placeholder="First Name"
                                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        <input type="text" wire:model="new_dependent.middlename" placeholder="Middle Name"
                                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        <input type="text" wire:model="new_dependent.extension" placeholder="Extension (Jr.)"
                                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        <input type="text" wire:model="new_dependent.relationship" placeholder="Relationship"
                                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        <select wire:model="new_dependent.gender"
                                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <input type="date" wire:model="new_dependent.birthday"
                                            x-on:change="$wire.set('new_dependent.age', calcAge($event.target.value))"
                                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                                        <input type="number" wire:model="new_dependent.age" placeholder="Age (auto)"
                                            disabled
                                            class="block w-full rounded-md border border-gray-200 bg-gray-50 shadow-sm sm:text-sm p-2 text-gray-500 cursor-not-allowed"/>
                                    </div>
                                    <button type="button" wire:click="addDependent"
                                        class="mt-3 brand-btn-primary text-sm font-bold py-1.5 px-4 rounded shadow-sm active:scale-95">
                                        Add Dependent
                                    </button>
                                </div>

                                @if(count($dependents) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Name</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Relationship</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Gender</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Birthday</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Age</th>
                                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-100">
                                                @foreach($dependents as $index => $dep)
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm">
                                                            {{ $dep['lastname'] }}, {{ $dep['firstname'] }} {{ $dep['middlename'] }} {{ $dep['extension'] }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm">{{ $dep['relationship'] }}</td>
                                                        <td class="px-4 py-2 text-sm">{{ $dep['gender'] }}</td>
                                                        <td class="px-4 py-2 text-sm">{{ $dep['birthday'] }}</td>
                                                        <td class="px-4 py-2 text-sm">{{ $dep['age'] }}</td>
                                                        <td class="px-4 py-2 text-right">
                                                            <button wire:click="removeDependent({{ $index }})"
                                                                class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                                                Remove
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 text-center py-4">No dependents added yet.</p>
                                @endif
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
