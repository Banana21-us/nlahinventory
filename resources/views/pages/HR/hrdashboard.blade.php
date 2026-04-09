<div class="min-h-screen font-sans nlah-page-bg" style="background:#F4F6F9;">
<style>
    .brand-primary   { color:#015581; }
    .brand-bg-p      { background:#015581; }
    .brand-bg-p-lt   { background:#e6f0f7; }
    .brand-accent    { color:#f0b626; }
    .brand-bg-a      { background:#f0b626; }
    .brand-bg-a-lt   { background:#fef8e7; }
    .brand-teal      { color:#027c8b; }
    .brand-bg-t      { background:#027c8b; }
    .brand-bg-t-lt   { background:#e6f4f5; }
    .bar-track       { background:#e5e7eb; border-radius:99px; height:7px; overflow:hidden; }
    .bar-fill        { height:7px; border-radius:99px; transition:width .4s ease; }
    .kpi-card        { background:#fff; border-radius:14px; border:1px solid #e5e7eb; padding:22px 24px; display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .kpi-icon        { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .section-card    { background:#fff; border-radius:14px; border:1px solid #e5e7eb; overflow:hidden; }
    .section-head    { padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between; }
    .section-body    { padding:20px 24px; }
    .badge           { display:inline-flex; align-items:center; padding:2px 10px; border-radius:99px; font-size:11px; font-weight:700; }
    .row-hover:hover { background:#f8fafc; }
</style>

    {{-- ── TOP BAR ───────────────────────────────────────────────────────── --}}
    <div class="px-8 pt-8 pb-0 flex items-end justify-between">
        <div>
            <p class="text-[10px] font-black tracking-[0.35em] uppercase brand-accent mb-1">Human Resources</p>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-none">HR Dashboard</h1>
            <p class="text-sm text-gray-400 font-medium mt-1" id="hr-date"></p>
        </div>
        <!-- <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-gray-200">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-bold text-gray-500">Live</span>
            <span class="text-xs font-mono font-bold text-gray-700" id="hr-clock">--:--</span>
        </div> -->
    </div>

    {{-- ── BRAND RULE ─────────────────────────────────────────────────────── --}}
    <div class="mx-8 mt-4 mb-6 h-px" style="background:linear-gradient(to right,#015581,#027c8b,#f0b62620,transparent);"></div>

    <div class="px-8 pb-10 flex flex-col gap-6">

        {{-- ══════════════════════════════════════════════════════════════════
             ROW 1 — KPI CARDS
        ══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4">

            {{-- Total Employees --}}
            <div class="kpi-card">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Total Employees</p>
                <p class="text-3xl font-black text-gray-900">{{ $totalEmployees }}</p>
                <p class="text-[11px] text-green-600 font-semibold mt-1">+{{ $newHiresThisMonth }} this month</p>
            </div>
            <div class="kpi-icon brand-bg-p-lt">
                <svg class="w-5 h-5 brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            </div>

            {{-- On Leave Today --}}
            <div class="kpi-card">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">On Leave Today</p>
                <p class="text-3xl font-black text-gray-900">{{ $onLeaveToday }}</p>
                <p class="text-[11px] text-gray-400 font-semibold mt-1">
                {{ $totalEmployees > 0 ? round(($onLeaveToday / $totalEmployees) * 100, 1) : 0 }}% of workforce
                </p>
            </div>
            <div class="kpi-icon brand-bg-a-lt">
                <svg class="w-5 h-5 brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            </div>

            {{-- Pending HR Review --}}
            <div class="kpi-card" style="border-color:#fde047;">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-yellow-600 mb-1">Pending HR Review</p>
                <p class="text-3xl font-black text-gray-900">{{ $pendingHR }}</p>
                <p class="text-[11px] text-yellow-600 font-semibold mt-1">Awaiting action</p>
            </div>
            <div class="kpi-icon brand-bg-a-lt">
                <svg class="w-5 h-5 brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            </div>

            {{-- Approved This Month --}}
            <div class="kpi-card">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Approved This Month</p>
                <p class="text-3xl font-black text-gray-900">{{ $approvedThisMonth }}</p>
                <p class="text-[11px] text-green-600 font-semibold mt-1">Leave requests</p>
            </div>
            <div class="kpi-icon" style="background:#dcfce7;">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            </div>

            {{-- Workforce In --}}
            <div class="kpi-card">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Present Today</p>
                <p class="text-3xl font-black text-gray-900">{{ $totalEmployees - $onLeaveToday }}</p>
                <p class="text-[11px] brand-teal font-semibold mt-1">
                {{ $totalEmployees > 0 ? round((($totalEmployees - $onLeaveToday) / $totalEmployees) * 100, 1) : 100 }}% attendance
                </p>
            </div>
            <div class="kpi-icon brand-bg-t-lt">
                <svg class="w-5 h-5 brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             ROW 2 — RELIGION · EMPLOYMENT STATUS · ROLE BREAKDOWN
        ══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Religion Distribution --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Workforce Profile</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Religion Distribution</h3>
                    </div>
                    <span class="badge" style="background:#e6f0f7;color:#015581;">{{ $totalWithReligion }} recorded</span>
                </div>
                <div class="section-body space-y-3">
                    @forelse($religionRows as $row)
                    @php $pct = $totalWithReligion > 0 ? round(($row->total / $totalWithReligion) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-gray-700">{{ $row->religion }}</span>
                            <span class="text-xs font-bold text-gray-500">{{ $row->total }} <span class="text-gray-300 font-normal">({{ $pct }}%)</span></span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill brand-bg-p" style="width:{{ $pct }}%;"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 italic py-4 text-center">No religion data on record.</p>
                    @endforelse
                </div>
            </div>

            {{-- Employment Status --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Contract Types</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Employment Status</h3>
                    </div>
                    <span class="badge" style="background:#e6f4f5;color:#027c8b;">{{ $totalEmploymentDetails }} total</span>
                </div>
                <div class="section-body space-y-3">
                    @php
                        $statusColors = [
                            'Regular'       => '#015581',
                            'Probationary'  => '#f0b626',
                            'Contractual'   => '#027c8b',
                            'Casual'        => '#6b7280',
                        ];
                    @endphp
                    @forelse($employmentStatus as $row)
                    @php
                        $pct   = $totalEmploymentDetails > 0 ? round(($row->total / $totalEmploymentDetails) * 100) : 0;
                        $color = $statusColors[$row->employment_status] ?? '#6b7280';
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-gray-700">{{ $row->employment_status }}</span>
                            <span class="text-xs font-bold text-gray-500">{{ $row->total }} <span class="text-gray-300 font-normal">({{ $pct }}%)</span></span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 italic py-4 text-center">No employment details on record.</p>
                    @endforelse
                </div>
            </div>

            {{-- Role Breakdown --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">System Access</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Role Breakdown</h3>
                    </div>
                </div>
                <div class="section-body space-y-2">
                    @php
                        $roleColors = [
                            'Staff'       => ['bg' => '#e6f0f7', 'text' => '#015581'],
                            'HR'          => ['bg' => '#dcfce7', 'text' => '#166534'],
                            'Maintenance' => ['bg' => '#fef9c3', 'text' => '#854d0e'],
                            'Inspector'   => ['bg' => '#ede9fe', 'text' => '#6b21a8'],
                            'Cashier'     => ['bg' => '#e6f4f5', 'text' => '#027c8b'],
                            'Developer'   => ['bg' => '#f3f4f6', 'text' => '#374151'],
                            'Disable'     => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                        ];
                        $totalRoles = $roleBreakdown->sum('total');
                    @endphp
                    @foreach($roleBreakdown as $row)
                    @php
                        $c   = $roleColors[$row->role] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                        $pct = $totalRoles > 0 ? round(($row->total / $totalRoles) * 100) : 0;
                    @endphp
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-2">
                            <span class="badge" style="background:{{ $c['bg'] }};color:{{ $c['text'] }};">{{ $row->role }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-20 bar-track">
                                <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $c['text'] }};"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-700 w-5 text-right">{{ $row->total }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             ROW 3 — DEPARTMENT HEADCOUNT + LEAVE BY TYPE
        ══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Department Headcount --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Structure</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Department Headcount</h3>
                    </div>
                    <span class="badge" style="background:#e6f0f7;color:#015581;">{{ $departments->count() }} dept(s)</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#f9fafb;border-bottom:1px solid #f3f4f6;">
                                <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Department</th>
                                <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Code</th>
                                <th class="text-right px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Staff</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $dept)
                            <tr class="row-hover border-b border-gray-50 last:border-0">
                                <td class="px-6 py-3 font-semibold text-gray-800">{{ $dept->name }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="badge brand-bg-t-lt brand-teal">{{ $dept->code }}</span>
                                </td>
                                <td class="px-6 py-3 text-right font-black text-gray-900">{{ $dept->total }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-400 italic">No departments configured.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Leave by Type (this year) --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ now()->year }}</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Leave by Type</h3>
                    </div>
                    <span class="badge" style="background:#fef8e7;color:#854d0e;">Active &amp; Approved</span>
                </div>
                <div class="section-body space-y-3">
                    @php
                        $typeColors = [
                            'Vacation Leave'      => '#015581',
                            'Sick Leave'          => '#991b1b',
                            'Birthday Leave'      => '#9d174d',
                            'Maternity Leave'     => '#6b21a8',
                            'Paternity Leave'     => '#027c8b',
                            'Compassionate Leave' => '#92400e',
                            'Leave Without Pay'   => '#374151',
                            'Pay-Off'             => '#854d0e',
                            'Single Parent Leave' => '#166534',
                        ];
                    @endphp
                    @forelse($leaveByType as $row)
                    @php
                        $pct   = round(($row->total / $totalLeavesByType) * 100);
                        $color = $typeColors[$row->leave_type] ?? '#6b7280';
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-gray-700">{{ $row->leave_type }}</span>
                            <span class="text-xs font-bold text-gray-500">{{ $row->total }} <span class="text-gray-300 font-normal">({{ $pct }}%)</span></span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 italic py-4 text-center">No leave records for {{ now()->year }}.</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             ROW 4 — PENDING ACTIONS + RECENT ACTIVITY + UPCOMING
        ══════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Pending HR Actions (dept head already approved) --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-yellow-500">Action Required</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Ready for HR Review</h3>
                    </div>
                    @if($pendingLeaves->count())
                    <span class="badge" style="background:#fef9c3;color:#854d0e;animation:pulse 2s infinite;">{{ $pendingLeaves->count() }}</span>
                    @endif
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($pendingLeaves as $leave)
                    <div class="px-5 py-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full brand-bg-p flex items-center justify-center text-white text-xs font-black shrink-0">
                                {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $leave->user->name }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ $leave->user->employmentDetail?->department?->name ?? '—' }} · {{ $leave->leave_type }}</p>
                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    {{ $leave->start_date->format('M d') }} – {{ $leave->end_date->format('M d, Y') }} &nbsp;·&nbsp; {{ $leave->total_days }}d
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 flex justify-end">
                            <a href="{{ route('HR.hr-leave-management') }}"
                               class="text-[11px] font-bold brand-primary hover:underline">
                                Review →
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-sm font-medium text-green-600">All clear — no pending reviews.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Leave Activity --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Latest</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Recent Leave Activity</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#f9fafb;border-bottom:1px solid #f3f4f6;">
                                <th class="text-left px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400">Employee</th>
                                <th class="text-left px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400">Type</th>
                                <th class="text-center px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400">HR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
                            @php
                                $hrStyle = match($leave->hr_status) {
                                    'approved' => 'background:#dcfce7;color:#166534;',
                                    'rejected' => 'background:#fee2e2;color:#991b1b;',
                                    default    => 'background:#fef9c3;color:#854d0e;',
                                };
                            @endphp
                            <tr class="row-hover border-b border-gray-50 last:border-0">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-gray-800 truncate max-w-[110px]">{{ $leave->user?->name ?? '(no user)' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $leave->start_date->format('M d, Y') }}</p>
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-600">{{ Str::limit($leave->leave_type, 14) }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="badge" style="{{ $hrStyle }}">{{ ucfirst($leave->hr_status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-gray-400 italic">No leave records yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Upcoming Approved Leaves --}}
            <div class="section-card">
                <div class="section-head">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Next 7 Days</p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">Upcoming Leaves</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($upcomingLeaves as $leave)
                    <div class="px-5 py-4 flex items-center gap-3">
                        <div class="shrink-0 w-10 text-center">
                            <p class="text-lg font-black brand-primary leading-none">{{ $leave->start_date->format('d') }}</p>
                            <p class="text-[10px] font-bold uppercase text-gray-400">{{ $leave->start_date->format('M') }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $leave->user->name }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ $leave->leave_type }} · {{ $leave->total_days }}d</p>
                            <p class="text-[11px] text-gray-400">{{ $leave->user->employmentDetail?->department?->name ?? '—' }}</p>
                        </div>
                        <span class="badge shrink-0" style="background:#dcfce7;color:#166534;">Approved</span>
                    </div>
                    @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-sm text-gray-400 italic">No upcoming approved leaves.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>

<script>
(function tick() {
    const now = new Date();
    const cl  = document.getElementById('hr-clock');
    const dt  = document.getElementById('hr-date');
    if (cl) cl.textContent = now.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' });
    if (dt) dt.textContent = now.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    setTimeout(tick, 1000);
})();
</script>
