<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cleaning Checklist — {{ $location->name }}</title>
    <style>
        @page { size: A4 landscape; margin: 10mm 12mm; } /* Adjusted left/right margin slightly to accommodate the new border */

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #222e35;
            background: #fff;
        }

        /* ── Page Border Container ───────────────── */
        .page-border {
            border: 1px solid #097b86; /* Added border here */
            padding: 16px 24px; /* Original top/bottom page margin, moved inside the border */
            min-height: 188mm; /* Ensures border extends to bottom of A4 */
        }

        /* ── Header ───────────────────────────────── */
        .hdr {
            display: table;
            width: 100%;
            border-bottom: 1.5px solid #097b86;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .hdr-l { display: table-cell; vertical-align: bottom; width: 62%; }
        .hdr-r { display: table-cell; vertical-align: bottom; text-align: right; width: 38%; }

        .hosp     { font-size: 11.5px; font-weight: bold; color: #097b86; letter-spacing: 0.5px; }
        .hosp-sub { font-size: 6.5px; color: #7a9da2; letter-spacing: 0.3px; margin-top: 1px; }
        .doc-title { font-size: 9.5px; font-weight: bold; color: #222e35; margin-top: 5px; }
        .doc-sub   { font-size: 7px; color: #7a9da2; margin-top: 2px; }

        .meta { border-collapse: collapse; font-size: 7px; margin-left: auto; }
        .meta td { padding: 2px 5px; }
        .meta td:first-child {
            color: #097b86; font-weight: bold; text-align: right;
            border-right: 1px solid #c5dfe2;
        }
        .meta tr + tr td { border-top: 0.5px solid #eaf3f4; }

        /* ── Checklist Table ──────────────────────── */
        .ct {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 9px;
        }
        .ct th, .ct td {
            border: 0.5px solid #dde8ea;
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }
        .ct thead .row-top th {
            background: #f0f9fa;
            color: #097b86;
            font-size: 7.5px;
            font-weight: bold;
            letter-spacing: 0.2px;
            padding: 4px 3px;
            border-bottom: 1px solid #097b86;
        }
        .ct thead .row-sub th {
            background: #fafcfc;
            color: #5e7e84;
            font-size: 6.5px;
            font-weight: bold;
            padding: 2px;
            border-bottom: 0.5px solid #dde8ea;
        }
        .col-item {
            text-align: left !important;
            padding: 4px 7px !important;
            font-size: 7.5px;
            color: #222e35;
        }
        .cell-done  { background: #f4fbfc; }
        .cell-empty { background: #fdfdfd; }
        .mark { color: #097b86; font-size: 9px; font-weight: bold; display: block; line-height: 1.2; }
        .ini  { color: #6a8f95; font-size: 5.5px; display: block; line-height: 1.1; }
        .ct tbody tr:nth-child(even) .col-item { background: #f8fdfd; }

        /* ── Signature boxes ──────────────────────── */
        .bottom {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .sig { display: table-cell; width: 50%; vertical-align: top; padding-right: 10px; }
        .sig:last-child { padding-right: 0; padding-left: 10px; }

        .field-label {
            font-size: 6px; font-weight: bold; text-transform: uppercase;
            color: #097b86; letter-spacing: 0.6px; margin-bottom: 2px;
        }
        .field-box {
            border-top: 1.5px solid #097b86;
            border-bottom: 0.5px solid #dde8ea;
            border-left: 0.5px solid #dde8ea;
            border-right: 0.5px solid #dde8ea;
            padding: 5px 7px;
            min-height: 18px;
            font-size: 7.5px;
            background: #fafcfc;
            color: #222e35;
        }

        /* ── Comments ─────────────────────────────── */
        .comments-wrap { margin-bottom: 7px; }
        .comments-label {
            font-size: 6px; font-weight: bold; text-transform: uppercase;
            color: #097b86; letter-spacing: 0.6px; margin-bottom: 2px;
        }
        .comments-box {
            border-top: 1.5px solid #097b86;
            border-bottom: 0.5px solid #dde8ea;
            border-left: 0.5px solid #dde8ea;
            border-right: 0.5px solid #dde8ea;
            padding: 5px 8px;
            min-height: 22px;
            background: #fafcfc;
        }
        .c-entry { margin-bottom: 5px; padding-bottom: 4px; border-bottom: 0.5px solid #eaf3f4; }
        .c-entry:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        .c-header { display: table; width: 100%; margin-bottom: 1px; }
        .c-left  { display: table-cell; vertical-align: middle; }
        .c-right { display: table-cell; vertical-align: middle; text-align: right; }
        .c-freq {
            display: inline-block;
            font-size: 5.5px; font-weight: bold; text-transform: uppercase;
            color: #097b86; background: #e8f7f8;
            border: 0.5px solid #b2dde2;
            border-radius: 2px;
            padding: 0 3px; line-height: 10px;
            margin-right: 4px;
            letter-spacing: 0.3px;
        }
        .c-area { font-size: 7.5px; font-weight: bold; color: #222e35; }
        .c-meta { font-size: 6px; color: #8aacaf; }
        .c-text { font-size: 7px; color: #3a4e54; line-height: 1.5; padding-left: 1px; }
        .c-empty { font-size: 7px; color: #b0c4c8; font-style: italic; }

        /* ── Footer ───────────────────────────────── */
        .footer {
            border-top: 0.5px solid #dde8ea;
            padding-top: 3px;
            display: table;
            width: 100%;
        }
        .fl { display: table-cell; font-size: 6px; color: #9ab8bb; }
        .fr { display: table-cell; text-align: right; font-size: 6px; color: #9ab8bb; }
    </style>
</head>
<body>

<div class="page-border">
{{-- ── HEADER ───────────────────────────────────── --}}
<div class="hdr">
    <div class="hdr-l">
        <div class="hosp">Northern Luzon Adventist Hospital</div>
        <div class="hosp-sub">Housekeeping &amp; Sanitation · Quality Management System</div>
        <div class="doc-title">
            {{ ['daily'=>'Daily','nightly'=>'Nightly','weekly'=>'Weekly','monthly'=>'Monthly'][$periodType] ?? ucfirst($periodType) }}
            Cleaning Checklist
            — {{ strtoupper($location->name) }}{{ $location->floor ? ' ('.$location->floor.')' : '' }}
        </div>
        <div class="doc-sub">{{ $periodLabel }}</div>
    </div>
    <div class="hdr-r">
        <table class="meta">
            <tr><td>Period</td><td>{{ $periodLabel }}</td></tr>
            <tr><td>Generated</td><td>{{ $generatedAt }}</td></tr>
            <tr><td>Form No.</td><td>NLAH-HK-001 · Rev.01</td></tr>
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- DAILY / NIGHTLY TABLE --}}
{{-- ══════════════════════════════════════════════ --}}
@if (in_array($periodType, ['daily', 'nightly']))
<table class="ct">
    <thead>
        <tr class="row-top">
            <th rowspan="2" style="width:22%; text-align:left; padding-left:7px;">Area / Item</th>
            @foreach ($days as $day)
                <th colspan="{{ $periodType === 'nightly' ? 1 : 2 }}">
                    {{ $day['name'] }}&nbsp;<span style="font-weight:normal; opacity:.7; font-size:6px;">{{ $day['date'] }}</span>
                </th>
            @endforeach
        </tr>
        <tr class="row-sub">
            @foreach ($days as $day)
                @if ($periodType === 'nightly')
                    <th>NIGHT</th>
                @else
                    <th style="color:#b05000;">AM</th>
                    <th style="color:#005a9e;">PM</th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($areaParts as $area)
        <tr>
            <td class="col-item">{{ $area->name }}</td>
            @foreach ($days as $day)
                @if ($periodType === 'nightly')
                    @php
                        $ini = $recordMap[$area->location_area_part_id][$day['full_date']]['AM']
                            ?? $recordMap[$area->location_area_part_id][$day['full_date']]['PM']
                            ?? null;
                    @endphp
                    <td class="{{ $ini ? 'cell-done' : 'cell-empty' }}" style="padding:3px 2px;">
                        @if($ini)
                            <span class="mark">●</span>
                            <span class="ini">{{ $ini }}</span>
                        @endif
                    </td>
                @else
                    @php
                        $iniAm = $recordMap[$area->location_area_part_id][$day['full_date']]['AM'] ?? null;
                        $iniPm = $recordMap[$area->location_area_part_id][$day['full_date']]['PM'] ?? null;
                    @endphp
                    <td class="{{ $iniAm ? 'cell-done' : 'cell-empty' }}" style="padding:3px 2px;">
                        @if($iniAm)
                            <span class="mark">●</span>
                            <span class="ini">{{ $iniAm }}</span>
                        @endif
                    </td>
                    <td class="{{ $iniPm ? 'cell-done' : 'cell-empty' }}" style="padding:3px 2px;">
                        @if($iniPm)
                            <span class="mark">●</span>
                            <span class="ini">{{ $iniPm }}</span>
                        @endif
                    </td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<div class="bottom">
    <div class="sig">
        <div class="field-label">Checked By (Maintenance)</div>
        <div class="field-box">
            @foreach ($maintenanceByDate as $date => $ini)
                @if($ini)<span style="color:#7a9da2; font-size:6.5px;">{{ \Carbon\Carbon::parse($date)->format('D') }}:</span> {{ $ini }}&nbsp;&nbsp;@endif
            @endforeach
        </div>
    </div>
    <div class="sig">
        <div class="field-label">Verified By (Supervisor)</div>
        <div class="field-box">
            @foreach ($verifierByDate as $date => $ini)
                @if($ini)<span style="color:#7a9da2; font-size:6.5px;">{{ \Carbon\Carbon::parse($date)->format('D') }}:</span> {{ $ini }}&nbsp;&nbsp;@endif
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- WEEKLY TABLE — all 52 weeks grouped by month --}}
{{-- ══════════════════════════════════════════════ --}}
@elseif ($periodType === 'weekly')
@php $monthGroups = collect($weeks ?? [])->groupBy('month'); @endphp
<table class="ct">
    <thead>
        <tr class="row-top">
            <th rowspan="2" style="width:26%; text-align:left; padding-left:7px;">Area / Item</th>
            @foreach ($monthGroups as $mon => $mWeeks)
                <th colspan="{{ count($mWeeks) }}" style="font-size:6.5px; padding:3px 1px; border-left:1px solid #b2dde2;">
                    {{ $mWeeks->first()['month_label'] }}
                </th>
            @endforeach
        </tr>
        <tr class="row-sub">
            @foreach ($weeks ?? [] as $w => $week)
                <th style="font-size:5px; padding:1px 0;">{{ $week['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($areaParts as $area)
        <tr>
            <td class="col-item">{{ $area->name }}</td>
            @foreach ($weeks ?? [] as $w => $week)
            @php $ini = $recordMap[$area->location_area_part_id][$w] ?? null; @endphp
            <td class="{{ $ini ? 'cell-done' : 'cell-empty' }}" style="padding:2px 1px; text-align:center;">
                @if($ini)
                    <span style="color:#097b86; font-size:7px; font-weight:bold; display:block; line-height:1.1;">●</span>
                    <span style="color:#6a8f95; font-size:4.5px; display:block; line-height:1.1;">{{ $ini }}</span>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<div class="bottom">
    <div class="sig">
        <div class="field-label">Checked By (Maintenance)</div>
        <div class="field-box">{{ $maintenanceInitials ?? '—' }}</div>
    </div>
    <div class="sig">
        <div class="field-label">Verified By (Supervisor)</div>
        <div class="field-box">{{ $verifierInitials ?? '—' }}</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- MONTHLY TABLE — all 12 months of the year    --}}
{{-- ══════════════════════════════════════════════ --}}
@else
<table class="ct">
    <thead>
        <tr class="row-top">
            <th style="width:35%; text-align:left; padding-left:7px;">Area / Item</th>
            @foreach ($months ?? [] as $num => $label)
                <th style="font-size:7px; padding:4px 2px;">{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($areaParts as $area)
        @php $partId = $area->location_area_part_id; @endphp
        <tr>
            <td class="col-item">{{ $area->name }}</td>
            @foreach ($months ?? [] as $num => $label)
            @php $ini = $recordMap[$partId][$num] ?? null; @endphp
            <td class="{{ $ini ? 'cell-done' : 'cell-empty' }}" style="padding:3px 2px;">
                @if($ini)
                    <span class="mark">●</span>
                    <span class="ini">{{ $ini }}</span>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<div class="bottom">
    <div class="sig">
        <div class="field-label">Checked By (Maintenance)</div>
        <div class="field-box">{{ $maintenanceInitials ?? '—' }}</div>
    </div>
    <div class="sig">
        <div class="field-label">Verified By (Supervisor)</div>
        <div class="field-box">{{ $verifierInitials ?? '—' }}</div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════ --}}
{{-- COMMENTS --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="comments-wrap">
    <div class="comments-label">Observations / Non-Conformances &amp; Action Taken</div>
    <div class="comments-box">
        @forelse ($comments as $c)
            <div class="c-entry">
                <div class="c-header">
                    <div class="c-left">
                        @if(!empty($c['frequency']))<span class="c-freq">{{ $c['frequency'] }}</span>@endif
                        @if(!empty($c['area_name']))<span class="c-area">{{ $c['area_name'] }}</span>@endif
                    </div>
                    <div class="c-right">
                        <span class="c-meta">{{ \Carbon\Carbon::parse($c['date'])->format('M d, Y') }} &middot; {{ $c['person'] }} ({{ $c['type'] }})</span>
                    </div>
                </div>
                <div class="c-text">{{ $c['text'] }}</div>
            </div>
        @empty
            <span class="c-empty">No observations recorded for this period.</span>
        @endforelse
    </div>
</div>

{{-- ── FOOTER ───────────────────────────────────── --}}
<div class="footer">
    <div class="fl">NB: ● = Completed · Initials below mark indicate who performed the task. System-generated — no manual signature required.</div>
    <div class="fr">NLAH-HK-001 · Rev.01 · {{ $generatedAt }}</div>
</div>
</div> </body>
</html>