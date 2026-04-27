<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Information Slip — {{ $employee->last_name }}, {{ $employee->first_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            padding: 24px 32px;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px dashed #999;
            padding: 10px 16px;
            margin-bottom: 6px;
        }
        .header-center {
            text-align: center;
            flex: 1;
        }
        .header-center .hospital-name {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-center .address {
            font-size: 11px;
            font-style: italic;
        }
        .header-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .header-logo-right {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .slip-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 10px 0 14px;
            text-decoration: underline;
        }

        /* Name / Date row */
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        .meta-row .meta-item {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }
        .meta-row .meta-label {
            font-size: 11px;
        }
        .meta-row .meta-value {
            font-size: 12px;
            font-weight: bold;
        }

        /* Body content */
        .content {
            margin-top: 14px;
            line-height: 1.8;
        }
        .content p {
            margin-bottom: 4px;
        }
        .content .bold {
            font-weight: bold;
        }
        .content .underline {
            text-decoration: underline;
        }
        .highlight-row {
            background: #e8e8e8;
            padding: 3px 6px;
            border: 1px solid #bbb;
            margin: 6px 0;
        }
        .monthly-salary-row {
            font-size: 12px;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 4px 0;
            margin: 4px 0;
        }

        /* Footer notes */
        .footnotes {
            margin-top: 14px;
            font-size: 10.5px;
            line-height: 1.6;
        }

        /* Print controls */
        .print-controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-controls button {
            background: #015581;
            color: #fff;
            border: none;
            padding: 8px 24px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            margin: 0 6px;
        }
        .print-controls button.close-btn {
            background: #6b7280;
        }

        @media print {
            .print-controls { display: none; }
            body { padding: 12px 20px; }
        }
    </style>
</head>
<body>

<div class="print-controls">
    <button onclick="window.print()">Print</button>
    <button class="close-btn" onclick="window.close()">Close</button>
</div>

@php
    $payroll = $employee->payrollLeave;
    $detail  = $employee->employmentDetail;

    $pct          = (float) ($payroll?->salary_rate ?? 0);          // e.g. 47
    $wageFactor   = (float) ($payroll?->wage_factor ?? 0);          // e.g. 30000
    $cola         = (float) ($payroll?->cola ?? 0);
    $grocery      = (float) ($payroll?->grocery_allowance ?? 0);
    $minRate      = (float) ($payroll?->min_scale ?? 0);
    $maxRate      = (float) ($payroll?->max_scale ?? 0);
    $monthlyRate  = (float) ($payroll?->monthly_rate ?? 0);
    $dailyRate    = (float) ($payroll?->daily_rate ?? 0);
    $totalSalary  = $monthlyRate + $cola + $grocery;

    // Example at 55%
    $exPct        = 55;
    $exMonthly    = ($exPct / 100) * $wageFactor;
    $exTotal      = $exMonthly + $cola + $grocery;

    $name         = strtoupper(trim("{$employee->last_name}, {$employee->first_name} {$employee->middle_name}"));
    $position     = strtoupper($detail?->position ?? '—');
    $department   = strtoupper($detail?->department?->name ?? '—');
@endphp

{{-- Header --}}
<div class="header">
    <img src="{{ asset('image/logo.png') }}" class="header-logo" alt="NLAH Logo" onerror="this.style.display='none'">
    <div class="header-center">
        <div class="hospital-name">NORTHERN LUZON ADVENTIST HOSPITAL INC.</div>
        <div class="address">Artacho, Sison, Pangasinan, 2434</div>
        <div class="address">Tel. No.: (075) 632-3200 / Email: nlahospital@yahoo.com</div>
    </div>
    <img src="{{ public_path('image/logo.png') }}" class="header-logo-right" alt="" onerror="this.style.display='none'">
</div>

<div class="slip-title">SALARY INFORMATION SLIP</div>

{{-- Name / Date --}}
<div class="meta-row">
    <div class="meta-item">
        <span class="meta-label">Name:</span>
        <span class="meta-value">{{ $name }}</span>
    </div>
    <div class="meta-item">
        <span class="meta-label">Date:</span>
        <span class="meta-value">{{ now()->format('n/j/Y') }}</span>
    </div>
</div>
<div class="meta-row" style="margin-top:4px;">
    <div class="meta-item">
        <span class="meta-label">Department:</span>
        <span class="meta-value">{{ $department }}</span>
    </div>
    <div class="meta-item">
        <span class="meta-label">Position</span>
        <span class="meta-value">{{ $position }}</span>
    </div>
</div>

{{-- Body --}}
<div class="content">
    <p>Current Active Percentage Rate: <span class="bold">{{ number_format($pct, 0) }}%</span></p>

    <p style="margin-top:8px;">
        Actual percentage rate <span class="bold">ON RECORD</span> due to new SSD policy for household benefits:
        <span class="bold underline">{{ number_format($minRate, 0) }}%-{{ number_format($maxRate, 0) }}%</span>
    </p>
    <p style="font-size:11px;">(Applicable to employees with head of the family benefits with a salary rate of 65% and above)</p>

    <p style="margin-top:10px;">
        Current Wage Factor: <span class="bold">P {{ number_format($wageFactor, 2) }}</span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        Current COLA (Cost of living allowance): <span class="bold">P {{ number_format($cola, 2) }}</span>
    </p>

    <p style="margin-top:12px;">
        Computation of Salary: &nbsp; /Current/Active Percentage Rate &times; Wage Factor + COLA + Grocery Allowance
    </p>

    <p style="margin-top:4px; padding-left: 20px;">
        Example: &nbsp;&nbsp; ({{ $exPct }}%): &nbsp;
        .{{ $exPct }} &times; P{{ number_format($wageFactor, 2) }}
        = P {{ number_format($exMonthly, 2) }} + P {{ number_format($cola, 2) }} + P{{ number_format($grocery, 2) }}
    </p>

    <div class="monthly-salary-row" style="margin-top:10px;">
        Your monthly salary: &nbsp;
        <span class="bold">
            {{ number_format($pct, 0) }}% X {{ number_format($wageFactor, 2) }}
            = {{ number_format($monthlyRate, 2) }}
            + {{ number_format($cola, 2) }}
            + {{ number_format($grocery, 2) }}
            = {{ number_format($totalSalary, 2) }}
        </span>
    </div>

    <div class="highlight-row" style="margin-top:8px;">
        Daily rate computation: &nbsp; Monthly salary &times; 12 divided by 262: &nbsp;
        <span class="bold">{{ number_format($dailyRate, 2) }}</span>
    </div>
</div>

{{-- Footnotes --}}
<div class="footnotes">
    <p>*The remuneration scale which includes the minimum - maximum percentage rates and the category of each position is subject to change (decrease/increase; downgrade/upgrade) depending on economic reasons, community rates, gov't wage orders, etc. which may result to an increase or status quo but never a decrease in the actual peso amount you have already received in the past.</p>
    <p style="margin-top:6px;">*Except for adjustments due to the above or due to distortions and promotions, increase in the actual percentage rate is based on merit earned for work performance as recommended by the department head and approved by the Administrative Committee.</p>
</div>

</body>
</html>
