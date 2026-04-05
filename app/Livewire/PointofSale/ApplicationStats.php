<?php

namespace App\Livewire\PointofSale;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ApplicationStats extends Component
{
    #[Computed]
    public function stats(): array
    {
        $today = now()->toDateString();
        $thisMonth = now()->startOfMonth();

        $salesToday = Sale::whereDate('created_at', $today);
        $salesMonth = Sale::where('created_at', '>=', $thisMonth);
        $salesYesterday = Sale::whereDate('created_at', now()->subDay()->toDateString());
        $salesLastMonth = Sale::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ]);

        $revenueToday = (float) $salesToday->sum('total');
        $revenueYesterday = (float) $salesYesterday->sum('total');
        $revenueMonth = (float) $salesMonth->sum('total');
        $revenueLastMonth = (float) $salesLastMonth->sum('total');

        $txToday = $salesToday->count();
        $txYest = $salesYesterday->count();

        $lowStock = Inventory::where('quantity', '<=', 5)->where('quantity', '>', 0)->count();
        $outOfStock = Inventory::where('quantity', 0)->count();
        $customers = Customer::where('status', 'active')->count();
        $credited = Customer::where('charges', '>', 0)->count();

        return [
            [
                'label' => 'Revenue Today',
                'value' => '₱'.number_format($revenueToday),
                'sub' => $revenueYesterday > 0
                    ? ($revenueToday >= $revenueYesterday ? '+' : '').
                      number_format((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100, 1).'% vs yesterday'
                    : 'No sales yesterday',
                'up' => $revenueToday >= $revenueYesterday,
                'icon' => 'currency',
                'accent' => 'amber',
            ],
            [
                'label' => 'Monthly Revenue',
                'value' => '₱'.number_format($revenueMonth),
                'sub' => $revenueLastMonth > 0
                    ? ($revenueMonth >= $revenueLastMonth ? '+' : '').
                      number_format((($revenueMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1).'% vs last month'
                    : 'First month of data',
                'up' => $revenueMonth >= $revenueLastMonth,
                'icon' => 'chart',
                'accent' => 'stone',
            ],
            [
                'label' => 'Transactions Today',
                'value' => number_format($txToday),
                'sub' => $txYest > 0
                    ? ($txToday >= $txYest ? '+' : '').
                      number_format((($txToday - $txYest) / $txYest) * 100, 1).'% vs yesterday'
                    : ($txToday.' sale'.($txToday !== 1 ? 's' : '').' so far'),
                'up' => $txToday >= $txYest,
                'icon' => 'receipt',
                'accent' => 'emerald',
            ],
            [
                'label' => 'Active Customers',
                'value' => number_format($customers),
                'sub' => $credited > 0
                    ? "{$credited} with outstanding credit"
                    : 'No outstanding credit',
                'up' => $credited === 0,
                'icon' => 'users',
                'accent' => $credited > 0 ? 'red' : 'blue',
            ],
            [
                'label' => 'Stock Alerts',
                'value' => number_format($lowStock + $outOfStock),
                'sub' => "{$outOfStock} out · {$lowStock} low",
                'up' => ($lowStock + $outOfStock) === 0,
                'icon' => 'warning',
                'accent' => ($lowStock + $outOfStock) > 0 ? 'red' : 'emerald',
            ],
        ];
    }

    public function render()
    {
        return view('pages.POSuser.application-stats');
    }
}
