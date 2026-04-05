<?php

namespace App\Livewire\PointofSale;

use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SalesTrendChart extends Component
{
    public string $period = '30';

    // Fires whenever $period changes — sends fresh data to Alpine
    public function updatedPeriod(): void
    {
        $this->dispatch('chartDataUpdated', ...$this->chartData);
    }

    #[Computed]
    public function chartData(): array
    {
        $days = (int) $this->period;

        $sales = Sale::selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as transactions')
            ->where('created_at', '>=', now()->subDays($days)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $revenue = [];
        $txCount = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format($days <= 7 ? 'D' : 'M d');
            $revenue[] = (float) ($sales[$date]->revenue ?? 0);
            $txCount[] = (int) ($sales[$date]->transactions ?? 0);
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'transactions' => $txCount,
            'total' => array_sum($revenue),
            'peak' => max($revenue ?: [0]),
        ];
    }

    public function render()
    {
        return view('pages.POSuser.livewire.sales-trend-chart');
    }
}
