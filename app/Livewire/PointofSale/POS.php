<?php

namespace App\Livewire\PointofSale;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class POS extends Component
{
    // Properties
    public $items    = [];
    public $customers = [];
    public string $search   = '';
    public array  $cart     = [];
    public string $category = 'all';

    // Checkout properties
    public $customer_id           = null;
    public string $payment_method = 'Cash';
    public $paid_amount           = 0;

    // Credit confirmation state
    public bool $showCreditConfirm = false;
    public float $creditShortfall  = 0;

    public function mount(): void
    {
        $this->items = Item::whereHas('inventory', fn($q) => $q->where('quantity', '>', 0))
            ->with('inventory')
            ->where('status', 'active')
            ->get();

        $this->customers = Customer::where('status', 'active')->orderBy('name')->get();
    }

    // ─── Computed ────────────────────────────────────────────────────────────

    #[Computed]
    public function filteredItems()
    {
        return $this->items->filter(function ($item) {
            $matchesSearch = empty($this->search)
                || str_contains(strtolower($item->name), strtolower($this->search));
            $matchesCategory = $this->category === 'all'
                || strtolower((string) $item->type) === $this->category;
            return $matchesSearch && $matchesCategory;
        });
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function change(): float
    {
        if ($this->payment_method === 'Credit') return 0;
        return max(0, (float) $this->paid_amount - $this->subtotal);
    }

    #[Computed]
    public function selectedCustomer()
    {
        if (! $this->customer_id) return null;
        return $this->customers->firstWhere('id', $this->customer_id);
    }

    // ─── Cart Actions ────────────────────────────────────────────────────────

    public function addToCart(int $itemId): void
    {
        $item      = Item::find($itemId);
        $inventory = Inventory::where('item_id', $itemId)->first();

        if (! $inventory || $inventory->quantity <= 0) {
            $this->notify('danger', 'Out of Stock', 'This item is out of stock!');
            return;
        }

        if (isset($this->cart[$itemId])) {
            if ($this->cart[$itemId]['quantity'] >= $inventory->quantity) {
                $this->notify('danger', 'Stock Limit', "Only {$inventory->quantity} in stock.");
                return;
            }
            $this->cart[$itemId]['quantity']++;
        } else {
            $this->cart[$itemId] = [
                'id'       => $item->id,
                'name'     => $item->name,
                'price'    => $item->price,
                'quantity' => 1,
            ];
        }
    }

    public function removeFromCart(int $itemId): void
    {
        unset($this->cart[$itemId]);
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        $quantity  = max(1, $quantity);
        $inventory = Inventory::where('item_id', $itemId)->first();

        if ($quantity > $inventory->quantity) {
            $this->notify('danger', 'Stock Limit', "Only {$inventory->quantity} in stock.");
            $this->cart[$itemId]['quantity'] = $inventory->quantity;
        } else {
            $this->cart[$itemId]['quantity'] = $quantity;
        }
    }

    // ─── Checkout ────────────────────────────────────────────────────────────

    public function checkout(): void
    {
        if (empty($this->cart)) {
            $this->notify('danger', 'Empty Cart', 'Add items to the cart before checking out.');
            return;
        }

        $isCredit = $this->payment_method === 'Credit';

        if ($isCredit && ! $this->customer_id) {
            $this->notify('danger', 'No Customer', 'Please select a customer for credit payments.');
            return;
        }

        if ($isCredit) {
            $customer = Customer::find($this->customer_id);
            if (! $customer) {
                $this->notify('danger', 'Customer Not Found', 'Selected customer does not exist.');
                return;
            }

            // If balance is insufficient → show confirm modal instead of blocking
            if ((float) $customer->balance < $this->subtotal) {
                $this->creditShortfall  = $this->subtotal - (float) $customer->balance;
                $this->showCreditConfirm = true;
                return; // stop here — wait for user confirmation
            }
        }

        // Cash/GCash: check paid amount
        if (! $isCredit && (float) $this->paid_amount < $this->subtotal) {
            $this->notify('danger', 'Insufficient Payment', 'Paid amount is less than the total.');
            return;
        }

        $this->processCheckout();
    }

    /**
     * Called when user confirms proceeding with insufficient credit.
     * The shortfall becomes charges on the customer account.
     */
    public function confirmCreditCheckout(): void
    {
        $this->showCreditConfirm = false;
        $this->processCheckout();
    }

    public function cancelCreditConfirm(): void
    {
        $this->showCreditConfirm = false;
        $this->creditShortfall   = 0;
    }

    private function processCheckout(): void
    {
        $isCredit = $this->payment_method === 'Credit';

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'customer_id'    => $this->customer_id ?: null,
                'payment_method' => $this->payment_method,
                'total'          => (int) $this->subtotal,
                'paid_amount'    => $isCredit ? (int) $this->subtotal : (int) $this->paid_amount,
            ]);

            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id'  => $sale->id,
                    'item_id'  => $item['id'],
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                ]);

                $inventory = Inventory::where('item_id', $item['id'])->first();
                if ($inventory) {
                    $inventory->decrement('quantity', $item['quantity']);
                }
            }

            // Deduct from customer balance if credit
            if ($isCredit && $this->customer_id) {
                $customer   = Customer::find($this->customer_id);
                $newBalance = (float) $customer->balance - $this->subtotal;

                if ($newBalance < 0) {
                    // Remaining amount becomes charges (credit owed)
                    $customer->charges = (float) $customer->charges + abs($newBalance);
                    $customer->balance = 0;
                } else {
                    $customer->balance = $newBalance;
                }
                $customer->save();
            }

            DB::commit();

            // Reset state
            $this->cart              = [];
            $this->search            = '';
            $this->customer_id       = null;
            $this->payment_method    = 'Cash';
            $this->paid_amount       = 0;
            $this->creditShortfall   = 0;
            $this->showCreditConfirm = false;
            $this->mount();

            $this->dispatch('sale-completed', saleId: $sale->id);
            $this->notify('success', 'Sale Completed!', 'The transaction has been recorded.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify('danger', 'Sale Failed', 'Something went wrong. Please try again.');
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function notify(string $type, string $title, string $body = ''): void
    {
        $this->dispatch('notify', type: $type, title: $title, body: $body);
    }

    public function render()
    {
        return view('pages.POSuser.livewire.p-o-s');
    }
}