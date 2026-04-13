@once
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endonce
<div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">

    {{-- Header — NOT inside wire:ignore so Livewire can update it --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black tracking-widest uppercase text-amber-500 mb-0.5">Analytics</p>
            <h3 class="text-lg font-black text-stone-900 tracking-tight">Revenue Trend</h3>
        </div>

        {{-- Period tabs — must be outside wire:ignore to receive clicks --}}
        <div class="flex gap-1 p-1 bg-stone-100 rounded-xl">
            @foreach(['7' => '7D', '30' => '30D', '90' => '90D'] as $val => $lbl)
                <button
                    wire:click="$set('period', '{{ $val }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all
                           {{ $period === $val
                               ? 'bg-white text-amber-600 shadow-sm ring-1 ring-stone-200'
                               : 'text-stone-500 hover:text-stone-700' }}"
                >
                    {{ $lbl }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Headline numbers — outside wire:ignore so they update --}}
    <div class="mb-6">
        <p class="text-4xl font-black text-stone-900 tracking-tight">
            ₱{{ number_format($this->chartData['total']) }}
        </p>
        <p class="text-sm text-stone-400 font-medium mt-1">
            Total over last {{ $period }} days · Peak ₱{{ number_format($this->chartData['peak']) }}
        </p>
    </div>

    {{-- Chart canvas — wire:ignore prevents DOM diffing on this element only --}}
    {{-- x-effect watches chartJson and re-draws whenever Livewire updates it  --}}
    <div
        class="relative h-52"
        wire:ignore
        x-data="{
            chart: null,
            chartJson: '{{ addslashes(json_encode($this->chartData)) }}',
            drawChart(data) {
                if (this.chart) this.chart.destroy();
                this.chart = new Chart(this.$refs.canvas, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.revenue,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245,158,11,0.08)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#f59e0b',
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                        },{
                            data: data.transactions,
                            borderColor: '#78716c',
                            borderDash: [4,4],
                            borderWidth: 1.5,
                            pointRadius: 2,
                            tension: 0.4,
                            yAxisID: 'y2',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1c1917',
                                titleColor: '#a8a29e',
                                bodyColor: '#fafaf9',
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: c => c.datasetIndex === 0
                                        ? ' ₱' + c.parsed.y.toLocaleString()
                                        : ' ' + c.parsed.y + ' sales'
                                }
                            }
                        },
                        scales: {
                            x:  { grid: { display: false }, ticks: { color: '#a8a29e', font: { size: 11 } }, border: { display: false } },
                            y:  { position: 'left', grid: { color: '#f5f5f4' }, ticks: { color: '#a8a29e', font: { size: 11 }, callback: v => '₱' + v.toLocaleString() }, border: { display: false } },
                            y2: { position: 'right', grid: { display: false }, ticks: { color: '#d6d3d1', font: { size: 10 } }, border: { display: false } }
                        }
                    }
                });
            },
            init() {
                this.drawChart(JSON.parse(this.chartJson));

                {{-- Listen for Livewire re-renders and redraw with fresh data --}}
                Livewire.on('chartDataUpdated', (data) => {
                    this.drawChart(data);
                });
            }
        }"
    >
        <canvas x-ref="canvas"></canvas>
    </div>
</div>