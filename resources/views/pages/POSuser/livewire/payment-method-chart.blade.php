@once
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endonce
<div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5 ms-1 me-1">
    <div class="mb-5">
        <p class="text-[10px] font-black tracking-widest uppercase text-amber-500 mb-0.5">Breakdown</p>
        <h3 class="text-base font-black text-stone-900 tracking-tight">Payment Methods</h3>
    </div>

    @if(count($this->methods) === 0)
        <p class="text-sm text-stone-400 text-center py-8">No payment data yet.</p>
    @else
        <div class="relative h-40 mb-4">
            <canvas id="paymentChart" wire:ignore></canvas>
        </div>

        <div class="space-y-2">
            @php
                $colors = ['#f59e0b', '#292524', '#d6d3d1', '#a8a29e'];
                $total  = array_sum(array_column($this->methods, 'count'));
            @endphp
            @foreach($this->methods as $i => $m)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full" style="background:{{ $colors[$i] ?? '#d6d3d1' }}"></div>
                        <span class="text-xs font-semibold text-stone-600">{{ $m['method'] }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[11px] text-stone-400">{{ $total > 0 ? round(($m['count'] / $total) * 100) : 0 }}%</span>
                        <span class="text-xs font-bold text-stone-800">{{ $m['count'] }} txn</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="paymentData" data-chart='@json($this->methods)' style="display:none"></div>
    @endif
</div>

<script>
    (function () {
        const el = document.getElementById('paymentData');
        if (!el) return;

        const methods = JSON.parse(el.dataset.chart);
        const ctx     = document.getElementById('paymentChart');
        const colors  = ['#f59e0b', '#292524', '#d6d3d1', '#a8a29e'];

        if (window._payChart instanceof Chart) window._payChart.destroy();

        window._payChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: methods.map(m => m.method),
                datasets: [{
                    data: methods.map(m => m.count),
                    backgroundColor: colors.slice(0, methods.length),
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1c1917',
                        titleColor: '#a8a29e',
                        bodyColor: '#fafaf9',
                        padding: 10,
                        cornerRadius: 8,
                    }
                }
            }
        });
    })();
</script>