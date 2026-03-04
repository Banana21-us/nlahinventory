
<div class="rounded-xl p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mission Medical Dashboard</h1>
                <p class="text-sm text-gray-500">Tracking medicine availability for effective outreach.</p>
            </div>
            <div class="text-sm font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                Live Mission Tracking
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Varieties</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $stats['total_items'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs font-bold text-green-500 uppercase tracking-wider">Active Supply</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $stats['active_stock'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
                <p class="text-xs font-bold text-red-500 uppercase tracking-wider">Critical Reorders</p>
                <p class="text-3xl font-extrabold text-red-600 mt-2">{{ $stats['low_stock'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Therapeutic Groups</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            

            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 text-center">Availability</h3>
                    <div class="h-48">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
                    <div class="bg-red-50 px-4 py-3 border-b border-red-100">
                        <h3 class="text-xs font-bold text-red-700 uppercase">⚠️ Low Stock Alerts</h3>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse($lowStockItems as $item)
                            <li class="px-4 py-3 flex justify-between items-center hover:bg-gray-50">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $item->generic_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->brand_name }}</p>
                                </div>
                                <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded">Lv: {{ $item->reorder_level }}</span>
                            </li>
                        @empty
                            <li class="p-4 text-sm text-gray-500 text-center italic">All stocks sufficient.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            // Category Bar Chart
            
            // Status Doughnut Chart
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Inactive'],
                    datasets: [{
                        data: @json($statusData),
                        backgroundColor: ['#10b981', '#f3f4f6'],
                        borderWidth: 0,
                        cutout: '70%'
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>
</div>