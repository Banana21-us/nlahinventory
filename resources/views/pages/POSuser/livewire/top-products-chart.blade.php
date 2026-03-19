<div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5 ms-1 me-1">
    <div class="mb-5">
        <p class="text-[10px] font-black tracking-widest uppercase text-amber-500 mb-0.5">Sales</p>
        <h3 class="text-base font-black text-stone-900 tracking-tight">Top Products</h3>
    </div>

    @if(count($this->products) === 0)
        <p class="text-sm text-stone-400 text-center py-8">No sales data yet.</p>
    @else
        <div class="space-y-3">
            @php $max = max(array_column($this->products, 'revenue') ?: [1]); @endphp
            @foreach($this->products as $i => $product)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-[10px] font-black text-stone-300 w-4">{{ $i + 1 }}</span>
                            <span class="text-xs font-bold text-stone-700 truncate">{{ $product['name'] }}</span>
                        </div>
                        <div class="text-right shrink-0 ml-2">
                            <span class="text-xs font-black text-stone-900">₱{{ number_format($product['revenue']) }}</span>
                            <span class="text-[10px] text-stone-400 ml-1">{{ $product['qty'] }}×</span>
                        </div>
                    </div>
                    <div class="h-1.5 bg-stone-100 rounded-full overflow-hidden">
                        <div
                            class="h-full rounded-full transition-all duration-700"
                            style="width: {{ ($product['revenue'] / $max) * 100 }}%;
                                   background: {{ $i === 0 ? '#f59e0b' : ($i === 1 ? '#78716c' : '#d6d3d1') }}"
                        ></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>