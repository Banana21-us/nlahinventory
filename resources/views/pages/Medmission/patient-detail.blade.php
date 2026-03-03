<div class="p-6 max-w-6xl mx-auto space-y-6">
    <nav class="flex items-center text-xs font-bold uppercase tracking-widest text-gray-400">
        <a href="{{ route('patients') }}" wire:navigate class="hover:text-indigo-600">Registry</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Patient Profile</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-indigo-600 h-24 relative">
                    <div class="absolute -bottom-10 left-6 w-20 h-20 bg-white rounded-2xl shadow-md flex items-center justify-center border-4 border-white">
                        <span class="text-2xl font-black text-indigo-600">{{ substr($patient->full_name, 0, 1) }}</span>
                    </div>
                </div>
                <div class="pt-14 p-6">
                    <h2 class="text-xl font-black text-gray-900">{{ $patient->full_name }}</h2>
                    <p class="text-xs font-mono text-gray-400">{{ $patient->patient_number }}</p>
                    
                    <div class="mt-6 grid grid-cols-2 gap-4 border-t border-gray-100 pt-6">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Age / Gender</p>
                            <p class="text-sm font-bold text-gray-700">{{ $patient->age }}y / {{ $patient->gender }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Contact</p>
                            <p class="text-sm font-bold text-gray-700">{{ $patient->contact_number ?? 'None' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-xs font-black text-gray-400 uppercase mb-4 tracking-widest">Medical History</h3>
                <p class="text-sm text-gray-600 leading-relaxed italic">
                    {{ $patient->medical_history ?? 'No previous history recorded.' }}
                </p>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Medication Log</h3>
                    <span class="text-[10px] font-bold bg-indigo-100 text-indigo-700 px-2 py-1 rounded">Total: {{ $history->count() }}</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase">
                            <tr>
                                <th class="px-6 py-4">Date & Time</th>
                                <th class="px-6 py-4">Medicine Generic</th>
                                <th class="px-6 py-4">Qty</th>
                                <th class="px-6 py-4">Mission Site</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($history as $record)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900 font-bold">{{ $record->created_at->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $record->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-800 font-medium">{{ $record->medicine->generic_name }}</div>
                                        <div class="text-[10px] text-gray-400 italic">Dispensed by: {{ $record->dispensed_by }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-black text-indigo-600">{{ $record->quantity_dispensed }} pcs</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] font-black uppercase bg-gray-100 text-gray-500 px-2 py-1 rounded">
                                            {{ $record->medmission_place }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                        No medication has been dispensed to this patient yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>