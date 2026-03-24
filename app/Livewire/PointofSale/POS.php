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
    public bool $showExtraUtensilsModal = false;
    public bool $showBudgetMealModal = false;
    public string $selectedBudgetRice = '';
    public string $selectedBudgetMeal = '';
    public string $selectedBudgetUtensil = '';
    public array $selectedBudgetUtensils = [];
    public array $budgetMealUtensilQuantities = [];
    public array $extraUtensilIds = [];
    public array $extraUtensilQuantities = [];

    public function mount(): void
    {
        $this->items = Item::whereHas('inventory', fn($q) => $q->where('quantity', '>', 0))
            ->with('inventory')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('type')
                    ->orWhere('type', '!=', 'Utensils');
            })
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
    public function budgetMealRiceOptions()
    {
        return Item::query()
            ->whereHas('inventory', fn ($q) => $q->where('quantity', '>', 0))
            ->with('inventory')
            ->where('status', 'active')
            ->where('type', 'Meals')
            ->where('name', 'like', '%rice%')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function budgetMealMainOptions()
    {
        return Item::query()
            ->whereHas('inventory', fn ($q) => $q->where('quantity', '>', 0))
            ->with('inventory')
            ->where('status', 'active')
            ->where('type', 'Meals')
            ->where('name', 'not like', '%rice%')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function budgetMealUtensilOptions()
    {
        return Item::query()
            ->whereHas('inventory', fn ($q) => $q->where('quantity', '>', 0))
            ->with('inventory')
            ->where('status', 'active')
            ->where('type', 'Utensils')
            ->whereRaw("LOWER(name) NOT LIKE 'extra %'")
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function extraUtensilOptions()
    {
        return Item::query()
            ->whereHas('inventory', fn ($q) => $q->where('quantity', '>', 0))
            ->with('inventory')
            ->where('status', 'active')
            ->where('type', 'Utensils')
            ->whereRaw("LOWER(name) LIKE 'extra %'")
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function budgetMealDisabled(): bool
    {
        return $this->budgetMealRiceOptions->isEmpty()
            || $this->budgetMealMainOptions->isEmpty()
            || $this->budgetMealUtensilOptions->isEmpty();
    }

    #[Computed]
    public function extraUtensilsDisabled(): bool
    {
        return $this->extraUtensilOptions->isEmpty();
    }

    #[Computed]
    public function budgetMealTotal(): int
    {
        $rice = $this->budgetMealRiceOptions->firstWhere('id', (int) $this->selectedBudgetRice);
        $meal = $this->budgetMealMainOptions->firstWhere('id', (int) $this->selectedBudgetMeal);
        $utensilsTotal = $this->budgetMealUtensilOptions->sum(function ($utensil) {
            $quantity = max(0, (int) ($this->budgetMealUtensilQuantities[$utensil->id] ?? 0));

            return $quantity > 0 ? (int) $utensil->price * $quantity : 0;
        });

        return (int) (($rice?->price ?? 0) + ($meal?->price ?? 0) + $utensilsTotal);
    }

    #[Computed]
    public function extraUtensilsTotal(): int
    {
        return $this->extraUtensilOptions->sum(function ($utensil) {
            $quantity = max(0, (int) ($this->extraUtensilQuantities[$utensil->id] ?? 0));

            return $quantity > 0 ? (int) $utensil->price * $quantity : 0;
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

    public function openBudgetMealModal(): void
    {
        if ($this->budgetMealDisabled) {
            $this->notify('danger', 'Budget Meal Unavailable', 'Rice, a main meal, and utensils must all be in stock.');
            return;
        }

        $this->selectedBudgetRice = (string) optional($this->budgetMealRiceOptions->first())->id;
        $this->selectedBudgetMeal = (string) optional($this->budgetMealMainOptions->first())->id;
        $this->selectedBudgetUtensils = [];
        $this->budgetMealUtensilQuantities = [];

        $this->showBudgetMealModal = true;
    }

    public function openExtraUtensilsModal(): void
    {
        if ($this->extraUtensilsDisabled) {
            $this->notify('danger', 'Extra Utensils Unavailable', 'No extra utensils are currently in stock.');
            return;
        }

        $this->extraUtensilIds = [];
        $this->extraUtensilQuantities = [];

        $this->showExtraUtensilsModal = true;
    }

    public function closeExtraUtensilsModal(): void
    {
        $this->showExtraUtensilsModal = false;
        $this->extraUtensilIds = [];
        $this->extraUtensilQuantities = [];
    }

    public function closeBudgetMealModal(): void
    {
        $this->showBudgetMealModal = false;
        $this->selectedBudgetRice = '';
        $this->selectedBudgetMeal = '';
        $this->selectedBudgetUtensil = '';
        $this->selectedBudgetUtensils = [];
        $this->budgetMealUtensilQuantities = [];
    }

    public function updatedExtraUtensilIds(): void
    {
        $selectedIds = collect($this->extraUtensilIds)
            ->map(fn ($id) => (string) $id)
            ->values()
            ->all();

        $this->extraUtensilIds = $selectedIds;
        $this->extraUtensilQuantities = collect($this->extraUtensilQuantities)
            ->filter(fn ($quantity, $id) => in_array((string) $id, $selectedIds, true))
            ->map(fn ($quantity) => max(1, (int) $quantity))
            ->all();

        foreach ($selectedIds as $id) {
            $this->extraUtensilQuantities[$id] = max(1, (int) ($this->extraUtensilQuantities[$id] ?? 1));
        }
    }

    public function updatedSelectedBudgetUtensils(): void
    {
        $selectedIds = collect($this->selectedBudgetUtensils)
            ->map(fn ($id) => (string) $id)
            ->values()
            ->all();

        $this->selectedBudgetUtensils = $selectedIds;
        $this->budgetMealUtensilQuantities = collect($this->budgetMealUtensilQuantities)
            ->filter(fn ($quantity, $id) => in_array((string) $id, $selectedIds, true))
            ->map(fn ($quantity) => max(1, (int) $quantity))
            ->all();

        foreach ($selectedIds as $id) {
            $this->budgetMealUtensilQuantities[$id] = max(1, (int) ($this->budgetMealUtensilQuantities[$id] ?? 1));
        }
    }

    public function increaseBudgetMealUtensil(string $utensilId): void
    {
        if (! in_array($utensilId, $this->selectedBudgetUtensils, true)) {
            $this->selectedBudgetUtensils[] = $utensilId;
        }

        $availableStock = (int) optional($this->budgetMealUtensilOptions->firstWhere('id', (int) $utensilId)?->inventory)->quantity;
        $current = max(0, (int) ($this->budgetMealUtensilQuantities[$utensilId] ?? 0));

        if ($availableStock > 0 && $current >= $availableStock) {
            $this->notify('danger', 'Stock Limit', "Only {$availableStock} in stock.");
            return;
        }

        $this->budgetMealUtensilQuantities[$utensilId] = $current + 1;
    }

    public function increaseExtraUtensil(string $utensilId): void
    {
        if (! in_array($utensilId, $this->extraUtensilIds, true)) {
            $this->extraUtensilIds[] = $utensilId;
        }

        $availableStock = (int) optional($this->extraUtensilOptions->firstWhere('id', (int) $utensilId)?->inventory)->quantity;
        $current = max(0, (int) ($this->extraUtensilQuantities[$utensilId] ?? 0));

        if ($availableStock > 0 && $current >= $availableStock) {
            $this->notify('danger', 'Stock Limit', "Only {$availableStock} in stock.");
            return;
        }

        $this->extraUtensilQuantities[$utensilId] = $current + 1;
    }

    public function decreaseBudgetMealUtensil(string $utensilId): void
    {
        $current = max(0, (int) ($this->budgetMealUtensilQuantities[$utensilId] ?? 0));

        if ($current <= 1) {
            unset($this->budgetMealUtensilQuantities[$utensilId]);
            $this->selectedBudgetUtensils = array_values(array_filter(
                $this->selectedBudgetUtensils,
                fn ($id) => (string) $id !== $utensilId
            ));

            return;
        }

        $this->budgetMealUtensilQuantities[$utensilId] = $current - 1;
    }

    public function decreaseExtraUtensil(string $utensilId): void
    {
        $current = max(0, (int) ($this->extraUtensilQuantities[$utensilId] ?? 0));

        if ($current <= 1) {
            unset($this->extraUtensilQuantities[$utensilId]);
            $this->extraUtensilIds = array_values(array_filter(
                $this->extraUtensilIds,
                fn ($id) => (string) $id !== $utensilId
            ));

            return;
        }

        $this->extraUtensilQuantities[$utensilId] = $current - 1;
    }

    public function addBudgetMealToCart(): void
    {
        $rice = $this->budgetMealRiceOptions->firstWhere('id', (int) $this->selectedBudgetRice);
        $meal = $this->budgetMealMainOptions->firstWhere('id', (int) $this->selectedBudgetMeal);
        $utensils = $this->budgetMealUtensilOptions
            ->filter(fn ($utensil) => max(0, (int) ($this->budgetMealUtensilQuantities[$utensil->id] ?? 0)) > 0)
            ->values();

        if (! $rice || ! $meal || $utensils->isEmpty()) {
            $this->notify('danger', 'Missing Selection', 'Choose a rice, a meal, and at least one utensil to build the budget meal.');
            return;
        }

        $cartId = 'budgetmeal_' . now()->timestamp . '_' . count($this->cart);
        $components = [
            [
                'item_id' => $rice->id,
                'name' => $rice->name,
                'price' => (int) $rice->price,
                'quantity' => 1,
            ],
            [
                'item_id' => $meal->id,
                'name' => $meal->name,
                'price' => (int) $meal->price,
                'quantity' => 1,
            ],
        ];

        foreach ($utensils as $utensil) {
            $quantity = max(1, (int) ($this->budgetMealUtensilQuantities[$utensil->id] ?? 1));
            $components[] = [
                'item_id' => $utensil->id,
                'name' => $utensil->name,
                'price' => (int) $utensil->price,
                'quantity' => $quantity,
            ];
        }

        $this->cart[$cartId] = [
            'id' => $cartId,
            'name' => 'Budget Meal',
            'price' => (int) ($rice->price + $meal->price + $utensils->sum(
                fn ($utensil) => (int) $utensil->price * max(1, (int) ($this->budgetMealUtensilQuantities[$utensil->id] ?? 1))
            )),
            'quantity' => 1,
            'is_bundle' => true,
            'bundle_label' => "{$rice->name} + {$meal->name} + " . $utensils->map(function ($utensil) {
                $quantity = max(1, (int) ($this->budgetMealUtensilQuantities[$utensil->id] ?? 1));

                return $quantity > 1 ? "{$utensil->name} x{$quantity}" : $utensil->name;
            })->implode(', '),
            'components' => $components,
        ];

        $this->closeBudgetMealModal();
    }

    public function addExtraUtensilsToCart(): void
    {
        $utensils = $this->extraUtensilOptions
            ->filter(fn ($utensil) => max(0, (int) ($this->extraUtensilQuantities[$utensil->id] ?? 0)) > 0)
            ->values();

        if ($utensils->isEmpty()) {
            $this->notify('danger', 'Missing Selection', 'Choose at least one extra utensil.');
            return;
        }

        $cartId = 'extrautensils_' . now()->timestamp . '_' . count($this->cart);
        $components = [];

        foreach ($utensils as $utensil) {
            $quantity = max(1, (int) ($this->extraUtensilQuantities[$utensil->id] ?? 1));
            $components[] = [
                'item_id' => $utensil->id,
                'name' => $utensil->name,
                'price' => (int) $utensil->price,
                'quantity' => $quantity,
            ];
        }

        $this->cart[$cartId] = [
            'id' => $cartId,
            'name' => 'Extra Utensils',
            'price' => (int) $utensils->sum(
                fn ($utensil) => (int) $utensil->price * max(1, (int) ($this->extraUtensilQuantities[$utensil->id] ?? 1))
            ),
            'quantity' => 1,
            'is_bundle' => true,
            'bundle_label' => $utensils->map(function ($utensil) {
                $quantity = max(1, (int) ($this->extraUtensilQuantities[$utensil->id] ?? 1));

                return $quantity > 1 ? "{$utensil->name} x{$quantity}" : $utensil->name;
            })->implode(', '),
            'components' => $components,
        ];

        $this->closeExtraUtensilsModal();
    }

    public function removeFromCart($itemId): void
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
        if (! $isCredit && (float) $this->paid_amount <= 0) {
            session()->flash('checkout_alert', 'Enter the tendered amount before checking out.');
            return;
        }

        if (! $isCredit && (float) $this->paid_amount < $this->subtotal) {
            session()->flash('checkout_alert', 'Tendered amount is not enough for this order.');
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
                if (! empty($item['is_bundle'])) {
                    foreach ($item['components'] as $component) {
                        $requiredQuantity = (int) $component['quantity'] * (int) $item['quantity'];
                        $inventory = Inventory::where('item_id', $component['item_id'])->first();

                        if (! $inventory || $inventory->quantity < $requiredQuantity) {
                            throw new \RuntimeException("Insufficient stock for {$component['name']}.");
                        }

                        SaleItem::create([
                            'sale_id'  => $sale->id,
                            'item_id'  => $component['item_id'],
                            'quantity' => $requiredQuantity,
                            'price'    => $component['price'],
                        ]);

                        $inventory->decrement('quantity', $requiredQuantity);
                    }

                    continue;
                }

                SaleItem::create([
                    'sale_id'  => $sale->id,
                    'item_id'  => $item['id'],
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                ]);

                $inventory = Inventory::where('item_id', $item['id'])->first();
                if ($inventory) {
                    if ($inventory->quantity < (int) $item['quantity']) {
                        throw new \RuntimeException("Insufficient stock for {$item['name']}.");
                    }

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
            $this->closeExtraUtensilsModal();
            $this->closeBudgetMealModal();
            $this->mount();

            $this->dispatch('sale-completed', saleId: $sale->id);
            $this->notify('success', 'Sale Completed!', 'The transaction has been recorded.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify('danger', 'Sale Failed', $e->getMessage() ?: 'Something went wrong. Please try again.');
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
