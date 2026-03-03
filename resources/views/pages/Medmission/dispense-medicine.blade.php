<div class="p-4 md:p-6 max-w-7xl mx-auto min-h-screen bg-gray-50">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6 order-1">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">DISPENSING STATION</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                <label class="block text-xs font-black text-indigo-600 uppercase mb-3">1. Select Patient</label>
                
                <input type="text" 
                    wire:model.live.debounce.300ms="searchPatient" 
                    placeholder="Type name or ID..." 
                    class="w-full bg-gray-50 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 mb-2">

                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($patients as $p)
                        <button wire:click="$set('patient_id', {{ $p->id }})" 
                            type="button"
                            class="w-full text-left p-3 rounded-xl transition {{ $patient_id == $p->id ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-indigo-50' }}">
                            <div class="font-bold text-sm">{{ $p->full_name }}</div>
                            <div class="text-[10px] {{ $patient_id == $p->id ? 'text-indigo-200' : 'text-gray-400' }}">{{ $p->patient_number }}</div>
                        </button>
                    @endforeach

                    @if($patients->isEmpty())
                        <p class="text-[10px] text-gray-400 p-2 italic">No patient found. Register them first?</p>
                    @endif
                </div>
            </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
    <label class="block text-xs font-black text-emerald-600 uppercase mb-3">2. Select Medicine</label>
    
    <input type="text" 
        wire:model.live.debounce.300ms="searchMedicine" 
        placeholder="Search generic or brand..." 
        class="w-full bg-gray-50 border-none rounded-xl p-4 text-sm focus:ring-2 focus:ring-emerald-500 mb-2">

    <div class="space-y-2 max-h-48 overflow-y-auto">
        @foreach($medicines as $m)
            <button wire:click="$set('medicine_id', {{ $m->id }})" 
                type="button"
                class="w-full text-left p-3 rounded-xl transition border {{ $medicine_id == $m->id ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-gray-50 border-transparent text-gray-700 hover:bg-emerald-50' }}">
                
                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-bold text-sm">{{ $m->generic_name }}</div>
                        <div class="text-[10px] {{ $medicine_id == $m->id ? 'text-emerald-100' : 'text-gray-400' }}">
                            {{ $m->brand_name ?? 'Generic' }}
                        </div>
                    </div>
                    <span class="text-[10px] font-black px-2 py-0.5 rounded {{ $m->quantity > 10 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $m->quantity }} left
                    </span>
                </div>
            </button>
        @endforeach

        @if($medicines->isEmpty())
            <div class="p-4 text-center">
                <p class="text-[10px] text-gray-400 italic font-bold uppercase">Medicine not found or out of stock</p>
            </div>
        @endif
    </div>
</div>
            </div>
        </div>

        <div class="lg:col-span-1 order-2 lg:order-3">
            <div class="sticky top-6">
                <form wire:submit.prevent="dispense" class="bg-white rounded-3xl shadow-2xl border-4 border-indigo-600 overflow-hidden">
                    <div class="bg-indigo-600 p-6 text-white">
                        <h3 class="text-lg font-black uppercase tracking-tighter">Confirm Dispensing</h3>
                        <p class="text-indigo-200 text-[10px] mt-1 italic uppercase font-bold">Authorized by: {{ auth()->user()->name }}</p>
                    </div>

                    <div class="p-6 space-y-5">
                        @if(session()->has('error'))
                            <div class="bg-red-50 text-red-700 p-3 rounded-lg text-xs font-bold border border-red-200 animate-pulse">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session()->has('message'))
                            <div class="bg-emerald-50 text-emerald-700 p-3 rounded-lg text-xs font-bold border border-emerald-200">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase">Mission Site</label>
                            <input type="text" wire:model="medmission_place" class="w-full mt-1 bg-gray-50 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase">Quantity (Pieces)</label>
                            <input type="number" wire:model="quantity_dispensed" class="w-full mt-1 bg-gray-50 border-none rounded-xl p-4 text-3xl font-black text-center text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white p-5 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-indigo-100 transition-transform active:scale-95">
                            Process Record →
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 order-3 lg:order-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Station Activity</h3>
                    <span class="text-[9px] font-bold px-2 py-1 bg-gray-200 text-gray-600 rounded uppercase">{{ $medmission_place }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black">
                            <tr>
                                <th class="px-6 py-3">Patient</th>
                                <th class="px-6 py-3">Medicine</th>
                                <th class="px-6 py-3">Qty</th>
                                <th class="px-6 py-3 text-right">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($recent_activity as $activity)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $activity->patient->full_name }}</div>
                                        <div class="text-[9px] text-gray-400 font-mono">{{ $activity->patient->patient_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 font-medium">{{ $activity->medicine->generic_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 font-black text-xs">
                                            {{ $activity->quantity_dispensed }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-[10px] text-gray-400 font-bold uppercase">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-xs text-gray-400 italic">No records yet for this session.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>