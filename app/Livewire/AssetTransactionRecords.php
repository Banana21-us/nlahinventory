<?php

namespace App\Livewire;

use App\Models\AssetTransaction;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class AssetTransactionRecords extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public bool $showDetail = false;

    public ?int $detailId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
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

    #[Computed]
    public function transactions()
    {
        return AssetTransaction::with(['asset.itemType', 'asset.location', 'fromLocation', 'toLocation'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('notes', 'like', '%'.$this->search.'%')
                        ->orWhere('type', 'like', '%'.$this->search.'%')
                        ->orWhereHas('asset', function ($asset) {
                            $asset->where('sku', 'like', '%'.$this->search.'%')
                                ->orWhere('brand', 'like', '%'.$this->search.'%')
                                ->orWhereHas('itemType', fn ($itemType) => $itemType->where('name', 'like', '%'.$this->search.'%'));
                        })
                        ->orWhereHas('fromLocation', fn ($location) => $location->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('toLocation', fn ($location) => $location->where('name', 'like', '%'.$this->search.'%'));
                });
            })
            ->when($this->type, fn ($query) => $query->where('type', $this->type))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('datetime', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('datetime', '<=', $this->dateTo))
            ->orderByDesc('datetime')
            ->paginate(10);
    }

    #[Computed]
    public function summary()
    {
        $base = AssetTransaction::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('notes', 'like', '%'.$this->search.'%')
                        ->orWhere('type', 'like', '%'.$this->search.'%')
                        ->orWhereHas('asset', function ($asset) {
                            $asset->where('sku', 'like', '%'.$this->search.'%')
                                ->orWhere('brand', 'like', '%'.$this->search.'%')
                                ->orWhereHas('itemType', fn ($itemType) => $itemType->where('name', 'like', '%'.$this->search.'%'));
                        })
                        ->orWhereHas('fromLocation', fn ($location) => $location->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('toLocation', fn ($location) => $location->where('name', 'like', '%'.$this->search.'%'));
                });
            })
            ->when($this->type, fn ($query) => $query->where('type', $this->type))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('datetime', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('datetime', '<=', $this->dateTo));

        return [
            'count' => (clone $base)->count(),
            'transfers' => (clone $base)->where('type', 'transfer')->count(),
            'repairs' => (clone $base)->where('type', 'repair')->count(),
        ];
    }

    #[Computed]
    public function detail()
    {
        if (! $this->detailId) {
            return null;
        }

        return AssetTransaction::with(['asset.itemType', 'asset.location', 'fromLocation', 'toLocation'])->find($this->detailId);
    }

    public function viewDetail(int $id): void
    {
        $this->detailId = $id;
        $this->showDetail = true;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'type', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.transaction-records')->layout('layouts.app');
    }
}
