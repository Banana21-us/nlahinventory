<div class="max-w-5xl mx-auto py-8 px-4">
<style>
    .m-bg-primary       { background-color: #1e3a5f; }
    .m-bg-primary-light { background-color: #e8eef5; }
    .m-text-primary     { color: #1e3a5f; }
    .m-bg-green         { background-color: #16a34a; }
    .m-bg-green-light   { background-color: #dcfce7; }
    .m-text-green       { color: #16a34a; }
    .m-bg-amber         { background-color: #d97706; }
    .m-bg-amber-light   { background-color: #fef3c7; }
    .m-text-amber       { color: #d97706; }
    .m-bg-red           { background-color: #dc2626; }
    .m-bg-red-light     { background-color: #fee2e2; }
    .m-text-red         { color: #dc2626; }
    .m-card             { background: #fff; border-radius: 0.75rem; box-shadow: 0 1px 4px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; }
</style>

    {{-- ═══════ HEADER ═══════ --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="p-3 rounded-xl m-bg-primary-light">
            <svg class="w-7 h-7 m-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Maintenance</p>
            <h1 class="text-2xl font-bold text-gray-800 leading-tight">Team Dashboard</h1>
        </div>
    </div>

    {{-- ═══════ PERIOD CARDS ═══════ --}}
    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Team Progress — tap a card to see details</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        @php
        $periods = [
            ['key' => 'daily',   'label' => 'Daily',   'sub' => 'Today',      'done' => $dailyDone,   'total' => $dailyTotal],
            ['key' => 'nightly', 'label' => 'Nightly', 'sub' => 'Tonight',    'done' => $nightlyDone, 'total' => $nightlyTotal],
            ['key' => 'weekly',  'label' => 'Weekly',  'sub' => 'This Week',  'done' => $weeklyDone,  'total' => $weeklyTotal],
            ['key' => 'monthly', 'label' => 'Monthly', 'sub' => 'This Month', 'done' => $monthlyDone, 'total' => $monthlyTotal],
        ];
        @endphp

        @foreach($periods as $p)
        @php
            $pct   = $p['total'] > 0 ? round(($p['done'] / $p['total']) * 100) : 0;
            $all   = $p['total'] > 0 && $p['done'] >= $p['total'];
            $some  = $p['done'] > 0 && ! $all;
            $none  = $p['done'] === 0 && $p['total'] > 0;
            $empty = $p['total'] === 0;
            $barColor  = $all ? 'm-bg-green' : ($some ? 'm-bg-amber' : 'm-bg-red');
            $iconBg    = $all ? 'm-bg-green-light' : ($some ? 'm-bg-amber-light' : 'm-bg-primary-light');
            $iconColor = $all ? 'm-text-green'     : ($some ? 'm-text-amber'     : 'm-text-primary');
            $numColor  = $all ? 'm-text-green'     : ($some ? 'm-text-amber'     : 'text-gray-900');
        @endphp
        <button type="button"
            wire:click="openModal('{{ $p['key'] }}')"
            class="m-card p-5 text-left w-full hover:bg-gray-50 active:scale-95 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">{{ $p['label'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $p['sub'] }}</p>
                </div>
                <div class="p-2 rounded-lg {{ $iconBg }}">
                    <svg class="w-4 h-4 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            @if($empty)
                <p class="text-2xl font-bold text-gray-300">—</p>
                <p class="text-xs text-gray-400 mt-1">No tasks set up</p>
            @else
                <p class="text-3xl font-bold {{ $numColor }}">
                    {{ $p['done'] }}<span class="text-lg text-gray-400 font-normal">/{{ $p['total'] }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">{{ $pct }}% complete</p>
                <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                    <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            @endif
        </button>
        @endforeach

    </div>

    {{-- ═══════ QUICK ACTIONS ═══════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('Maintenance.checklist.check') }}" wire:navigate
           class="m-card p-4 sm:p-5 flex items-center gap-3 sm:gap-4 hover:bg-gray-50 transition-colors group">
            <div class="p-2.5 sm:p-3 rounded-xl m-bg-primary shrink-0 group-hover:opacity-90 transition">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm sm:text-base">Open Checklist</p>
                <p class="text-xs text-gray-500">Log today's cleaning tasks</p>
            </div>
        </a>

        <a href="{{ route('users.leaveform') }}" wire:navigate
           class="m-card p-4 sm:p-5 flex items-center gap-3 sm:gap-4 hover:bg-gray-50 transition-colors group">
            <div class="p-2.5 sm:p-3 rounded-xl m-bg-green shrink-0 group-hover:opacity-90 transition">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm sm:text-base">File a Leave</p>
                <p class="text-xs text-gray-500">Submit leave application</p>
            </div>
        </a>
    </div>

    {{-- ═══════ TEAM RECENT ACTIVITY ═══════ --}}
    <div class="m-card overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="p-2 rounded-lg m-bg-primary-light">
                <svg class="w-4 h-4 m-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-800">Recent Team Activity</h3>
        </div>

        @forelse($recentRecords as $rec)
            <div class="px-5 py-4 border-b border-gray-50 flex items-start gap-4 last:border-0">
                <div class="mt-1 shrink-0">
                    @if($rec->verifier_status === 'YES')
                        <span class="w-2.5 h-2.5 rounded-full m-bg-green block"></span>
                    @elseif($rec->verifier_status === 'NO')
                        <span class="w-2.5 h-2.5 rounded-full m-bg-red block"></span>
                    @elseif($rec->status === 'YES')
                        <span class="w-2.5 h-2.5 rounded-full m-bg-amber block"></span>
                    @else
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-300 block"></span>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ $rec->part_name }}</p>
                    <p class="text-xs text-gray-500">{{ $rec->location_name }} · {{ $rec->location_floor }}</p>
                    <p class="text-xs font-medium text-[#1e3a5f] mt-0.5">{{ $rec->maintenance_name }}</p>
                </div>

                <div class="text-right shrink-0">
                    <p class="text-xs font-semibold text-gray-700">{{ \Carbon\Carbon::parse($rec->cleaning_date)->format('M d') }}</p>
                    <div class="flex items-center gap-1 justify-end mt-1">
                        @if($rec->shift)
                            <span class="text-[10px] px-1.5 py-0.5 rounded font-bold"
                                  style="{{ $rec->shift === 'AM' ? 'background:#fef3c7;color:#92400e;' : 'background:#e0e7ff;color:#3730a3;' }}">
                                {{ $rec->shift }}
                            </span>
                        @endif
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500 font-medium capitalize">
                            {{ $rec->period_type }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-5 py-12 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm font-medium">No activity yet.</p>
                <p class="text-xs mt-1">Completed checklist items will appear here.</p>
            </div>
        @endforelse
    </div>

    {{-- ═══════ DETAIL MODAL ═══════ --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
         x-data x-init="document.body.style.overflow='hidden'"
         x-effect="if(!$wire.showModal) document.body.style.overflow=''">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             wire:click="closeModal"></div>

        {{-- Panel --}}
        <div class="relative bg-white w-full sm:max-w-2xl sm:rounded-2xl rounded-t-2xl shadow-2xl flex flex-col max-h-[90vh]">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Checklist Details</p>
                    <h2 class="text-lg font-bold text-gray-900 capitalize">{{ $modalPeriod }} Tasks</h2>
                </div>
                <button type="button" wire:click="closeModal"
                        class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Legend --}}
            <div class="px-5 py-2 border-b border-gray-50 flex items-center gap-4 text-[11px] text-gray-500 shrink-0">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full m-bg-green inline-block"></span>Verified OK</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full m-bg-amber inline-block"></span>Done, awaiting</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full m-bg-red inline-block"></span>Flagged / Not done</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span>Not yet</span>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto flex-1">
                @forelse($modalRows ?? [] as $row)
                @php
                    $done = ! is_null($row->maintenance_name);
                    $dot  = ! $done
                        ? 'bg-gray-300'
                        : ($row->verifier_status === 'YES' ? 'm-bg-green'
                            : ($row->verifier_status === 'NO' ? 'm-bg-red' : 'm-bg-amber'));
                @endphp
                <div class="px-5 py-4 border-b border-gray-50 flex items-start gap-3 last:border-0">
                    <span class="w-2.5 h-2.5 rounded-full {{ $dot }} mt-1.5 shrink-0 block"></span>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $row->part_name }}</p>
                        <p class="text-xs text-gray-400">{{ $row->location_name }}
                            @if($row->location_floor) · {{ $row->location_floor }} @endif
                        </p>
                    </div>

                    <div class="text-right shrink-0 space-y-1">
                        @if($done)
                            <p class="text-sm font-bold text-[#1e3a5f]">{{ $row->maintenance_name }}</p>
                            <div class="flex items-center gap-1 justify-end">
                                @if($row->shift)
                                    <span class="text-[10px] px-1.5 py-0.5 rounded font-bold"
                                          style="{{ $row->shift === 'AM' ? 'background:#fef3c7;color:#92400e;' : 'background:#e0e7ff;color:#3730a3;' }}">
                                        {{ $row->shift }}
                                    </span>
                                @endif
                                @if($row->verifier_status === 'YES')
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-green-100 text-green-700 font-bold">Verified</span>
                                @elseif($row->verifier_status === 'NO')
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-red-100 text-red-700 font-bold">Flagged</span>
                                @else
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 font-bold">Pending</span>
                                @endif
                            </div>
                        @else
                            <span class="text-xs text-gray-400 italic">Not cleaned yet</span>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="px-5 py-12 text-center text-gray-400">
                        <p class="text-sm font-medium">No tasks found for this period.</p>
                        <p class="text-xs mt-1">Add entries to location_area_parts with frequency="{{ $modalPeriod }}".</p>
                    </div>
                @endforelse
            </div>

            {{-- Footer --}}
            <div class="px-5 py-3 border-t border-gray-100 shrink-0">
                <button type="button" wire:click="closeModal"
                        class="w-full py-2.5 rounded-xl text-sm font-semibold text-white m-bg-primary hover:opacity-90 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
