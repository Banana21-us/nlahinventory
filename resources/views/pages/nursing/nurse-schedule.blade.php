<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary"
     x-data="nurseSchedule(@js($selectedDate))"
     x-init="init()">
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
    .shift-grid { display:grid;grid-template-columns:80px 1fr 1fr; }
    .shift-label { font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding:10px 14px;border-right:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;display:flex;align-items:center; }
    .shift-cell  { padding:8px 12px;border-bottom:1px solid #f1f5f9;border-right:1px solid #f1f5f9;min-height:54px; }
    .shift-cell:last-child { border-right:none; }
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
    .xl-table { border-collapse:collapse;width:100%;font-size:.75rem;font-family:'Courier New',monospace; }
    .xl-table th,.xl-table td { border:1px solid #9ca3af;padding:4px 8px;text-align:left;white-space:nowrap; }
    .xl-table th { background:#d1fae5;font-weight:700;text-align:center; }
    .xl-section-row td { background:#015581;color:#fff;font-weight:800;letter-spacing:.06em;text-transform:uppercase;text-align:center; }
    .xl-shift-label { background:#e6f0f7;font-weight:700;color:#015581; }
    .xl-period-header { background:#f0f9ff;font-weight:700;color:#027c8b;text-align:center; }
    .xl-table tbody tr:hover td { background:#fef9ee; }

    @keyframes shrink { from { width:100% } to { width:0% } }
    @keyframes fadeIn { from { opacity:0;transform:translateY(6px) } to { opacity:1;transform:translateY(0) } }
    .fade-in { animation:fadeIn .2s ease forwards; }

    [x-cloak] { display:none !important; }

    .dp-day.dp-active.dp-today {
        background-color: #015581 !important; /* Green theme */
        border-color: #f0b626 !important;     /* Orange outline */
        border-width: 2px;
    }
    
    /* Ensure text stays white on the green background */
    .dp-day.dp-active.dp-today .dp-num,
    .dp-day.dp-active.dp-today .dp-dayname {
        color: #fff !important;
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
        {{-- Preview (Excel) --}}
        <button @click="previewOpen = true"
            class="group relative text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2 border border-green-600 text-green-700 bg-green-50 transition-all duration-200 hover:bg-green-100 hover:shadow-md active:scale-95">


            <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            
            <span>Preview (Excel)</span>

        </button>

        {{-- Print --}}
        <button onclick="window.print()"
            class="brand-btn-teal text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Schedule
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     DATE PICKER CARD
═══════════════════════════════════════════ --}}
<div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden mb-6">

    {{-- Row 1: Month / Year --}}
    <div class="px-5 pt-4 pb-3 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-2">
            <button @click="prevMonth()"
                class="dp-monyear-btn brand-bg-primary-light brand-text-primary hover:bg-blue-100 flex items-center justify-center w-8 h-8 p-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            {{-- Month -- x-model.number ensures numeric binding --}}
            <select x-model.number="currentMonth" @change="buildDays()"
                class="brand-focus border border-gray-200 rounded-lg px-3 py-1.5 text-sm font-bold text-gray-700 bg-white cursor-pointer">
                <template x-for="(m, i) in monthNames" :key="i">
                    <option :value="i" x-text="m"></option>
                </template>
            </select>

            {{-- Year -- rendered only after yearRange is populated --}}
            <select x-model.number="currentYear" @change="buildDays()"
                class="brand-focus border border-gray-200 rounded-lg px-3 py-1.5 text-sm font-bold text-gray-700 bg-white cursor-pointer">
                <template x-for="y in yearRange" :key="y">
                    <option :value="y" x-text="y"></option>
                </template>
            </select>

            <button @click="nextMonth()"
                class="dp-monyear-btn brand-bg-primary-light brand-text-primary hover:bg-blue-100 flex items-center justify-center w-8 h-8 p-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Legend -- inline styles prevent Tailwind purge from stripping border-yellow-400 --}}
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
                
                <!-- Subtle shine effect on hover -->
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></span>
                
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Today
            </button>
        </div>
    </div>

        {{-- Row 2: Day scroller --}}
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
                        :class="{ 
                            'dp-active': selectedDate === day.date, 
                            'dp-today': day.isToday 
                        }"
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
        </div>
        <div class="shift-grid">
            <div class="shift-label bg-gray-50" style="border-bottom:1px solid #e2e8f0;">Shift</div>
            <div class="shift-cell-header text-center border-r border-gray-200">AM</div>
            <div class="shift-cell-header text-center">PM</div>
            @foreach(['1st','2nd','3rd','4th','5th'] as $slot)
                <div class="shift-label">{{ $slot }}</div>
                @foreach(['am','pm'] as $period)
                    <div class="shift-cell flex flex-wrap items-start content-start gap-1 pt-2">
                        @foreach($schedule['ward'][$slot][$period] ?? [] as $entry)
                            <span class="nurse-pill">
                                <span class="np-avatar">{{ strtoupper(substr($entry['name'],0,1)) }}</span>
                                <span>{{ $entry['name'] }}</span>
                                <button class="np-remove" wire:click="removeEntry({{ $entry['id'] }})" title="Remove">✕</button>
                            </span>
                        @endforeach
                        @if(empty($schedule['ward'][$slot][$period] ?? []))
                            <button class="add-nurse-btn" @click="$store.nurseModal.openFor('ward', '{{ $slot }}', '{{ $period }}')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add
                            </button>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ── DR/OR ONCALL / RELIEVER / AMBULANCE ── --}}
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
        <div class="section-header brand-bg-teal-light">
            <div class="p-1.5 rounded brand-bg-teal">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="section-title brand-text-teal">DR/OR Oncall / Reliever / Ambulance Nurse</span>
        </div>
        <div class="shift-grid">
            <div class="shift-label bg-gray-50" style="border-bottom:1px solid #e2e8f0;">Shift</div>
            <div class="shift-cell-header text-center border-r border-gray-200">AM</div>
            <div class="shift-cell-header text-center">PM</div>
            @foreach(['1st','2nd','3rd','4th','5th','OPD'] as $slot)
                <div class="shift-label">{{ $slot }}</div>
                @foreach(['am','pm'] as $period)
                    <div class="shift-cell flex flex-wrap items-start content-start gap-1 pt-2">
                        @foreach($schedule['or'][$slot][$period] ?? [] as $entry)
                            <span class="nurse-pill" style="background:#e6f4f5;color:#027c8b;border-color:#a7d9dd;">
                                <span class="np-avatar" style="background:#027c8b;">{{ strtoupper(substr($entry['name'],0,1)) }}</span>
                                <span>{{ $entry['name'] }}</span>
                                <button class="np-remove" style="background:#a7d9dd;color:#027c8b;"
                                    wire:click="removeEntry({{ $entry['id'] }})" title="Remove">✕</button>
                            </span>
                        @endforeach
                        @if(empty($schedule['or'][$slot][$period] ?? []))
                            <button class="add-nurse-btn" style="border-color:#6ee7b7;color:#027c8b;"
                                @click="$store.nurseModal.openFor('or', '{{ $slot }}', '{{ $period }}')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add
                            </button>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ── HEAD NURSE ── --}}
    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
        <div class="section-header brand-bg-accent-light">
            <div class="p-1.5 rounded brand-bg-accent">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <span class="section-title brand-text-accent">Head Nurse</span>
        </div>
        <div class="shift-grid">
            <div class="shift-label bg-gray-50" style="border-bottom:1px solid #e2e8f0;">Shift</div>
            <div class="shift-cell-header text-center border-r border-gray-200">AM</div>
            <div class="shift-cell-header text-center">PM</div>
            @foreach(['8-3','3-11','IPCN'] as $slot)
                <div class="shift-label">{{ $slot }}</div>
                @foreach(['am','pm'] as $period)
                    <div class="shift-cell flex flex-wrap items-start content-start gap-1 pt-2">
                        @foreach($schedule['hn'][$slot][$period] ?? [] as $entry)
                            <span class="nurse-pill" style="background:#fef8e7;color:#b45309;border-color:#fde68a;">
                                <span class="np-avatar" style="background:#f0b626;color:#fff;">{{ strtoupper(substr($entry['name'],0,1)) }}</span>
                                <span>{{ $entry['name'] }}</span>
                                <button class="np-remove" style="background:#fde68a;color:#b45309;"
                                    wire:click="removeEntry({{ $entry['id'] }})" title="Remove">✕</button>
                            </span>
                        @endforeach
                        @if(empty($schedule['hn'][$slot][$period] ?? []))
                            <button class="add-nurse-btn" style="border-color:#fcd34d;color:#b45309;"
                                @click="$store.nurseModal.openFor('hn', '{{ $slot }}', '{{ $period }}')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add
                            </button>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

</div>{{-- /schedule sections --}}


{{-- ═══════════════════════════════════════════
     ASSIGN NURSE MODAL — state lives in Alpine.store('nurseModal')
     so it survives every Livewire re-render (morphdom never touches
     Alpine stores). wire:ignore.self also guards the modal shell.
 ═══════════════════════════════════════════ --}}
<div wire:ignore.self
     x-show="$store.nurseModal.isOpen"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog" aria-modal="true"
     @keydown.escape.window="$store.nurseModal.close()">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity"
         @click="$store.nurseModal.close()"></div>

    <div class="flex min-h-full items-center justify-center p-4" @click.stop>
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl w-full max-w-md fade-in"
             style="border-top:4px solid #015581;">

            <div class="bg-white px-6 pt-6 pb-4">

                {{-- Header --}}
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
                                <span x-text="$store.nurseModal.section.toUpperCase()"></span> ·
                                <span x-text="$store.nurseModal.slot"></span> ·
                                <span x-text="$store.nurseModal.period.toUpperCase()"></span> ·
                                <span x-text="formattedSelectedDate()"></span>
                            </p>
                        </div>
                    </div>
                    <button @click="$store.nurseModal.close()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                    </button>
                </div>

                {{-- Search (pure client-side) --}}
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

                {{-- Nurse list — filtered client-side; no Livewire calls on search --}}
                <div class="max-h-56 overflow-y-auto rounded-lg border border-gray-100">
                    <template x-for="nurse in $store.nurseModal.filteredNurses" :key="nurse.id">
                        <div class="nurse-option"
                             @click="$wire.assignEmployee(nurse.id, $store.nurseModal.section, $store.nurseModal.slot, $store.nurseModal.period); $store.nurseModal.close()">
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

                {{-- Manual entry --}}
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Or type a name manually</label>
                    <div class="flex gap-2">
                        <input type="text"
                            x-model="$store.nurseModal.customName"
                            placeholder="Enter name…"
                            @keydown.enter="if($store.nurseModal.customName.trim()) { $wire.assignCustom($store.nurseModal.section, $store.nurseModal.slot, $store.nurseModal.period, $store.nurseModal.customName); $store.nurseModal.close() }"
                            class="brand-focus flex-1 border border-gray-300 rounded-md px-3 py-1.5 text-sm"/>
                        <button @click="if($store.nurseModal.customName.trim()) { $wire.assignCustom($store.nurseModal.section, $store.nurseModal.slot, $store.nurseModal.period, $store.nurseModal.customName); $store.nurseModal.close() }"
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
     EXCEL PREVIEW MODAL (pure Alpine — no Livewire re-render)
═══════════════════════════════════════════ --}}
<div x-show="previewOpen"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog" aria-modal="true"
     @keydown.escape.window="previewOpen = false">

    <div class="fixed inset-0 bg-gray-900/80" @click="previewOpen = false"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-4xl fade-in overflow-hidden"
             style="border-top:4px solid #166534;"
             @click.stop>

            {{-- Preview header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-green-100">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Schedule Preview</h3>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="'Excel-style · ' + formattedSelectedDate()"></p>
                    </div>
                </div>
                <button @click="previewOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-auto max-h-[68vh] p-5 bg-white">

                {{-- Excel title cells --}}
                <div class="mb-3 text-center">
                    <p class="text-base font-extrabold tracking-widest uppercase" style="color:#015581;">NURSES SCHEDULE</p>
                    <p class="text-xs font-semibold text-gray-500 mt-0.5" x-text="formattedSelectedDate()"></p>
                </div>

                <table class="xl-table">
                    <colgroup>
                        <col style="width:90px;">
                        <col style="width:55px;">
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th colspan="2">Shift</th>
                            <th>AM</th>
                            <th>PM</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- WARD --}}
                        <tr class="xl-section-row"><td colspan="4">WARD</td></tr>
                        @foreach(['1st','2nd','3rd','4th','5th'] as $slot)
                        <tr>
                            <td class="xl-shift-label">{{ $slot }}</td>
                            <td class="xl-period-header">—</td>
                            <td>{{ collect($schedule['ward'][$slot]['am'] ?? [])->pluck('name')->join(', ') ?: '—' }}</td>
                            <td>{{ collect($schedule['ward'][$slot]['pm'] ?? [])->pluck('name')->join(', ') ?: '—' }}</td>
                        </tr>
                        @endforeach

                        <tr><td colspan="4" style="background:#f9fafb;border-left:none;border-right:none;padding:3px;"></td></tr>

                        {{-- DR/OR --}}
                        <tr class="xl-section-row"><td colspan="4">DR/OR ONCALL / RELIEVER / AMBULANCE NURSE</td></tr>
                        @foreach(['1st','2nd','3rd','4th','5th','OPD'] as $slot)
                        <tr>
                            <td class="xl-shift-label">{{ $slot }}</td>
                            <td class="xl-period-header">—</td>
                            <td>{{ collect($schedule['or'][$slot]['am'] ?? [])->pluck('name')->join(', ') ?: '—' }}</td>
                            <td>{{ collect($schedule['or'][$slot]['pm'] ?? [])->pluck('name')->join(', ') ?: '—' }}</td>
                        </tr>
                        @endforeach

                        <tr><td colspan="4" style="background:#f9fafb;border-left:none;border-right:none;padding:3px;"></td></tr>

                        {{-- HEAD NURSE --}}
                        <tr class="xl-section-row"><td colspan="4">HEAD NURSE</td></tr>
                        @foreach(['8-3','3-11','IPCN'] as $slot)
                        <tr>
                            <td class="xl-shift-label">{{ $slot }}</td>
                            <td class="xl-period-header">—</td>
                            <td>{{ collect($schedule['hn'][$slot]['am'] ?? [])->pluck('name')->join(', ') ?: '—' }}</td>
                            <td>{{ collect($schedule['hn'][$slot]['pm'] ?? [])->pluck('name')->join(', ') ?: '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="window.print()"
                    class="brand-btn-teal text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
                <button @click="previewOpen = false"
                    class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════
     TOAST (controlled by Alpine global state)
═══════════════════════════════════════════ --}}
<div x-data="{ show: false, message: '' }"
     x-show="show"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-init="
        window.addEventListener('show-toast', (e) => {
            show = true;
            message = e.detail.message;
            setTimeout(() => { show = false; }, 2000);
        });
     "
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


{{-- ═══════════════════════════════════════════
     ALPINE.JS
═══════════════════════════════════════════ --}}
<script>
/*
 * Alpine store — holds assign-modal state globally so it survives every
 * Livewire re-render (morphdom never touches Alpine.store). This is the
 * key to "add another nurse without refreshing the page".
 */
document.addEventListener('alpine:init', () => {
    if (window.Alpine && window.Alpine.store('nurseModal')) return;
    window.Alpine.store('nurseModal', {
        isOpen: false,
        section: '',
        slot: '',
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

        openFor(section, slot, period) {
            this.section = section;
            this.slot = slot;
            this.period = period;
            this.search = '';
            this.customName = '';
            this.isOpen = true;
        },

        close() {
            this.isOpen = false;
            this.search = '';
            this.customName = '';
        },
    });
});

function nurseSchedule(initialDate) {
    return {
        /* ── Date picker ── */
        selectedDate: initialDate,
        currentMonth: new Date(initialDate + 'T00:00:00').getMonth(),
        currentYear:  new Date(initialDate + 'T00:00:00').getFullYear(),
        days:      [],
        yearRange: [],
        monthNames: ['January','February','March','April','May','June',
                     'July','August','September','October','November','December'],
        dayNames:   ['SUN','MON','TUE','WED','THU','FRI','SAT'],

        /* ── Preview modal ── */
        previewOpen: false,

        init() {
            const now = new Date();
            const base = now.getFullYear();
            for (let y = base - 3; y <= base + 3; y++) this.yearRange.push(y);
            this.buildDays();
        },

        buildDays() {
            const m = Number(this.currentMonth);
            const y = Number(this.currentYear);
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
                    isToday: today.getFullYear() === y &&
                             today.getMonth()    === m &&
                             today.getDate()     === d,
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
            let m = Number(this.currentMonth);
            let y = Number(this.currentYear);
            if (m === 0) { m = 11; y--; } else { m--; }
            this.currentMonth = m;
            this.currentYear  = y;
            this.buildDays();
        },

        nextMonth() {
            let m = Number(this.currentMonth);
            let y = Number(this.currentYear);
            if (m === 11) { m = 0; y++; } else { m++; }
            this.currentMonth = m;
            this.currentYear  = y;
            this.buildDays();
        },

        scrollDays(n) {
            const track = this.$refs.dpTrack;
            if (track) track.scrollBy({ left: n * 60, behavior: 'smooth' });
        },

        formattedSelectedDate() {
            if (!this.selectedDate) return '';
            const [y, m, d] = this.selectedDate.split('-');
            const dt = new Date(+y, +m - 1, +d);
            return dt.toLocaleDateString('en-US', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        },
    };
}
</script>

</div>
