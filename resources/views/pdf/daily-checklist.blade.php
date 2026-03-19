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

        .ok-mark {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }

        .initials-row td {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            background-color: #f0f0f0;
        }

        .blank-cell {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>Northern Luzon Adventist Hospital</h3>
        <p>Quality Management System</p>
        <h4>
            @if ($periodType === 'daily')
                Daily Cleaning Checklist - {{ $periodLabel }}
            @elseif ($periodType === 'weekly')
                Weekly Cleaning Checklist - {{ $periodLabel }}
            @else
                Monthly Cleaning Checklist - {{ $periodLabel }}
            @endif
        </h4>
        <p style="font-size: 14px; margin-top: 5px;">{{ strtoupper($location->name) }}@if(filled($location->floor)) ({{ $location->floor }})@endif</p>
    </div>

    <div class="period-info">
        <strong>Period:</strong> {{ $periodLabel }}<br>
        <strong>Generated:</strong> {{ $generatedAt }}
    </div>

    @if ($periodType === 'daily')
        <table>
            <thead>
                <tr>
                    <th rowspan="3" style="width: 18%;">AREA / ITEM</th>
                    @foreach ($days as $day)
                        <th colspan="2">{{ $day['name'] }}<br><span style="font-size:10px; font-weight:normal;">{{ $day['date'] }}</span></th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($days as $day)
                        <th colspan="2" style="font-size:10px; font-weight:normal;">DATE</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($days as $day)
                        <th>AM</th>
                        <th>PM</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($areaParts as $area)
                    <tr>
                        <td class="item">{{ $area->name }}</td>
                        @foreach ($days as $day)
                            @php
                                $hasAmRecord = (bool) ($recordMap[$area->location_area_part_id][$day['full_date']]['AM'] ?? false);
                                $hasPmRecord = (bool) ($recordMap[$area->location_area_part_id][$day['full_date']]['PM'] ?? false);
                            @endphp
                            <td class="{{ $hasAmRecord ? 'ok-mark' : 'blank-cell' }}">{{ $hasAmRecord ? '✓' : '' }}</td>
                            <td class="{{ $hasPmRecord ? 'ok-mark' : 'blank-cell' }}">{{ $hasPmRecord ? '✓' : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach

                <tr class="initials-row">
                    <td class="item"><b>CHECKED BY:</b></td>
                    @foreach ($days as $day)
                        <td colspan="2">{{ $maintenanceByDate[$day['full_date']] ?? '' }}</td>
                    @endforeach
                </tr>

                <tr class="initials-row">
                    <td class="item"><b>VERIFIED BY:</b></td>
                    @foreach ($days as $day)
                        <td colspan="2">{{ $verifierByDate[$day['full_date']] ?? '' }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="item" style="vertical-align: top;"><b>COMMENTS / NON-CONFORMANCES AND ACTION TAKEN</b></td>
                    <td colspan="14" style="height: 60px; vertical-align: top; text-align: left; padding: 8px;">
                        @foreach ($comments as $comment)
                            {{ '- '.$comment }}<br>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    @elseif ($periodType === 'weekly')
        <table>
            <thead>
                <tr>
                    <th style="width: 70%;">AREA / ITEM</th>
                    <th>{{ $periodLabel }}</th>
                </tr>
                <tr>
                    <th></th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($areaParts as $area)
                    @php
                        $hasRecord = (bool) ($recordMap[$area->location_area_part_id] ?? false);
                    @endphp
                    <tr>
                        <td class="item">{{ $area->name }}</td>
                        <td class="{{ $hasRecord ? 'ok-mark' : 'blank-cell' }}">{{ $hasRecord ? '✓' : '' }}</td>
                    </tr>
                @endforeach

                <tr class="initials-row">
                    <td class="item"><b>CHECKED BY:</b></td>
                    <td>{{ $maintenanceInitials ?? '' }}</td>
                </tr>

                <tr class="initials-row">
                    <td class="item"><b>VERIFIED BY:</b></td>
                    <td>{{ $verifierInitials ?? '' }}</td>
                </tr>

                <tr>
                    <td class="item" style="vertical-align: top;"><b>COMMENTS / NON-CONFORMANCES AND ACTION TAKEN</b></td>
                    <td style="height: 80px; vertical-align: top; text-align: left; padding: 8px;">
                        @foreach ($comments as $comment)
                            {{ '- '.$comment }}<br>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 70%;">AREA / ITEM</th>
                    <th>{{ $periodLabel }}</th>
                </tr>
                <tr>
                    <th></th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($areaParts as $area)
                    @php
                        $hasRecord = (bool) ($recordMap[$area->location_area_part_id] ?? false);
                    @endphp
                    <tr>
                        <td class="item">{{ $area->name }}</td>
                        <td class="{{ $hasRecord ? 'ok-mark' : 'blank-cell' }}">{{ $hasRecord ? '✓' : '' }}</td>
                    </tr>
                @endforeach

                <tr class="initials-row">
                    <td class="item"><b>CHECKED BY:</b></td>
                    <td>{{ $maintenanceInitials ?? '' }}</td>
                </tr>

                <tr class="initials-row">
                    <td class="item"><b>VERIFIED BY:</b></td>
                    <td>{{ $verifierInitials ?? '' }}</td>
                </tr>

                <tr>
                    <td class="item" style="vertical-align: top;"><b>COMMENTS / NON-CONFORMANCES AND ACTION TAKEN</b></td>
                    <td style="height: 80px; vertical-align: top; text-align: left; padding: 8px;">
                        @foreach ($comments as $comment)
                            {{ '- '.$comment }}<br>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    @endif

    <p class="small">
        NB: Please use your initials when completing this checklist. OK indicates completed item.
    </p>

    <div class="footer-info">
        This is a system-generated document. No signature is required.
    </div>
</body>
</html>
