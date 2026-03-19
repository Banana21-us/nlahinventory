<div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-black tracking-widest uppercase text-amber-500 mb-0.5">Live Feed</p>
            <h3 class="text-base font-black text-stone-900 tracking-tight">Latest Transactions</h3>
        </div>
        <span class="px-3 py-1 bg-stone-100 text-stone-500 rounded-full text-[10px] font-bold uppercase tracking-wider">
            Last 8
        </span>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-stone-100">
                    <th class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-stone-400">Customer</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-stone-400">Items</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-stone-400">Total</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-stone-400">Method</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-stone-400">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-50">
                @forelse($this->sales as $sale)
                    @php
                        $methodColors = [
                            'Cash'   => 'bg-emerald-100 text-emerald-700',
                            'Gcash'  => 'bg-blue-100 text-blue-700',
                            'Credit' => 'bg-amber-100 text-amber-700',
                        ];
                        $mc = $methodColors[$sale->payment_method] ?? 'bg-stone-100 text-stone-600';
                    @endphp
                    <tr class="hover:bg-stone-50/60 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center shrink-0 text-xs font-black text-amber-600">
                                    {{ strtoupper(substr($sale->customer?->name ?? 'W', 0, 1)) }}
                                </div>
                                <span class="font-semibold text-stone-800 text-xs">{{ $sale->customer?->name ?? 'Walk-in' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="text-xs text-stone-600">
                                @foreach($sale->saleItems->take(2) as $si)
                                    <span class="inline-block">{{ $si->item?->name ?? '?' }}{{ !$loop->last ? ',' : '' }}</span>
                                @endforeach
                                @if($sale->saleItems->count() > 2)
                                    <span class="text-stone-400">+{{ $sale->saleItems->count() - 2 }} more</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="font-black text-stone-900 text-sm">₱{{ number_format($sale->total) }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $mc }}">
                                {{ $sale->payment_method ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <p class="text-xs text-stone-600 font-medium">{{ $sale->created_at->format('M d, h:i A') }}</p>
                            <p class="text-[10px] text-stone-400">{{ $sale->created_at->diffForHumans() }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center text-stone-400">
                                <svg class="w-8 h-8 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                                <p class="text-sm font-medium">No transactions yet</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>