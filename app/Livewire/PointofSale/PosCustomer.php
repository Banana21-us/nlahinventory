<?php

namespace App\Livewire\PointofSale;

use App\Models\Customer;
use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PosCustomer extends Component
{
    use WithPagination;

    // Form visibility
    public bool $showForm           = false;
    public bool $isEditing          = false;
    public bool $confirmingDeletion = false;
    public bool $showHistory        = false;

    public ?int $editingId   = null;
    public ?int $deletingId  = null;
    public ?int $historyId   = null;

    // Form fields
    public string $name    = '';
    public string $balance = '500';
    public string $charges = '0';
    public string $phone   = '';
    public string $status  = 'active';

    // Search / filter
    public string $search       = '';
    public string $filterStatus = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'balance' => 'required|numeric',
            'charges' => 'required|numeric|min:0',
            'phone'   => 'nullable|string|max:20',
            'status'  => 'required|in:active,inactive',
        ];
    }

    // ─── Computed ────────────────────────────────────────────────────────────

    #[Computed]
    public function customers()
    {
        return Customer::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterStatus, fn($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function summary()
    {
        return [
            'total'    => Customer::count(),
            'active'   => Customer::where('status', 'active')->count(),
            'inactive' => Customer::where('status', 'inactive')->count(),
            'credited' => Customer::where('charges', '>', 0)->count(),
        ];
    }

    #[Computed]
    public function historyCustomer()
    {
        if (! $this->historyId) return null;
        return Customer::find($this->historyId);
    }

    #[Computed]
    public function transactionHistory()
    {
        if (! $this->historyId) return collect();

        return Sale::with('saleItems.item')
            ->where('customer_id', $this->historyId)
            ->latest()
            ->get();
    }

    // ─── History ─────────────────────────────────────────────────────────────

    public function viewHistory(int $customerId): void
    {
        $this->historyId    = $customerId;
        $this->showHistory  = true;
    }

    public function closeHistory(): void
    {
        $this->showHistory = false;
        $this->historyId   = null;
    }

    // ─── Save ────────────────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate();

        $balance = (float) $this->balance;
        $charges = (float) $this->charges;

        if ($balance < 0) {
            $charges += abs($balance);
            $balance  = 0;
        }

        Customer::create([
            'name'    => trim($this->name),
            'balance' => $balance,
            'charges' => $charges,
            'phone'   => trim($this->phone) ?: null,
            'status'  => $this->status,
        ]);

        session()->flash('message', "Customer '{$this->name}' has been added.");
        $this->resetForm();
        $this->showForm = false;
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(int $id): void
    {
        $customer = Customer::findOrFail($id);

        $this->editingId = $customer->id;
        $this->name      = $customer->name;
        $this->balance   = (string) $customer->balance;
        $this->charges   = (string) $customer->charges;
        $this->phone     = $customer->phone ?? '';
        $this->status    = $customer->status;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        $customer = Customer::findOrFail($this->editingId);

        $balance = (float) $this->balance;
        $charges = (float) $this->charges;

        if ($balance < 0) {
            $charges += abs($balance);
            $balance  = 0;
        }

        $customer->update([
            'name'    => trim($this->name),
            'balance' => $balance,
            'charges' => $charges,
            'phone'   => trim($this->phone) ?: null,
            'status'  => $this->status,
        ]);

        session()->flash('message', "Customer '{$customer->name}' has been updated.");
        $this->resetForm();
        $this->isEditing = false;
    }

    // ─── Delete ──────────────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->deletingId        = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $customer = Customer::findOrFail($this->deletingId);
        $name     = $customer->name;
        $customer->delete();

        session()->flash('message', "Customer '{$name}' has been removed.");
        $this->confirmingDeletion = false;
        $this->deletingId         = null;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'filterStatus']);
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'phone', 'editingId', 'deletingId']);
        $this->balance = '500';
        $this->charges = '0';
        $this->status  = 'active';
    }

    public function render()
    {
        return view('pages.POSuser.customers');
    }
}