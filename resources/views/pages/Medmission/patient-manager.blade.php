<div class="p-6 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Patient Registry</h2>
        <input type="text" wire:model.live="search" placeholder="Search by name or ID..." 
               class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 w-full md:w-72">
    </div>

    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-8" x-data="{ open: @entangle('showForm') }">
        <button @click="open = !open" class="w-full flex items-center justify-between p-4 hover:bg-gray-50">
            <span class="font-bold text-gray-700">+ Register New Patient</span>
            <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-show="open" x-collapse class="p-6 border-t border-gray-100 bg-gray-50/50">
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="text-xs font-bold uppercase text-gray-500">Full Name *</label>
                    <input type="text" wire:model="full_name" class="w-full mt-1 rounded-md border-gray-300 p-2 border">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-gray-500">Age</label>
                    <input type="number" wire:model="age" class="w-full mt-1 rounded-md border-gray-300 p-2 border">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-gray-500">Gender</label>
                    <select wire:model="gender" class="w-full mt-1 rounded-md border-gray-300 p-2 border bg-white">
                        <option value="">Select...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-gray-500">Contact Number</label>
                    <input type="text" wire:model="contact_number" class="w-full mt-1 rounded-md border-gray-300 p-2 border">
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs font-bold uppercase text-gray-500">Medical History / Allergies</label>
                    <textarea wire:model="medical_history" rows="2" class="w-full mt-1 rounded-md border-gray-300 p-2 border"></textarea>
                </div>
                <div class="md:col-span-3 flex justify-end gap-2 mt-4">
                    <button type="button" @click="open = false" class="px-4 py-2 text-gray-500">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition">Register Patient</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Age/Gender</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($patients as $patient)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono text-indigo-600">{{ $patient->patient_number }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $patient->full_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $patient->age }}yrs / {{ $patient->gender }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $patient->contact_number }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('patient.details', $patient->id) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $patients->links() }}
        </div>
    </div>
</div>