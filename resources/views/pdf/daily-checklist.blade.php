<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cleaning Checklist - {{ $location->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 15px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px 5px;
            text-align: center;
            vertical-align: middle;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .header h4 {
            margin: 10px 0 5px;
            font-size: 16px;
            text-decoration: underline;
        }

        .item {
            text-align: left;
            font-weight: normal;
        }

        .item b {
            font-weight: bold;
        }

        .small {
            font-size: 10px;
            margin-top: 10px;
            font-style: italic;
        }

        .footer-info {
            margin-top: 20px;
            font-size: 10px;
            color: #666;
            text-align: right;
        }

        .period-info {
            margin-bottom: 15px;
            font-size: 12px;
            text-align: left;
        }

        .period-info span {
            font-weight: bold;
        }

        .date-header {
            background-color: #f0f0f0;
        }
        
        .ok-mark {
            font-size: 30px;
            font-weight: bold;
            color: #000000;
        }
        
        .initials-row td {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            background-color: #f0f0f0;
            padding: 8px 5px;
        }
        
        .blank-cell {
            background-color: #f9f9f9;
        }
        
        .initials-cell {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }
        
        .month-header, .week-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .date-row {
            background-color: #f9f9f9;
            font-weight: normal;
        }
        
        /* Portrait orientation for monthly */
        .portrait {
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>Northern Luzon Adventist Hospital</h3>
        <p>Quality Management System</p>
        <h4>
            @if($periodType === 'daily')
                Daily Cleaning Checklist - Week of {{ \Carbon\Carbon::parse($selectedDate)->startOfWeek(\Carbon\Carbon::MONDAY)->format('M d') }} - {{ \Carbon\Carbon::parse($selectedDate)->endOfWeek(\Carbon\Carbon::SUNDAY)->format('M d, Y') }}
            @elseif($periodType === 'weekly')
                Weekly Cleaning Checklist - {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}
            @else
                Monthly Cleaning Checklist - {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}
            @endif
        </h4>
        <p style="font-size: 14px; margin-top: 5px;">{{ strtoupper($location->name) }} ({{ $location->floor }})</p>
    </div>

    <div class="period-info">
        <span>Period:</span> 
        @if($periodType === 'daily')
            Week of {{ \Carbon\Carbon::parse($selectedDate)->startOfWeek(\Carbon\Carbon::MONDAY)->format('M d') }} - {{ \Carbon\Carbon::parse($selectedDate)->endOfWeek(\Carbon\Carbon::SUNDAY)->format('M d, Y') }}
        @elseif($periodType === 'weekly')
            {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}
        @else
            {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}
        @endif
        <br>
        <span>Generated:</span> {{ $generatedAt }}
    </div>

    @php
        // Group records by location_area_part_id and date
        $recordsByPartAndDate = [];
        $maintenanceByDate = []; // Track maintenance names by date
        $verifierByDate = []; // Track verifier names by date (only if verified)
        $maintenanceByPeriod = []; // Track maintenance names by period (month/week)
        $verifierByPeriod = []; // Track verifier names by period (month/week)
        
        foreach($records as $record) {
            $key = $record->location_area_part_id . '_' . $record->cleaning_date . '_' . $record->shift;
            $recordsByPartAndDate[$key] = $record;
            
            // Track maintenance by date for daily view
            if ($record->maintenance_name) {
                if (!isset($maintenanceByDate[$record->cleaning_date])) {
                    $maintenanceByDate[$record->cleaning_date] = [];
                }
                if (!in_array($record->maintenance_name, $maintenanceByDate[$record->cleaning_date])) {
                    $maintenanceByDate[$record->cleaning_date][] = $record->maintenance_name;
                }
            }
            
            // Track verifier by date for daily view (only if verified)
            if ($record->verifier_name && $record->verifier_status === 'YES') {
                if (!isset($verifierByDate[$record->cleaning_date])) {
                    $verifierByDate[$record->cleaning_date] = [];
                }
                if (!in_array($record->verifier_name, $verifierByDate[$record->cleaning_date])) {
                    $verifierByDate[$record->cleaning_date][] = $record->verifier_name;
                }
            }
            
            // For monthly: group by month
            if ($periodType === 'monthly') {
                $month = \Carbon\Carbon::parse($record->cleaning_date)->format('F');
                
                // Track maintenance name for this month
                if ($record->maintenance_name) {
                    if (!isset($maintenanceByPeriod[$month])) {
                        $maintenanceByPeriod[$month] = [];
                    }
                    if (!in_array($record->maintenance_name, $maintenanceByPeriod[$month])) {
                        $maintenanceByPeriod[$month][] = $record->maintenance_name;
                    }
                }
                
                // Track verifier name for this month (only if verified)
                if ($record->verifier_name && $record->verifier_status === 'YES') {
                    if (!isset($verifierByPeriod[$month])) {
                        $verifierByPeriod[$month] = [];
                    }
                    if (!in_array($record->verifier_name, $verifierByPeriod[$month])) {
                        $verifierByPeriod[$month][] = $record->verifier_name;
                    }
                }
            }
            
            // For weekly: group by week
            if ($periodType === 'weekly') {
                $weekStart = \Carbon\Carbon::parse($record->cleaning_date)->startOfWeek(\Carbon\Carbon::MONDAY);
                $weekEnd = $weekStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
                $weekLabel = $weekStart->format('M j') . '-' . $weekEnd->format('j');
                
                // Track maintenance name for this week
                if ($record->maintenance_name) {
                    if (!isset($maintenanceByPeriod[$weekLabel])) {
                        $maintenanceByPeriod[$weekLabel] = [];
                    }
                    if (!in_array($record->maintenance_name, $maintenanceByPeriod[$weekLabel])) {
                        $maintenanceByPeriod[$weekLabel][] = $record->maintenance_name;
                    }
                }
                
                // Track verifier name for this week (only if verified)
                if ($record->verifier_name && $record->verifier_status === 'YES') {
                    if (!isset($verifierByPeriod[$weekLabel])) {
                        $verifierByPeriod[$weekLabel] = [];
                    }
                    if (!in_array($record->verifier_name, $verifierByPeriod[$weekLabel])) {
                        $verifierByPeriod[$weekLabel][] = $record->verifier_name;
                    }
                }
            }
        }
        
        // Function to check if a record exists for a specific part, date, and shift
        function hasRecordForDateAndShift($recordsByPartAndDate, $partId, $date, $shift) {
            $key = $partId . '_' . $date . '_' . $shift;
            return isset($recordsByPartAndDate[$key]);
        }
        
        // Function to get formatted initials with dots
        function getFormattedInitials($name) {
            if (empty($name)) return '';
            $words = explode(' ', $name);
            $initials = [];
            foreach ($words as $word) {
                if (!empty($word)) {
                    $initials[] = strtoupper(substr($word, 0, 1)) . '.';
                }
            }
            return implode('', $initials);
        }
        
        // Function to format multiple initials
        function formatInitialsList($names) {
            if (empty($names)) return '';
            $initialsList = [];
            foreach ($names as $name) {
                $initialsList[] = getFormattedInitials($name);
            }
            return implode(', ', $initialsList);
        }
        
        // Get weeks for the current month only
        $currentMonth = \Carbon\Carbon::parse($selectedDate)->month;
        $currentYear = \Carbon\Carbon::parse($selectedDate)->year;
        $startOfMonth = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $weeksInMonth = [];
        $currentDate = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        
        while ($currentDate <= $endOfMonth) {
            $weekStart = $currentDate->copy();
            $weekEnd = $weekStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
            
            // Only include weeks that have at least one day in the current month
            if ($weekStart <= $endOfMonth || $weekEnd >= $startOfMonth) {
                $weeksInMonth[] = [
                    'label' => $weekStart->format('M j') . '-' . $weekEnd->format('j'),
                    'start' => $weekStart->toDateString(),
                    'end' => $weekEnd->toDateString()
                ];
            }
            
            $currentDate->addWeek();
        }
        
        // Generate month columns for monthly view (all 12 months)
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = \Carbon\Carbon::createFromDate(null, $i, 1)->format('F');
        }
    @endphp

    @if($periodType === 'daily')
        {{-- Daily View - Full week Monday to Sunday --}}
        @php
            $startOfWeek = \Carbon\Carbon::parse($selectedDate)->startOfWeek(\Carbon\Carbon::MONDAY);
            $days = [];
            for ($i = 0; $i < 7; $i++) {
                $currentDay = $startOfWeek->copy()->addDays($i);
                $days[] = [
                    'name' => $currentDay->format('D'),
                    'date' => $currentDay->format('M d'),
                    'full_date' => $currentDay->toDateString()
                ];
            }
        @endphp
        
        <table>
            <thead>
                <tr>
                    <th rowspan="3" style="width: 15%;">AREA / ITEM</th>
                    @foreach($days as $day)
                        <th colspan="2">{{ strtoupper($day['name']) }}<br><span style="font-size:10px; font-weight:normal;">{{ $day['date'] }}</span></th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($days as $day)
                        <th colspan="2" style="font-size:10px; font-weight:normal;">DATE</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($days as $day)
                        <th>AM</th>
                        <th>PM</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($areaParts as $area)
                <tr>
                    <td class="item">{{ $area->name }}</td>
                    @foreach($days as $day)
                        @php
                            $hasAmRecord = hasRecordForDateAndShift($recordsByPartAndDate, $area->location_area_part_id, $day['full_date'], 'AM');
                            $hasPmRecord = hasRecordForDateAndShift($recordsByPartAndDate, $area->location_area_part_id, $day['full_date'], 'PM');
                        @endphp
                        
                        {{-- AM Cell --}}
                        <td class="{{ $hasAmRecord ? 'ok-mark' : 'blank-cell' }}" align="center">
                            @if($hasAmRecord)
                                ✓
                            @endif
                        </td>

                        {{-- PM Cell --}}
                        <td class="{{ $hasPmRecord ? 'ok-mark' : 'blank-cell' }}" align="center">
                            @if($hasPmRecord)
                                ✓
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach

                {{-- CHECKED BY row with initials per day --}}
                <tr class="initials-row">
                    <td class="item"><b>CHECKED BY:</b></td>
                    @foreach($days as $day)
                        <td colspan="2" class="initials-cell">
                            @php
                                $initials = isset($maintenanceByDate[$day['full_date']]) ? formatInitialsList($maintenanceByDate[$day['full_date']]) : '';
                            @endphp
                            {{ $initials }}
                        </td>
                    @endforeach
                </tr>

                {{-- VERIFIED BY row with initials per day (only if verified) --}}
                <tr class="initials-row">
                    <td class="item"><b>VERIFIED BY:</b></td>
                    @foreach($days as $day)
                        <td colspan="2" class="initials-cell">
                            @php
                                $initials = isset($verifierByDate[$day['full_date']]) ? formatInitialsList($verifierByDate[$day['full_date']]) : '';
                            @endphp
                            {{ $initials }}
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td class="item" style="vertical-align: top;"><b>COMMENTS / NON-CONFORMANCES AND ACTION TAKEN</b></td>
                    <td colspan="14" style="height: 60px; vertical-align: top; text-align: left; padding: 8px;">
                        @php
                            $comments = [];
                            foreach($records as $record) {
                                $recordDate = \Carbon\Carbon::parse($record->cleaning_date);
                                if ($recordDate->between($startOfWeek, $startOfWeek->copy()->endOfWeek(\Carbon\Carbon::SUNDAY))) {
                                    if (!empty($record->verifier_comments)) {
                                        $comments[] = $record->verifier_comments;
                                    }
                                    if (!empty($record->maintenance_comments)) {
                                        $comments[] = $record->maintenance_comments;
                                    }
                                }
                            }
                            $comments = array_unique($comments);
                        @endphp
                        @foreach($comments as $comment)
                            @if(!empty($comment))
                                • {{ $comment }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>

    @elseif($periodType === 'weekly')
        {{-- Weekly View - Single page for current month --}}
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 15%;">{{ strtoupper($location->name) }}</th>
                    @foreach($weeksInMonth as $week)
                        <th>WEEK<br><span style="font-size:10px; font-weight:normal;">{{ $week['label'] }}</span></th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($weeksInMonth as $week)
                        <th style="font-size:10px; font-weight:normal;">DATE</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($areaParts as $area)
                <tr>
                    <td class="item">{{ $area->name }}</td>
                    @foreach($weeksInMonth as $week)
                        @php
                            // Check if there's any record for this area part during this week
                            $hasRecord = false;
                            foreach($records as $record) {
                                if ($record->location_area_part_id == $area->location_area_part_id) {
                                    $recordDate = \Carbon\Carbon::parse($record->cleaning_date);
                                    if ($recordDate->between($week['start'], $week['end'])) {
                                        $hasRecord = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <td class="{{ $hasRecord ? 'ok-mark' : 'blank-cell' }}">
                            @if($hasRecord) OK @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach

                <tr class="initials-row">
                    <td class="item"><b>CHECKED BY:</b></td>
                    @foreach($weeksInMonth as $week)
                        <td class="initials-cell">
                            @php
                                $weekLabel = $week['label'];
                                $initials = isset($maintenanceByPeriod[$weekLabel]) ? formatInitialsList($maintenanceByPeriod[$weekLabel]) : '';
                            @endphp
                            {{ $initials }}
                        </td>
                    @endforeach
                </tr>

                <tr class="initials-row">
                    <td class="item"><b>VERIFIED BY:</b></td>
                    @foreach($weeksInMonth as $week)
                        <td class="initials-cell">
                            @php
                                $weekLabel = $week['label'];
                                $initials = isset($verifierByPeriod[$weekLabel]) ? formatInitialsList($verifierByPeriod[$weekLabel]) : '';
                            @endphp
                            {{ $initials }}
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td class="item" style="vertical-align: top;"><b>COMMENTS / NON-CONFORMANCES AND ACTION TAKEN</b></td>
                    <td colspan="{{ count($weeksInMonth) }}" style="height: 80px; vertical-align: top; text-align: left; padding: 8px;">
                        @php
                            $comments = [];
                            $monthStart = $startOfMonth->toDateString();
                            $monthEnd = $endOfMonth->toDateString();
                            
                            foreach($records as $record) {
                                $recordDate = \Carbon\Carbon::parse($record->cleaning_date);
                                if ($recordDate->between($monthStart, $monthEnd)) {
                                    if (!empty($record->verifier_comments)) {
                                        $comments[] = $record->verifier_comments;
                                    }
                                    if (!empty($record->maintenance_comments)) {
                                        $comments[] = $record->maintenance_comments;
                                    }
                                }
                            }
                            $comments = array_unique($comments);
                        @endphp
                        @foreach($comments as $comment)
                            @if(!empty($comment))
                                • {{ $comment }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>

    @elseif($periodType === 'monthly')
        {{-- Monthly View - Single row per month (no YES/NO split) --}}
        @for($page = 0; $page < 3; $page++) {{-- 3 pages for 12 months --}}
            @php
                $startMonth = $page * 4;
                $monthsToShow = array_slice($months, $startMonth, 4);
            @endphp
            
            <table style="page-break-after: {{ $page < 2 ? 'always' : 'auto' }}; width: 100%;">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 15%;">{{ strtoupper($location->name) }}</th>
                        @foreach($monthsToShow as $month)
                            <th>MONTH ({{ $month }})</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($monthsToShow as $month)
                            <th style="font-size:10px; font-weight:normal;">DATE</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($areaParts as $area)
                    <tr>
                        <td class="item">{{ $area->name }}</td>
                        @foreach($monthsToShow as $monthIndex => $month)
                            @php
                                // Check if there's any record for this area part during this month
                                $monthNum = $startMonth + $monthIndex + 1;
                                $year = \Carbon\Carbon::parse($selectedDate)->year;
                                $startOfMonth = \Carbon\Carbon::createFromDate($year, $monthNum, 1)->startOfMonth();
                                $endOfMonth = $startOfMonth->copy()->endOfMonth();
                                
                                $hasRecord = false;
                                foreach($records as $record) {
                                    if ($record->location_area_part_id == $area->location_area_part_id) {
                                        $recordDate = \Carbon\Carbon::parse($record->cleaning_date);
                                        if ($recordDate->between($startOfMonth, $endOfMonth)) {
                                            $hasRecord = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <td class="{{ $hasRecord ? 'ok-mark' : 'blank-cell' }}">
                                @if($hasRecord) X @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach

                    <tr class="initials-row">
                        <td class="item"><b>CHECKED BY:</b></td>
                        @foreach($monthsToShow as $month)
                            <td class="initials-cell">
                                @php
                                    $initials = isset($maintenanceByPeriod[$month]) ? formatInitialsList($maintenanceByPeriod[$month]) : '';
                                @endphp
                                {{ $initials }}
                            </td>
                        @endforeach
                    </tr>

                    <tr class="initials-row">
                        <td class="item"><b>VERIFIED BY:</b></td>
                        @foreach($monthsToShow as $month)
                            <td class="initials-cell">
                                @php
                                    $initials = isset($verifierByPeriod[$month]) ? formatInitialsList($verifierByPeriod[$month]) : '';
                                @endphp
                                {{ $initials }}
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        <td class="item" style="vertical-align: top;"><b>COMMENTS / NON-CONFORMANCES AND ACTION TAKEN</b></td>
                        <td colspan="{{ count($monthsToShow) }}" style="height: 80px; vertical-align: top; text-align: left; padding: 8px;">
                            @php
                                $comments = [];
                                $startMonthNum = $startMonth + 1;
                                $endMonthNum = $startMonth + count($monthsToShow);
                                $year = \Carbon\Carbon::parse($selectedDate)->year;
                                
                                foreach($records as $record) {
                                    $recordMonth = \Carbon\Carbon::parse($record->cleaning_date)->month;
                                    $recordYear = \Carbon\Carbon::parse($record->cleaning_date)->year;
                                    
                                    if ($recordYear == $year && $recordMonth >= $startMonthNum && $recordMonth <= $endMonthNum) {
                                        if (!empty($record->verifier_comments)) {
                                            $comments[] = $record->verifier_comments;
                                        }
                                        if (!empty($record->maintenance_comments)) {
                                            $comments[] = $record->maintenance_comments;
                                        }
                                    }
                                }
                                $comments = array_unique($comments);
                            @endphp
                            @foreach($comments as $comment)
                                @if(!empty($comment))
                                    • {{ $comment }}<br>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            
            @if($page < 2)
                <div style="margin-top: 20px; margin-bottom: 10px; text-align: center;">---</div>
            @endif
        @endfor
    @endif

    <p class="small">
        NB: Please use your initials when completing this checklist. OK indicates completed item.
    </p>

    <div class="footer-info">
        This is a system-generated document. No signature is required.
    </div>

</body>
</html>