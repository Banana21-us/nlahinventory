<?php

namespace App\Livewire;

use App\Models\AssetMovement;
use Livewire\Component;
use Livewire\WithPagination;

class AssetTransactionRecords extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $queryString = ['search', 'type', 'dateFrom', 'dateTo'];

    public function getTransactionsProperty()
    {
        return AssetMovement::query()
            ->with('asset')
            ->when($this->search, function ($query) {
                $query->whereHas('asset', function ($q) {
                    $q->where('asset_code', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateFrom, fn($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function clearFilters()
    {
        $this->reset(['search', 'type', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.asset-transaction-records', [
            'transactions' => $this->transactions,
        ]);
    }
}