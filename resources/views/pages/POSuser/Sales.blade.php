<div class="max-w-7xl mx-auto py-8 px-4">

    {{-- ═══════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-50 rounded-lg">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Point of Sale</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Sales Transactions</h1>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SUMMARY CARDS
    ═══════════════════════════════════════════ --}}    
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Total Transactions</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ number_format($this->summary['count']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Gross Sales</p>
            <p class="text-2xl font-extrabold text-amber-600">₱{{ number_format($this->summary['total']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Total Collected</p>
            <p class="text-2xl font-extrabold text-green-600">₱{{ number_format($this->summary['paid']) }}</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         FILTERS
    ═══════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3 items-end">

            {{-- Search --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Customer</label>
                <div class="relative">
                    
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="    Search customer…"
                        class="w-full pl-9 pr-4 py-2 text-sm bg-stone-50 border border-gray-200 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all"
                    />
                </div>
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Date From</label>
                <input
                    wire:model.live="dateFrom"
                    type="date"
                    class="py-2 px-3 text-sm bg-stone-50 border border-gray-200 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all"
                />
            </div>

            {{-- Date To --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Date To</label>
                <input
                    wire:model.live="dateTo"
                    type="date"
                    class="py-2 px-3 text-sm bg-stone-50 border border-gray-200 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all"
                />
            </div>

            {{-- Payment Method --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Payment Method</label>
                <select
                    wire:model.live="payMethod"
                    class="py-2 px-3 text-sm bg-stone-50 border border-gray-200 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent
                           transition-all bg-white appearance-none cursor-pointer pr-8"
                >
                    <option value="">All Methods</option>
                    <option value="Cash">Cash</option>
                    <option value="Gcash">GCash</option>
                    <option value="Credit">Credit</option>
                </select>
            </div>

            {{-- Clear Filters --}}
            @if($search || $dateFrom || $dateTo || $payMethod)
                <button
                    wire:click="clearFilters"
                    class="flex items-center gap-1.5 py-2 px-3 text-sm font-semibold text-gray-500
                           hover:text-red-600 border border-dashed border-gray-300 hover:border-red-300
                           rounded-lg transition-colors"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SALES TABLE
    ═══════════════════════════════════════════ --}}
    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">

        {{-- Table Header --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-base font-bold text-gray-800">Transaction Records</h3>
            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                {{ $this->summary['count'] }} {{ Str::plural('transaction', $this->summary['count']) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sold Items</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($this->sales as $sale)
                        @php
                            $itemCount = $sale->saleItems->sum('quantity');
                            $change    = $sale->paid_amount - $sale->total;
                        @endphp
                        <tr class="hover:bg-amber-50/40 transition-colors">

                            {{-- ID --}}
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-400">#{{ $sale->id }}</span>
                            </td>

                            {{-- Customer --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">
                                        {{ $sale->customer?->name ?? 'Walk-in' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Sold Items --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-sm font-bold text-gray-800">{{ $itemCount }}</span>
                                    <span class="text-xs text-gray-400">{{ Str::plural('pc', $itemCount) }}</span>
                                    <span class="text-xs text-gray-300 mx-0.5">·</span>
                                    <span class="text-xs text-gray-500">{{ $sale->saleItems->count() }} {{ Str::plural('item', $sale->saleItems->count()) }}</span>
                                </div>
                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">₱{{ number_format($sale->total) }}</span>
                            </td>

                            {{-- Paid --}}
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-800">₱{{ number_format($sale->paid_amount) }}</span>
                                @if($change > 0)
                                    <p class="text-xs text-blue-500 font-medium">↩ ₱{{ number_format($change) }} change</p>
                                @endif
                            </td>

                            {{-- Payment Method --}}
                            <td class="px-6 py-4">
                                @php
                                    $methodColors = [
                                        'Cash'   => 'bg-green-100 text-green-700',
                                        'Gcash'  => 'bg-blue-100 text-blue-700',
                                        'Credit' => 'bg-purple-100 text-purple-700',
                                    ];
                                    $color = $methodColors[$sale->payment_method] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ $sale->payment_method ?? '—' }}
                                </span>
                            </td>

                            {{-- Time --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 font-medium">{{ $sale->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $sale->created_at->format('h:i A') }}</p>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right space-x-2">
                                <button
                                    wire:click="viewDetail({{ $sale->id }})"
                                    class="rounded-md bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700
                                           shadow-sm hover:bg-amber-100 transition-colors"
                                >
                                    View
                                </button>
                                <button
                                    wire:click="confirmDelete({{ $sale->id }})"
                                    class="text-red-500 hover:text-red-700 text-xs font-semibold transition-colors"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">
                                        {{ ($search || $dateFrom || $dateTo || $payMethod) ? 'No transactions match your filters.' : 'No sales recorded yet.' }}
                                    </p>
                                    @if($search || $dateFrom || $dateTo || $payMethod)
                                        <button wire:click="clearFilters" class="mt-2 text-xs text-amber-600 hover:underline font-medium">
                                            Clear all filters
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($this->sales->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $this->sales->links() }}
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════
         VIEW DETAIL MODAL
    ═══════════════════════════════════════════ --}}
    @if($showDetail && $this->detail)
        @php $d = $this->detail; @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('showDetail', false)"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl sm:w-full sm:max-w-2xl">

                    {{-- Modal Header --}}
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-amber-100 rounded-lg">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Transaction #{{ $d->id }}</h3>
                                    <p class="text-xs text-gray-400">{{ $d->created_at->format('F d, Y · h:i A') }}</p>
                                </div>
                            </div>
                            <button wire:click="$set('showDetail', false)"
                                    class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-5 space-y-5">

                        {{-- Info Row --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-0.5">Customer</p>
                                <p class="text-sm font-bold text-gray-800">{{ $d->customer?->name ?? 'Walk-in' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-0.5">Payment Method</p>
                                @php
                                    $methodColors = [
                                        'Cash'   => 'bg-green-100 text-green-700',
                                        'Gcash'  => 'bg-blue-100 text-blue-700',
                                        'Credit' => 'bg-purple-100 text-purple-700',
                                    ];
                                    $dc = $methodColors[$d->payment_method] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $dc }} mt-0.5">
                                    {{ $d->payment_method ?? '—' }}
                                </span>
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Sold Items</p>
                            <div class="rounded-lg border border-gray-200 overflow-hidden">
                                <table class="w-full text-sm divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Item</th>
                                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Unit Price</th>
                                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach($d->saleItems as $si)
                                            <tr>
                                                <td class="px-4 py-3 font-medium text-gray-800">{{ $si->item?->name ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center text-gray-600">{{ $si->quantity }}</td>
                                                <td class="px-4 py-3 text-right text-gray-600">₱{{ number_format($si->price) }}</td>
                                                <td class="px-4 py-3 text-right font-bold text-gray-800">₱{{ number_format($si->price * $si->quantity) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Totals --}}
                        <div class="rounded-lg border border-gray-200 bg-stone-50 overflow-hidden">
                            <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-200">
                                <span class="text-xs text-gray-500 font-medium">Order Total</span>
                                <span class="text-sm font-bold text-gray-800">₱{{ number_format($d->total) }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-200">
                                <span class="text-xs text-gray-500 font-medium">Amount Paid</span>
                                <span class="text-sm font-bold text-green-700">₱{{ number_format($d->paid_amount) }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2.5">
                                <span class="text-xs text-gray-500 font-medium">Change Given</span>
                                <span class="text-sm font-bold text-blue-600">₱{{ number_format($d->paid_amount - $d->total) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 flex justify-end rounded-b-xl">
                        <button
                            wire:click="$set('showDetail', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-5 py-2 text-sm font-semibold
                                   text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         DELETE CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" wire:click="$set('confirmingDeletion', false)"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-xl bg-white shadow-xl sm:w-full sm:max-w-md">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full bg-red-100">
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Delete Sale #{{ $deletingId }}</h3>
                                <p class="mt-1.5 text-sm text-gray-500">
                                    This will permanently delete the transaction and all its line items. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-xl">
                        <button type="button" wire:click="delete"
                            class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-bold
                                   text-white shadow-sm hover:bg-red-500 transition-colors active:scale-95">
                            Delete Permanently
                        </button>
                        <button type="button" wire:click="$set('confirmingDeletion', false)"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold
                                   text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         TOAST NOTIFICATION
    ═══════════════════════════════════════════ --}}
    @if (session()->has('message'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5"
        >
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full bg-green-100">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Done!</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </div>
            <div class="h-1 bg-green-500"
                 style="animation: shrink 4s linear forwards; @keyframes shrink { from{width:100%} to{width:0%} }">
            </div>
        </div>
    @endif

</div>