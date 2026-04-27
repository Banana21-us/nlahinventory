<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary"
     x-data="nurseSchedule(@js($selectedDate))"
     x-init="init()"
     @click="handleScheduleClick($event)">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
    .brand-bg-accent         { background-color: #f0b626; }
    .brand-bg-accent-light   { background-color: #fef8e7; }
    .brand-text-accent       { color: #f0b626; }
    .brand-bg-teal           { background-color: #027c8b; }
    .brand-bg-teal-light     { background-color: #e6f4f5; }
    .brand-text-teal         { color: #027c8b; }
    .brand-btn-primary { background-color:#015581;color:#fff;transition:background-color .15s; }
    .brand-btn-primary:hover { background-color:#01406a; }
    .brand-btn-teal { background-color:#027c8b;color:#fff;transition:background-color .15s; }
    .brand-btn-teal:hover { background-color:#016070; }
    .brand-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(1,85,129,.2);border-color:#015581; }

    /* ── Date Picker ── */
    .dp-track { display:flex;gap:6px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;padding-bottom:4px; }
    .dp-track::-webkit-scrollbar { display:none; }
    .dp-day { min-width:52px;border-radius:10px;border:2px solid transparent;cursor:pointer;transition:all .18s ease;text-align:center;padding:6px 4px;user-select:none;flex-shrink:0; }
    .dp-day:hover { background-color:#e6f0f7;border-color:#015581; }
    .dp-day.dp-active { background-color:#015581 !important;border-color:#015581 !important; }
    .dp-day.dp-active .dp-dayname,
    .dp-day.dp-active .dp-num { color:#fff !important; }
    .dp-day.dp-today:not(.dp-active) { border-color:#f0b626 !important; }
    .dp-num { font-size:1.35rem;font-weight:800;line-height:1.2;color:#1e293b; }
    .dp-dayname { font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-top:2px; }
    .dp-monyear-btn { border-radius:8px;padding:4px 10px;font-size:.8rem;font-weight:700;cursor:pointer;transition:background .15s;border:none; }

    /* ── Section headers ── */
    .section-header { display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid #e2e8f0;background:#f8fafc; }
    .section-title  { font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase; }

    /* ── Shift grid ── */
    .shift-grid { display:grid;grid-template-columns:80px 1fr; }
    .shift-label { font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding:10px 14px;border-right:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;display:flex;align-items:center; }
    .shift-cell  { padding:8px 12px;border-bottom:1px solid #f1f5f9;min-height:54px; }
    .shift-cell-header { font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;padding:6px 12px;border-bottom:1px solid #e2e8f0;background:#fafafa; }

    /* ── Nurse pill ── */
    .nurse-pill { display:inline-flex;align-items:center;gap:5px;background:#e6f0f7;color:#015581;border:1px solid #bfdbee;border-radius:999px;padding:2px 8px 2px 4px;font-size:.72rem;font-weight:600;margin:2px;white-space:nowrap; }
    .nurse-pill .np-avatar { width:18px;height:18px;border-radius:50%;background:#015581;color:#fff;font-size:.6rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
    .nurse-pill .np-remove { width:14px;height:14px;border-radius:50%;background:#bfdbee;color:#015581;display:flex;align-items:center;justify-content:center;font-size:.65rem;cursor:pointer;flex-shrink:0;border:none;transition:background .12s;line-height:1; }
    .nurse-pill .np-remove:hover { background:#015581;color:#fff; }

    /* ── Add btn ── */
    .add-nurse-btn { display:inline-flex;align-items:center;gap:4px;background:#f0f9ff;border:1.5px dashed #93c5fd;color:#015581;border-radius:999px;padding:2px 10px;font-size:.7rem;font-weight:700;cursor:pointer;transition:all .15s;white-space:nowrap;margin:2px; }
    .add-nurse-btn:hover { background:#015581;color:#fff;border-color:#015581;border-style:solid; }
    .add-nurse-btn svg { width:12px;height:12px; }

    /* ── Modal nurse option ── */
    .nurse-option { padding:8px 14px;cursor:pointer;font-size:.85rem;display:flex;align-items:center;gap:10px;border-radius:6px;transition:background .1s; }
    .nurse-option:hover { background:#e6f0f7; }

    /* ── Excel Preview ── */
    .xl-table { border-collapse:collapse;width:100%;font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;table-layout:auto; }
    .xl-table th,.xl-table td { border:1px solid #9ca3af;padding:8px 12px;text-align:left;word-wrap:break-word;word-break:break-word;font-size:.75rem; }
    .xl-table th { background:#d1fae5;font-weight:700;text-align:center; }
    .xl-section-row td { background:#015581;color:#fff;font-weight:800;letter-spacing:.06em;text-transform:uppercase;text-align:center;font-size:.7rem; }
    .xl-shift-label { background:#e6f0f7;font-weight:700;color:#015581;font-size:.7rem;white-space:nowrap; }
    .xl-table tbody tr:hover td { background:#fef9ee; }

    @keyframes shrink { from { width:100% } to { width:0% } }
    @keyframes fadeIn { from { opacity:0;transform:translateY(6px) } to { opacity:1;transform:translateY(0) } }
    .fade-in { animation:fadeIn .2s ease forwards; }
    [x-cloak] { display:none !important; }

    .dp-day.dp-active.dp-today {
        background-color: #015581 !important;
        border-color: #f0b626 !important;
        border-width: 2px;
    }
    .dp-day.dp-active.dp-today .dp-num,
    .dp-day.dp-active.dp-today .dp-dayname { color: #fff !important; }

    @media (max-width: 639px) {
        .shift-grid { grid-template-columns: 48px 1fr; }
        .shift-label { font-size:.6rem;padding:8px 6px;letter-spacing:0; }
        .shift-cell  { padding:5px 6px;min-height:40px; }
        .shift-cell-header { font-size:.6rem;padding:5px 6px; }
        .section-header { padding:8px 10px; }
        .section-title { font-size:.6rem; }
        .nurse-pill { font-size:.65rem;padding:2px 5px 2px 3px;gap:3px; }
        .nurse-pill .np-avatar { width:15px;height:15px;font-size:.55rem; }
        .add-nurse-btn { font-size:.62rem;padding:2px 6px;gap:3px; }
        .dp-day { min-width:44px;padding:5px 3px; }
        .dp-num { font-size:1.1rem; }
    }
</style>

{{-- ═══════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div class="flex items-center gap-3">
        <div class="p-2 rounded-lg brand-bg-primary-light">
            <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">System</p>
            <h1 class="text-xl font-bold text-gray-800 leading-tight">Nurses Schedule</h1>
        </div>
    </div>

    <div class="flex items-center gap-2">
        {{-- AI Auto-Schedule --}}
        <button type="button" @click="$store.autoModal.open()"
            class="group relative text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2 border border-violet-500 text-violet-700 bg-violet-50 transition-all duration-200 hover:bg-violet-100 hover:shadow-md active:scale-95">
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <span>AI Auto-Schedule</span>
        </button>

        {{-- Preview (Excel) --}}
        <button type="button" @click="openPreview()"
            class="group relative text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2 border border-green-600 text-green-700 bg-green-50 transition-all duration-200 hover:bg-green-100 hover:shadow-md active:scale-95">
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Preview (Excel)</span>
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     DATE PICKER CARD
═══════════════════════════════════════════ --}}
<div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="px-5 pt-4 pb-3 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-2">
            <button @click="prevMonth()"
                class="dp-monyear-btn brand-bg-primary-light brand-text-primary hover:bg-blue-100 flex items-center justify-center w-8 h-8 p-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <select @change="currentMonth = +$event.target.value; buildDays()"
                class="brand-focus border border-gray-200 rounded-lg px-3 py-1.5 text-sm font-bold text-gray-700 bg-white cursor-pointer">
                <template x-for="(m, i) in monthNames" :key="i">
                    <option :value="i" :selected="i === currentMonth" x-text="m"></option>
                </template>
            </select>
            <select @change="currentYear = +$event.target.value; buildDays()"
                class="brand-focus border border-gray-200 rounded-lg px-3 py-1.5 text-sm font-bold text-gray-700 bg-white cursor-pointer">
                <template x-for="y in yearRange" :key="y">
                    <option :value="y" :selected="y === currentYear" x-text="y"></option>
                </template>
            </select>
            <button @click="nextMonth()"
                class="dp-monyear-btn brand-bg-primary-light brand-text-primary hover:bg-blue-100 flex items-center justify-center w-8 h-8 p-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <div class="flex items-center gap-3 text-xs text-gray-500">
            <span class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full" style="background-color:#015581;"></span>
                Selected
            </span>
            <span class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded" style="border:2px solid #f0b626;background:transparent;"></span>
                Today
            </span>
            <button @click="goToday()"
                class="relative overflow-hidden group px-6 py-2 rounded-lg brand-bg-accent text-white text-[11px] font-black uppercase tracking-tighter shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 active:scale-95 flex items-center gap-2 border-b-2 border-yellow-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Today
            </button>
        </div>
    </div>

    <div class="px-4 py-3 relative">
        <button @click="scrollDays(-3)"
            class="absolute left-0 top-1/2 -translate-y-1/2 z-10 w-7 h-full bg-gradient-to-r from-white to-transparent flex items-center justify-start pl-1 border-none cursor-pointer">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <div class="dp-track px-5" x-ref="dpTrack">
            <template x-for="day in days" :key="day.date">
                <div class="dp-day"
                    :class="{ 'dp-active': selectedDate === day.date, 'dp-today': day.isToday }"
                    @click="selectDate(day.date)">
                    <div class="dp-num" x-text="day.num"></div>
                    <div class="dp-dayname" x-text="day.name"></div>
                </div>
            </template>
        </div>
        <button @click="scrollDays(3)"
            class="absolute right-0 top-1/2 -translate-y-1/2 z-10 w-7 h-full bg-gradient-to-l from-white to-transparent flex items-center justify-end pr-1 border-none cursor-pointer">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    <div class="px-5 pb-3 flex items-center gap-2">
        <span class="text-xs text-gray-400 font-medium">Viewing schedule for:</span>
        <span class="text-sm font-bold brand-text-primary" x-text="formattedSelectedDate()"></span>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     SCHEDULE SECTIONS
═══════════════════════════════════════════ --}}
<div class="space-y-5">

    {{-- ── EMERGENCY ROOM ── --}}
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
        <div class="section-header" style="background:#fff1f2;">
            <div class="p-1.5 rounded" style="background:#dc2626;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="section-title" style="color:#dc2626;">Emergency Room (ER)</span>
            <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:#fecaca;color:#991b1b;">3 nurses/day</span>
        </div>
        <div class="shift-grid">
            <div class="shift-label bg-gray-50" style="border-bottom:1px solid #e2e8f0;">Shift</div>
            <div class="shift-cell-header text-center">Nurse</div>
            @foreach(['am' => 'AM', 'pm' => 'PM', 'noc' => 'NOC'] as $period => $label)
                <div class="shift-label">{{ $label }}</div>
                <div class="shift-cell flex flex-wrap items-start content-start gap-1 pt-2">
                    @php $entry = $schedule['er'][$period] ?? null; @endphp
                    @if($entry)
                        <span class="nurse-pill" wire:key="pill-er-{{ $period }}-{{ $entry['id'] }}"
                            style="background:#fff1f2;color:#dc2626;border-color:#fecaca;">
                            <span class="np-avatar" style="background:#dc2626;">{{ strtoupper(substr($entry['name'],0,1)) }}</span>
                            <span>{{ $entry['name'] }}</span>
                            <button class="np-remove" style="background:#fecaca;color:#dc2626;"
                                data-remove-id="{{ $entry['id'] }}" title="Remove">✕</button>
                        </span>
                    @else
                        <button class="add-nurse-btn" style="border-color:#fca5a5;color:#dc2626;"
                            data-section="er" data-period="{{ $period }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── TRIAGE ── --}}
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
        <div class="section-header" style="background:#fff7ed;">
            <div class="p-1.5 rounded" style="background:#ea580c;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <span class="section-title" style="color:#ea580c;">Triage</span>
            <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:#fed7aa;color:#9a3412;">3 nurses/day</span>
        </div>
        <div class="shift-grid">
            <div class="shift-label bg-gray-50" style="border-bottom:1px solid #e2e8f0;">Shift</div>
            <div class="shift-cell-header text-center">Nurse</div>
            @foreach(['am' => 'AM', 'pm' => 'PM', 'noc' => 'NOC'] as $period => $label)
                <div class="shift-label">{{ $label }}</div>
                <div class="shift-cell flex flex-wrap items-start content-start gap-1 pt-2">
                    @php $entry = $schedule['triage'][$period] ?? null; @endphp
                    @if($entry)
                        <span class="nurse-pill" wire:key="pill-triage-{{ $period }}-{{ $entry['id'] }}"
                            style="background:#fff7ed;color:#ea580c;border-color:#fed7aa;">
                            <span class="np-avatar" style="background:#ea580c;">{{ strtoupper(substr($entry['name'],0,1)) }}</span>
                            <span>{{ $entry['name'] }}</span>
                            <button class="np-remove" style="background:#fed7aa;color:#ea580c;"
                                data-remove-id="{{ $entry['id'] }}" title="Remove">✕</button>
                        </span>
                    @else
                        <button class="add-nurse-btn" style="border-color:#fdba74;color:#ea580c;"
                            data-section="triage" data-period="{{ $period }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── WARD ── --}}
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
        <div class="section-header brand-bg-primary-light">
            <div class="p-1.5 rounded brand-bg-primary">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="section-title brand-text-primary">Ward</span>
            <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full brand-bg-primary-light brand-text-primary border border-blue-200">3 nurses/day</span>
        </div>
        <div class="shift-grid">
            <div class="shift-label bg-gray-50" style="border-bottom:1px solid #e2e8f0;">Shift</div>
            <div class="shift-cell-header text-center">Nurse</div>
            @foreach(['am' => 'AM', 'pm' => 'PM', 'noc' => 'NOC'] as $period => $label)
                <div class="shift-label">{{ $label }}</div>
                <div class="shift-cell flex flex-wrap items-start content-start gap-1 pt-2">
                    @php $entry = $schedule['ward'][$period] ?? null; @endphp
                    @if($entry)
                        <span class="nurse-pill" wire:key="pill-ward-{{ $period }}-{{ $entry['id'] }}">
                            <span class="np-avatar">{{ strtoupper(substr($entry['name'],0,1)) }}</span>
                            <span>{{ $entry['name'] }}</span>
                            <button class="np-remove" data-remove-id="{{ $entry['id'] }}" title="Remove">✕</button>
                        </span>
                    @else
                        <button class="add-nurse-btn"
                            data-section="ward" data-period="{{ $period }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── OPD (closed weekends) ── --}}
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
        <div class="section-header" style="background:#f5f3ff;">
            <div class="p-1.5 rounded" style="background:#7c3aed;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <span class="section-title" style="color:#7c3aed;">OPD (Out-Patient Department)</span>
            @if($isOpdClosed)
                <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:#e5e7eb;color:#6b7280;">
                    Closed — Weekend
                </span>
            @else
                <span class="ml-auto text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:#ede9fe;color:#5b21b6;">1 nurse/day · 8-5</span>
            @endif
        </div>
        <div class="shift-grid">
            <div class="shift-label bg-gray-50" style="border-bottom:1px solid #e2e8f0;">Shift</div>
            <div class="shift-cell-header text-center">Nurse</div>
            <div class="shift-label">8–5</div>
            <div class="shift-cell flex flex-wrap items-start content-start gap-1 pt-2">
                @if($isOpdClosed)
                    <span class="text-xs text-gray-400 italic flex items-center gap-1.5 mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Closed on Saturdays & Sundays
                    </span>
                @else
                    @php $entry = $schedule['opd']['day'] ?? null; @endphp
                    @if($entry)
                        <span class="nurse-pill" wire:key="pill-opd-day-{{ $entry['id'] }}"
                            style="background:#f5f3ff;color:#7c3aed;border-color:#ddd6fe;">
                            <span class="np-avatar" style="background:#7c3aed;">{{ strtoupper(substr($entry['name'],0,1)) }}</span>
                            <span>{{ $entry['name'] }}</span>
                            <button class="np-remove" style="background:#ddd6fe;color:#7c3aed;"
                                data-remove-id="{{ $entry['id'] }}" title="Remove">✕</button>
                        </span>
                    @else
                        <button class="add-nurse-btn" style="border-color:#c4b5fd;color:#7c3aed;"
                            data-section="opd" data-period="day">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

</div>{{-- /schedule sections --}}


{{-- ═══════════════════════════════════════════
     AI AUTO-SCHEDULE MODAL
     Controlled entirely by Alpine.store('autoModal').
     wire:ignore keeps Livewire from patching this section.
═══════════════════════════════════════════ --}}
<div wire:ignore
     x-show="$store.autoModal.isOpen"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog" aria-modal="true"
     @keydown.escape.window="$store.autoModal.close()">

    <div class="fixed inset-0 bg-gray-900/75 transition-opacity"
         @click="$store.autoModal.close()"></div>

    <div class="flex min-h-full items-center justify-center p-4" @click.stop>
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl w-full max-w-md fade-in"
             style="border-top:4px solid #7c3aed;">

            <div class="bg-white px-6 pt-6 pb-4">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg" style="background:#f5f3ff;">
                            <svg class="w-5 h-5" style="color:#7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">AI Auto-Generate Schedule</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Algorithm assigns nurses based on rotation & leave data</p>
                        </div>
                    </div>
                    <button @click="$store.autoModal.close()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                    </button>
                </div>

                {{-- Block selection --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">Schedule Block</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-start gap-2 p-3 rounded-lg border-2 cursor-pointer transition-all"
                               :class="$store.autoModal.block === 'A' ? 'border-violet-500 bg-violet-50' : 'border-gray-200 hover:border-violet-300'">
                            <input type="radio" x-model="$store.autoModal.block" value="A" class="mt-0.5 accent-violet-600">
                            <div>
                                <p class="text-sm font-bold text-gray-800">Block A</p>
                                <p class="text-xs text-gray-400">11th – 25th</p>
                                <p class="text-xs font-semibold text-violet-600 mt-0.5"
                                   x-show="$store.autoModal.block === 'A'"
                                   x-text="$store.autoModal.blockDateRange"></p>
                            </div>
                        </label>
                        <label class="flex items-start gap-2 p-3 rounded-lg border-2 cursor-pointer transition-all"
                               :class="$store.autoModal.block === 'B' ? 'border-violet-500 bg-violet-50' : 'border-gray-200 hover:border-violet-300'">
                            <input type="radio" x-model="$store.autoModal.block" value="B" class="mt-0.5 accent-violet-600">
                            <div>
                                <p class="text-sm font-bold text-gray-800">Block B</p>
                                <p class="text-xs text-gray-400">26th – 10th (next month)</p>
                                <p class="text-xs font-semibold text-violet-600 mt-0.5"
                                   x-show="$store.autoModal.block === 'B'"
                                   x-text="$store.autoModal.blockDateRange"></p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Month / Year --}}
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Month</label>
                        <select @change="$store.autoModal.month = +$event.target.value"
                            class="brand-focus w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 bg-white">
                            <template x-for="(mName, i) in $store.autoModal.monthNames" :key="i">
                                <option :value="i + 1" :selected="(i + 1) === $store.autoModal.month" x-text="mName"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Year</label>
                        <select @change="$store.autoModal.year = +$event.target.value"
                            class="brand-focus w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 bg-white">
                            <template x-for="y in $store.autoModal.yearRange" :key="y">
                                <option :value="y" :selected="y === $store.autoModal.year" x-text="y"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Rules reminder --}}
                <div class="rounded-lg p-3 mb-4 text-xs text-gray-600 space-y-1" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <p class="font-bold text-gray-700 mb-1.5">Scheduling rules applied:</p>
                    <p>• Nurses rotate sections each block (ER → Triage → Ward → ER)</p>
                    <p>• Minimum 80 hours per nurse per block</p>
                    <p>• No NOC → AM back-to-back (fatigue protection)</p>
                    <p>• Approved leave days are automatically skipped</p>
                    <p>• OPD closed on Saturdays &amp; Sundays</p>
                </div>

                {{-- Warning --}}
                <div class="rounded-lg p-3 flex items-start gap-2 text-xs" style="background:#fffbeb;border:1px solid #fde68a;">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-amber-700 font-medium">Existing schedule for this block will be replaced.</span>
                </div>

                {{-- Error status --}}
                <div x-show="$store.autoModal.status" x-cloak
                     class="mt-3 rounded-lg p-3 text-sm text-red-700 font-medium" style="background:#fff1f2;border:1px solid #fecaca;">
                    <span x-text="$store.autoModal.status"></span>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3 rounded-b-xl">
                <button @click="$store.autoModal.close()"
                    class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button @click="$store.autoModal.running = true; $wire.autoGenerate($store.autoModal.block, $store.autoModal.month, $store.autoModal.year)"
                        :disabled="$store.autoModal.running"
                        class="inline-flex items-center gap-2 rounded-lg px-5 py-2 text-sm font-bold text-white shadow transition-all active:scale-95 disabled:opacity-60"
                        style="background:#7c3aed;">
                    <template x-if="!$store.autoModal.running">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Generate Schedule
                        </span>
                    </template>
                    <template x-if="$store.autoModal.running">
                        <span class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Generating…
                        </span>
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════
     ASSIGN NURSE MODAL
═══════════════════════════════════════════ --}}
<div wire:ignore
     x-show="$store.nurseModal.isOpen"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog" aria-modal="true"
     @keydown.escape.window="$store.nurseModal.close()">

    <div class="fixed inset-0 bg-gray-500/75 transition-opacity"
         @click="$store.nurseModal.close()"></div>

    <div class="flex min-h-full items-center justify-center p-4" @click.stop>
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl w-full max-w-md fade-in"
             style="border-top:4px solid #015581;">

            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg brand-bg-primary-light">
                            <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Assign Nurse</h3>
                            <p class="text-xs text-gray-400 mt-0.5">
                                <span x-text="$store.nurseModal.section.toUpperCase()"></span>
                                · <span x-text="$store.nurseModal.period.toUpperCase()"></span>
                                · <span x-text="formattedSelectedDate()"></span>
                            </p>
                        </div>
                    </div>
                    <button @click="$store.nurseModal.close()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                    </button>
                </div>

                <div class="relative mb-3">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                        x-model="$store.nurseModal.search"
                        placeholder="Search by name or employee #…"
                        class="brand-focus w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white"/>
                </div>

                <div class="max-h-56 overflow-y-auto rounded-lg border border-gray-100">
                    <template x-for="nurse in $store.nurseModal.filteredNurses" :key="nurse.id">
                        <div class="nurse-option"
                             @click="$wire.assignEmployee(nurse.id, $store.nurseModal.section, $store.nurseModal.period); $store.nurseModal.close()">
                            <div class="w-8 h-8 rounded-full brand-bg-primary flex items-center justify-center text-white font-bold text-xs flex-shrink-0"
                                 x-text="nurse.name.charAt(0).toUpperCase()"></div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm" x-text="nurse.name"></p>
                                <p class="text-xs text-gray-400" x-text="nurse.position + ' · ' + nurse.emp_no"></p>
                            </div>
                        </div>
                    </template>
                    <div x-show="$store.nurseModal.filteredNurses.length === 0"
                         class="px-4 py-8 text-center text-sm text-gray-400">No nurses found.</div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Or type a name manually</label>
                    <div class="flex gap-2">
                        <input type="text"
                            x-model="$store.nurseModal.customName"
                            placeholder="Enter name…"
                            @keydown.enter="if($store.nurseModal.customName.trim()) { $wire.assignCustom($store.nurseModal.section, $store.nurseModal.period, $store.nurseModal.customName); $store.nurseModal.close() }"
                            class="brand-focus flex-1 border border-gray-300 rounded-md px-3 py-1.5 text-sm"/>
                        <button @click="if($store.nurseModal.customName.trim()) { $wire.assignCustom($store.nurseModal.section, $store.nurseModal.period, $store.nurseModal.customName); $store.nurseModal.close() }"
                            class="brand-btn-primary text-xs font-bold px-4 py-1.5 rounded-md shadow">
                            Add
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-end rounded-b-xl">
                <button @click="$store.nurseModal.close()"
                    class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════
     SCHEDULE RANGE PREVIEW MODAL
═══════════════════════════════════════════ --}}
<div wire:ignore
     x-show="$store.nursePreview.isOpen"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog" aria-modal="true"
     @keydown.escape.window="$store.nursePreview.close()">

    <div class="fixed inset-0 bg-gray-900/80" @click="$store.nursePreview.close()"></div>

    <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-6xl fade-in overflow-hidden"
             style="border-top:4px solid #166534;"
             @click.stop>

            <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50 flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-green-100">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Schedule Preview</h3>
                </div>
                <button type="button" @click="$store.nursePreview.close()" class="text-gray-400 hover:text-gray-600 ml-auto">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
            </div>

            <div class="px-4 sm:px-6 py-3 border-b border-gray-100 bg-white flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 text-sm">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">From</label>
                    <input type="date" x-model="$store.nursePreview.from"
                           class="brand-focus border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-700 bg-white">
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">To</label>
                    <input type="date" x-model="$store.nursePreview.to"
                           class="brand-focus border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-700 bg-white">
                </div>
                <button type="button" @click="loadPreview()"
                        class="brand-btn-primary text-xs font-bold px-4 py-2 rounded-lg shadow flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Load Schedule
                </button>
            </div>

            <div class="overflow-auto max-h-[62vh] bg-white" id="preview-scroll-area">
                <div id="preview-table-container" wire:ignore>
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-400">Select a date range and click <strong>Load Schedule</strong></p>
                    </div>
                </div>
            </div>

            <div id="preview-data-store" class="hidden" x-data="{}" wire:ignore
                 x-init="$watch('$wire.previewData', value => { window.dispatchEvent(new CustomEvent('preview-data-updated', { detail: value })); })">
            </div>

            <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="window.print()"
                    class="brand-btn-teal text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
                <button type="button" @click="$store.nursePreview.close()"
                    class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════
     TOAST
═══════════════════════════════════════════ --}}
<div x-data="{ show: false, message: '' }"
     x-show="show"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-init="window.addEventListener('show-toast', (e) => { show=true; message=e.detail.message; setTimeout(()=>{ show=false; }, 2000); });"
     class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5">
    <div class="p-4 flex items-start gap-3">
        <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full brand-bg-teal-light">
            <svg class="w-5 h-5 brand-text-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-semibold text-gray-900">Success!</p>
            <p class="mt-0.5 text-sm text-gray-500" x-text="message"></p>
        </div>
        <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
            </svg>
        </button>
    </div>
    <div class="h-1" style="background-color:#f0b626;animation:shrink 2s linear forwards;"></div>
</div>
</div>

{{-- ═══════════════════════════════════════════
     ALPINE.JS
═══════════════════════════════════════════ --}}
<script>
document.addEventListener('alpine:init', () => {
    if (!window.Alpine.store('nurseModal')) {
        window.Alpine.store('nurseModal', {
            isOpen: false,
            section: '',
            period: '',
            search: '',
            customName: '',
            allNurses: @json($nurses),

            get filteredNurses() {
                if (!this.search) return this.allNurses;
                const q = this.search.toLowerCase();
                return this.allNurses.filter(n =>
                    n.name.toLowerCase().includes(q) ||
                    (n.emp_no && String(n.emp_no).toLowerCase().includes(q))
                );
            },

            openFor(section, period) {
                this.section = section;
                this.period  = period;
                this.search  = '';
                this.customName = '';
                this.isOpen  = true;
            },

            close() {
                this.isOpen     = false;
                this.search     = '';
                this.customName = '';
            },
        });
    }

    if (!window.Alpine.store('nursePreview')) {
        window.Alpine.store('nursePreview', {
            isOpen: false,
            from: @js($previewFrom),
            to:   @js($previewTo),

            open(from, to) { this.from = from; this.to = to; this.isOpen = true; },
            close()        { this.isOpen = false; },
        });
    }

    if (!window.Alpine.store('autoModal')) {
        const _nowY = new Date().getFullYear();
        const _nowM = new Date().getMonth() + 1;
        const _yr   = [];
        for (let y = _nowY - 1; y <= _nowY + 2; y++) _yr.push(y);
        window.Alpine.store('autoModal', {
            isOpen:  false,
            block:   'A',
            month:   _nowM,
            year:    _nowY,
            status:  '',
            running: false,
            monthNames: ['January','February','March','April','May','June',
                         'July','August','September','October','November','December'],
            yearRange: _yr,
            get blockDateRange() {
                const p = n => String(n).padStart(2, '0');
                const m = this.month, y = this.year;
                if (this.block === 'A') {
                    return `${y}-${p(m)}-11 – ${y}-${p(m)}-25`;
                }
                const nm = m === 12 ? 1 : m + 1;
                const ny = m === 12 ? y + 1 : y;
                return `${y}-${p(m)}-26 – ${ny}-${p(nm)}-10`;
            },
            open()  { this.isOpen = true;  this.status = ''; this.running = false; },
            close() { this.isOpen = false; this.status = ''; this.running = false; },
        });
    }
});

function nurseSchedule(initialDate) {
    return {
        selectedDate: initialDate,
        currentMonth: new Date(initialDate + 'T00:00:00').getMonth(),
        currentYear:  new Date(initialDate + 'T00:00:00').getFullYear(),
        days:      [],
        yearRange: (() => { const b = new Date().getFullYear(), r = []; for (let y = b-3; y <= b+3; y++) r.push(y); return r; })(),
        monthNames: ['January','February','March','April','May','June',
                     'July','August','September','October','November','December'],
        dayNames:   ['SUN','MON','TUE','WED','THU','FRI','SAT'],

        init() {
            this.buildDays();

            window.addEventListener('auto-schedule-result', (e) => {
                const s = window.Alpine.store('autoModal');
                s.running = false;
                if (e.detail.success) { s.close(); } else { s.status = e.detail.message; }
            });

            window.addEventListener('preview-data-updated', (e) => {
                const preview = window.Alpine.store('nursePreview');
                if (preview.isOpen && preview.from && preview.to) {
                    this.renderPreviewTable(preview.from, preview.to, e.detail);
                }
            });
        },

        buildDays() {
            const m = Number(this.currentMonth), y = Number(this.currentYear);
            const daysInMonth = new Date(y, m + 1, 0).getDate();
            const today = new Date();
            const pad = n => String(n).padStart(2, '0');
            this.days = [];
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${y}-${pad(m + 1)}-${pad(d)}`;
                const dt = new Date(y, m, d);
                this.days.push({
                    date:    dateStr,
                    num:     d,
                    name:    this.dayNames[dt.getDay()],
                    isToday: today.getFullYear() === y && today.getMonth() === m && today.getDate() === d,
                });
            }
            this.$nextTick(() => this.scrollToSelected());
        },

        scrollToSelected() {
            const track = this.$refs.dpTrack;
            if (!track) return;
            const active = track.querySelector('.dp-active');
            if (active) active.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        },

        selectDate(date) {
            this.selectedDate = date;
            this.$wire.call('changeDate', date);
            this.$nextTick(() => this.scrollToSelected());
        },

        goToday() {
            const now = new Date();
            this.currentMonth = now.getMonth();
            this.currentYear  = now.getFullYear();
            this.buildDays();
            const pad = n => String(n).padStart(2, '0');
            const date = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}`;
            this.selectedDate = date;
            this.$wire.call('changeDate', date);
        },

        prevMonth() {
            let m = Number(this.currentMonth), y = Number(this.currentYear);
            if (m === 0) { m = 11; y--; } else { m--; }
            this.currentMonth = m; this.currentYear = y; this.buildDays();
        },

        nextMonth() {
            let m = Number(this.currentMonth), y = Number(this.currentYear);
            if (m === 11) { m = 0; y++; } else { m++; }
            this.currentMonth = m; this.currentYear = y; this.buildDays();
        },

        scrollDays(n) {
            const track = this.$refs.dpTrack;
            if (track) track.scrollBy({ left: n * 60, behavior: 'smooth' });
        },

        formattedSelectedDate() {
            if (!this.selectedDate) return '';
            const [y, m, d] = this.selectedDate.split('-');
            return new Date(+y, +m - 1, +d).toLocaleDateString('en-US', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        },

        openPreview() {
            const d = this.selectedDate ? new Date(this.selectedDate + 'T00:00:00') : new Date();
            const y = d.getFullYear(), m = d.getMonth();
            const pad = n => String(n).padStart(2, '0');
            window.Alpine.store('nursePreview').open(
                `${y}-${pad(m + 1)}-01`,
                `${y}-${pad(m + 1)}-${pad(new Date(y, m + 1, 0).getDate())}`
            );
            this.loadPreview();
        },

        loadPreview() {
            const preview = window.Alpine.store('nursePreview');
            if (preview.from && preview.to) {
                this.$wire.loadPreviewRange(preview.from, preview.to);
            }
        },

        renderPreviewTable(previewFrom, previewTo, previewData) {
            const container = document.getElementById('preview-table-container');
            if (!container) return;

            if (!previewData || Object.keys(previewData).length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <p class="text-sm font-semibold text-gray-400">No schedule data found for the selected range.</p>
                    </div>`;
                return;
            }

            const fromDt = new Date(previewFrom + 'T00:00:00');
            const toDt   = new Date(previewTo   + 'T00:00:00');
            const dates  = [];
            const cur = new Date(fromDt);
            while (cur <= toDt) { dates.push(new Date(cur)); cur.setDate(cur.getDate() + 1); }

            const dayNames = ['SUN','MON','TUE','WED','THU','FRI','SAT'];
            const sectionColors = {
                er:     { bg: '#dc2626', light: '#fff1f2' },
                triage: { bg: '#ea580c', light: '#fff7ed' },
                ward:   { bg: '#015581', light: '#e6f0f7' },
                opd:    { bg: '#7c3aed', light: '#f5f3ff' },
            };
            const sections = [
                { key: 'er',     label: 'EMERGENCY ROOM (ER)', shifts: ['am','pm','noc'] },
                { key: 'triage', label: 'TRIAGE',              shifts: ['am','pm','noc'] },
                { key: 'ward',   label: 'WARD',                shifts: ['am','pm','noc'] },
                { key: 'opd',    label: 'OPD',                 shifts: ['day'] },
            ];

            const colCount = dates.length + 1;
            const dateStrings = dates.map(d => {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${day}`;
            });

            let html = `<div class="text-center pt-4 pb-2">
                <p class="text-sm font-extrabold tracking-widest uppercase" style="color:#015581;">NORTHERN LUZON ADVENTIST HOSPITAL — NURSES SCHEDULE</p>
                <p class="text-xs font-semibold text-gray-500 mt-0.5">${fromDt.toLocaleDateString('en-US',{month:'long',day:'numeric'})} – ${toDt.toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'})}</p>
            </div>
            <div class="px-3 pb-4 overflow-x-auto">
            <table class="xl-table" style="min-width:600px;">
                <thead><tr>
                    <th style="min-width:90px;position:sticky;left:0;z-index:2;background:#d1fae5;white-space:nowrap;">Shift</th>`;

            dates.forEach(dt => {
                const dow = dt.getDay();
                const isWknd = dow === 0 || dow === 6;
                html += `<th style="min-width:70px;text-align:center;white-space:nowrap;${isWknd?'background:#f9fafb;color:#9ca3af;':''}" >
                    <span style="font-size:.65rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;">${dayNames[dow]}</span><br>
                    <span style="font-weight:900;font-size:.85rem;">${dt.getDate()}</span>
                </th>`;
            });
            html += '</tr></thead><tbody>';

            sections.forEach((sec, si) => {
                const color = sectionColors[sec.key] || { bg: '#015581', light: '#e6f0f7' };
                html += `<tr><td colspan="${colCount}" style="background:${color.bg};color:#fff;font-weight:800;letter-spacing:.06em;text-transform:uppercase;text-align:center;font-size:.7rem;padding:4px 12px;">${sec.label}</td></tr>`;

                sec.shifts.forEach(shift => {
                    const shiftLabel = shift === 'am' ? 'AM' : shift === 'pm' ? 'PM' : shift === 'noc' ? 'NOC' : '8–5';
                    html += `<tr><td class="xl-shift-label" style="position:sticky;left:0;z-index:1;white-space:nowrap;background:${color.light};color:${color.bg};">${shiftLabel}</td>`;

                    dateStrings.forEach((d, idx) => {
                        const dt = dates[idx];
                        const dow = dt.getDay();
                        const isWknd = dow === 0 || dow === 6;
                        let cellContent = '';

                        if (sec.key === 'opd' && isWknd) {
                            cellContent = '<span style="font-size:.65rem;color:#9ca3af;font-style:italic;">Closed</span>';
                        } else {
                            const name = previewData[sec.key]?.[shift]?.[d] || '';
                            cellContent = `<span style="font-size:.75rem;">${name}</span>`;
                        }

                        html += `<td style="text-align:center;word-break:break-word;min-width:70px;${isWknd?'background:#f9fafb;':''}">${cellContent}</td>`;
                    });
                    html += '</tr>';
                });

                if (si < sections.length - 1) {
                    html += `<tr><td colspan="${colCount}" style="padding:2px;background:#f9fafb;border:none;"></td></tr>`;
                }
            });

            html += '</tbody></table></div>';
            container.innerHTML = html;
        },

        // Delegated click handler for dynamically rendered schedule buttons
        handleScheduleClick(event) {
            const removeBtn = event.target.closest('.np-remove[data-remove-id]');
            if (removeBtn) {
                this.$wire.removeEntry(parseInt(removeBtn.dataset.removeId));
                return;
            }
            const addBtn = event.target.closest('.add-nurse-btn[data-section]');
            if (addBtn) {
                window.Alpine.store('nurseModal').openFor(
                    addBtn.dataset.section,
                    addBtn.dataset.period
                );
            }
        },
    };
}
</script>
