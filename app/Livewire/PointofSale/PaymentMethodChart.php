<?php
// ── PaymentMethodChart ────────────────────────────────────────────────────────
namespace App\Livewire\PointofSale;

use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PaymentMethodChart extends Component
{
    #[Computed]
    public function methods(): array
    {
        return Sale::selectRaw('payment_method, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get()
            ->map(fn($s) => [
                'method'  => $s->payment_method ?? 'Unknown',
                'count'   => (int) $s->count,
                'revenue' => (float) $s->revenue,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('pages.POSuser.livewire.payment-method-chart');
    }
}