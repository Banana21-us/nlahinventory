<div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-5 ms-1 me-1">
    <div class="flex items-start justify-between mb-5">
        <div>
            <p class="text-[10px] font-black tracking-widest uppercase text-amber-500 mb-0.5">Growth</p>
            <h3 class="text-base font-black text-stone-900 tracking-tight">New Customers</h3>
        </div>
        <div class="text-right">
            <p class="text-2xl font-black text-stone-900">{{ $this->chartData['totalNew'] }}</p>
            <p class="text-[10px] text-stone-400 font-medium">past 12 months</p>
        </div>
    </div>

    <div class="relative h-36">
        <canvas id="customerChart" wire:ignore></canvas>
    </div>

    <div id="customerData" data-chart='@json($this->chartData)' style="display:none"></div>
</div>

<script>
    (function () {
        const el = document.getElementById('customerData');
        if (!el) return;

        const data = JSON.parse(el.dataset.chart);
        const ctx  = document.getElementById('customerChart');
        const max  = Math.max(...data.newCustomers, 1);

        if (window._custChart instanceof Chart) window._custChart.destroy();

        window._custChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.months,
                datasets: [{
                    label: 'New Customers',
                    data: data.newCustomers,
                    backgroundColor: data.newCustomers.map(v => v === max && max > 0 ? '#f59e0b' : '#e7e5e4'),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1c1917',
                        titleColor: '#a8a29e',
                        bodyColor: '#fafaf9',
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#a8a29e', font: { size: 10, weight: '600' } },
                        border: { display: false },
                    },
                    y: {
                        grid: { color: '#f5f5f4' },
                        ticks: { color: '#d6d3d1', font: { size: 10 }, stepSize: 1 },
                        border: { display: false },
                        beginAtZero: true,
                    }
                }
            }
        });
    })();
</script>
