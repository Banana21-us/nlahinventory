<?php

namespace App\Livewire\PointofSale;

use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PosSales extends Component
{
    use WithPagination;

    public string $search = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $payMethod = '';

    // View detail modal
    public bool $showDetail = false;

    public ?int $detailId = null;

    // Delete
    public bool $confirmingDeletion = false;

    public ?int $deletingId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'payMethod' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingPayMethod(): void
    {
        $this->resetPage();
    }

    // ─── Computed ────────────────────────────────────────────────────────────

    #[Computed]
    public function sales()
    {
        return Sale::with(['customer', 'saleItems.item'])
            ->when($this->search, function ($q) {
                $q->whereHas('customer', fn ($c) => $c->where('name', 'like', '%'.$this->search.'%')
                );
            })
            ->when($this->payMethod, fn ($q) => $q->where('payment_method', $this->payMethod)
            )
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom)
            )
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo)
            )
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function summary()
    {
        $base = Sale::query()
            ->when($this->search, fn ($q) => $q->whereHas('customer', fn ($c) => $c->where('name', 'like', '%'.$this->search.'%')
            )
            )
            ->when($this->payMethod, fn ($q) => $q->where('payment_method', $this->payMethod)
            )
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom)
            )
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo)
            );

        return [
            'count' => $base->count(),
            'total' => $base->sum('total'),
            'paid' => $base->sum('paid_amount'),
        ];
    }

    #[Computed]
    public function detail()
    {
        if (! $this->detailId) {
            return null;
        }

        return Sale::with(['customer', 'saleItems.item'])->find($this->detailId);
    }

    // ─── Actions ─────────────────────────────────────────────────────────────

    public function viewDetail(int $id): void
    {
        $this->detailId = $id;
        $this->showDetail = true;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $sale = Sale::findOrFail($this->deletingId);
        $sale->saleItems()->delete();
        $sale->delete();

        session()->flash('message', 'Sale #'.$this->deletingId.' has been deleted.');
        $this->confirmingDeletion = false;
        $this->deletingId = null;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'payMethod']);
        $this->resetPage();
    }

    public function render()
    {
        return view('pages.POSuser.Sales');
    }
}
