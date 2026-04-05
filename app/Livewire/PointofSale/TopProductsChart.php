<?php

// ── TopProductsChart ──────────────────────────────────────────────────────────

namespace App\Livewire\PointofSale;

use App\Models\SaleItem;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TopProductsChart extends Component
{
    #[Computed]
    public function products(): array
    {
        return SaleItem::with('item')
            ->selectRaw('item_id, SUM(quantity) as total_qty, SUM(price * quantity) as total_revenue')
            ->groupBy('item_id')
            ->orderByDesc('total_revenue')
            ->limit(6)
            ->get()
            ->map(fn ($si) => [
                'name' => $si->item?->name ?? 'Unknown',
                'qty' => (int) $si->total_qty,
                'revenue' => (float) $si->total_revenue,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('pages.POSuser.livewire.top-products-chart');
    }
}
