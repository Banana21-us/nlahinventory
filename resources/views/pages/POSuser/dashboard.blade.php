{{--
    ╔══════════════════════════════════════════════════╗
    ║  POS COMMAND CENTRE  — Posdashboard.blade.php    ║
    ║  Aesthetic: Editorial luxury — warm stone + gold ║
    ╚══════════════════════════════════════════════════╝
--}}
<div class="min-h-screen bg-[#F7F5F2] font-sans">

    {{-- ── TOP BAR ── --}}
    <div class="px-8 pt-8 pb-0 flex items-end justify-between">
        <div>
            <p class="text-[10px] font-black tracking-[0.35em] uppercase text-amber-500 mb-1">Point of Sale</p>
            <h1 class="text-3xl font-black text-stone-900 tracking-tight leading-none">
                Command Centre
            </h1>
            <p class="text-sm text-stone-400 font-medium mt-1" id="dash-date"></p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-stone-200">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-bold text-stone-500">Live</span>
            <span class="text-xs font-mono font-bold text-stone-700" id="dash-clock">--:--</span>
        </div>
    </div>

    {{-- ── THIN GOLD RULE ── --}}
    <div class="mx-8 mt-4 mb-6 h-px bg-gradient-to-r from-amber-400 via-amber-200 to-transparent"></div>

    {{-- ── MAIN CONTENT ── --}}
    <div class="px-8 pb-10 flex flex-col gap-6">

        {{-- Row 1: Stats --}}
        <livewire:pointofsale.application-stats />

        {{-- Row 2: Sales Trend (full width) --}}
        <livewire:pointofsale.sales-trend-chart />

        {{-- Row 3: Three charts --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 gutter-5">
            <livewire:pointofsale.top-products-chart />
            <livewire:pointofsale.payment-method-chart />
            <livewire:pointofsale.yearly-customer-chart />
        </div>

        {{-- Row 4: Latest Sales --}}
        <livewire:pointofsale.latest-sales />

    </div>
</div>

<script>
    (function tick() {
        const now  = new Date();
        const cl   = document.getElementById('dash-clock');
        const dt   = document.getElementById('dash-date');
        if (cl) cl.textContent = now.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' });
        if (dt) dt.textContent = now.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        setTimeout(tick, 1000);
    })();
</script>