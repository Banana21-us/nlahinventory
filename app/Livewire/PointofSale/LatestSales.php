<?php
namespace App\Livewire\PointofSale;

use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LatestSales extends Component
{
    #[Computed]
    public function sales()
    {
        return Sale::with(['customer', 'saleItems.item'])
            ->latest()
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('pages.POSuser.livewire.latest-sales');
    }
}