<?php
namespace App\Livewire\PointofSale;

use App\Models\Customer;
use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

class YearlyCustomerChart extends Component
{
    #[Computed]
    public function chartData(): array
    {
        $months = [];
        $newCustomers = [];
        $salesPerMonth = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');

            $newCustomers[] = Customer::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $salesPerMonth[] = Sale::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'months'        => $months,
            'newCustomers'  => $newCustomers,
            'salesPerMonth' => $salesPerMonth,
            'totalNew'      => array_sum($newCustomers),
        ];
    }

    public function render()
    {
        return view('pages.POSuser.livewire.yearly-customer-chart');
    }
}