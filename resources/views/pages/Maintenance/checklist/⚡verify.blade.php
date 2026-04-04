<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

new class extends Component {
    public array $selectedSlots = [];
    public array $slotProofs = [];
    public array $slotComments = [];
    public array $slotVerifierComments = [];
    public array $slotRecordIds = [];
    public bool $hasProofColumn = false;
    public ?int $pendingProofPartId = null;
    public ?string $pendingProofDayKey = null;
    public ?string $pendingProofShift = null;
    public array $areaParts = [];
    public array $locations = [];
    public string $selectedLocation = '';
    public ?int $selectedLocationId = null;
    public string $periodType = 'daily';
    public string $selectedDate = '';
    public string $calendarMonth = '';
    public string $weeklyMonth = '';
    public string $weeklyStart = '';
    public string $monthlyStart = '';
    public array $weeklyWeeks = [];
    public array $monthlyPeriods = [];
    public bool $showDailyChecklist = false;
    public bool $showProofPreviewModal = false;
    public ?string $proofPreviewUrl = null;
    public ?string $proofPreviewTitle = null;
    public ?string $proofPreviewComment = null;
    public bool $showVerifyModal = false;
    public ?int $verifyRecordId = null;
    public ?string $verifyPreviewUrl = null;
    public string $verifyComment = '';
    public array $days = [
        'mon' => 'Monday',
        'tue' => 'Tuesday',
        'wed' => 'Wednesday',
        'thu' => 'Thursday',
        'fri' => 'Friday',
    ];
    public array $shifts = ['AM', 'PM'];
    public array $weekDates = [];

    public function exportToPdf()
    {
        if (!$this->selectedLocationId) {
            $this->dispatch('notify', message: __('Please select a location first.'), type: 'error');
            return;
        }

        if (in_array($this->periodType, ['daily', 'nightly'], true) && !$this->showDailyChecklist) {
            $this->dispatch('notify', message: __('Please select a date first.'), type: 'error');
            return;
        }

        // Get location details
        $location = DB::table('locations')
            ->where('id', $this->selectedLocationId)
            ->first();

        if (!$location) {
            $this->dispatch('notify', message: __('Location not found.'), type: 'error');
            return;
        }

        // Get area parts for this location with the selected frequency
        $areaParts = DB::table('location_area_parts as lap')
            ->join('area_parts as ap', 'ap.id', '=', 'lap.area_part_id')
            ->where('lap.location_id', $this->selectedLocationId)
            ->where('lap.frequency', $this->periodType)
            ->orderBy('ap.name')
            ->select('ap.*', 'lap.id as location_area_part_id')
            ->get();

        if ($areaParts->isEmpty()) {
            $this->dispatch('notify', message: __('No area parts found for this location.'), type: 'error');
            return;
        }

        // Get records for this period
        $partIds = $areaParts->pluck('location_area_part_id')->toArray();
        $recordsQuery = DB::table('records')
    ->whereIn('location_area_part_id', $partIds)
    ->where('period_type', $this->periodType)
    ->where('status', 'YES')
    ->select([
        'location_area_part_id',
        'cleaning_date',        // ← was cleaning_datetime
        'shift',
        'maintenance_name',
        'verifier_name',
        'verifier_status',
        'maintenance_comments',
        'verifier_comments',
    ]);

        // Replace all whereBetween/whereDate on cleaning_datetime in exportToPdf:

if (in_array($this->periodType, ['daily', 'nightly'], true)) {
    $startOfWeek = Carbon::parse($this->selectedDate)->startOfWeek(Carbon::MONDAY)->toDateString();
    $endOfWeek   = Carbon::parse($this->selectedDate)->endOfWeek(Carbon::SUNDAY)->toDateString();
    $recordsQuery->whereBetween('cleaning_date', [$startOfWeek, $endOfWeek]);
} elseif ($this->periodType === 'weekly') {
    $refDate = $this->weeklyWeeks['w1']['start_date'] ?? ($this->weeklyStart ?: $this->selectedDate);
    $year = $refDate ? Carbon::parse($refDate)->year : now()->year;
    $recordsQuery->whereBetween('cleaning_date', ["{$year}-01-01", "{$year}-12-31"]);
} elseif ($this->periodType === 'monthly') {
    $refDate = $this->monthlyPeriods['m1']['start_date'] ?? ($this->monthlyStart ?: $this->selectedDate);
    $year = $refDate ? Carbon::parse($refDate)->year : now()->year;
    $recordsQuery->whereBetween('cleaning_date', ["{$year}-01-01", "{$year}-12-31"]);
}

        $records = $recordsQuery->get();
        $partNames = $areaParts->pluck('name', 'location_area_part_id')->toArray();
        $data = $this->buildPdfExportData($records, $partNames);
        $data = array_merge($data, [
            'location' => $location,
            'areaParts' => $areaParts,
            'periodType' => $this->periodType,
            'selectedDate' => $this->selectedDate,
            'generatedAt' => now('Asia/Manila')->format('F j, Y g:i A'),
        ]);

        // Generate PDF
        try {
            $pdf = Pdf::loadView('pdf.daily-checklist', $data);
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');
            
            // Generate filename
            $filename = sprintf(
                'cleaning-checklist-%s-%s-%s.pdf',
                preg_replace('/[^a-z0-9]+/', '-', strtolower($location->name)),
                $this->periodType,
                now('Asia/Manila')->format('Y-m-d-His')
            );
            
            // Return PDF download
            return response()->streamDownload(
                fn () => print($pdf->output()),
                $filename
            );
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: __('Error generating PDF: ') . $e->getMessage(), type: 'error');
            return;
        }
    }

    private function buildPdfExportData(\Illuminate\Support\Collection $records, array $partNames = []): array
{
    $normalizedRecords = $records->map(function ($record): array {
        return [
            'location_area_part_id' => (int) $record->location_area_part_id,
            'cleaning_date'         => $record->cleaning_date,  // ← was Carbon::parse(cleaning_datetime)
            'shift'                 => in_array($record->shift, $this->shifts, true) ? $record->shift : 'AM',
            'maintenance_name'      => is_string($record->maintenance_name ?? null) ? trim($record->maintenance_name) : '',
            'verifier_name'         => is_string($record->verifier_name ?? null) ? trim($record->verifier_name) : '',
            'verifier_status'       => $record->verifier_status,
            'maintenance_comments'  => is_string($record->maintenance_comments ?? null) ? trim($record->maintenance_comments) : '',
            'verifier_comments'     => is_string($record->verifier_comments ?? null) ? trim($record->verifier_comments) : '',
        ];
    });
    // rest of method unchanged...

        $commentsMap = [];
        foreach ($normalizedRecords as $record) {
            foreach (['maintenance_comments', 'verifier_comments'] as $commentField) {
                $comment = $record[$commentField];
                if ($comment === '') {
                    continue;
                }
                $person = $commentField === 'verifier_comments'
                    ? ($record['verifier_name'] ?: $record['maintenance_name'])
                    : $record['maintenance_name'];
                $key = $record['cleaning_date'].'|'.$person.'|'.$comment;
                $commentsMap[$key] = [
                    'date'      => $record['cleaning_date'],
                    'person'    => $person,
                    'type'      => $commentField === 'verifier_comments' ? 'Verifier' : 'Staff',
                    'text'      => $comment,
                    'area_name' => $partNames[$record['location_area_part_id']] ?? '',
                    'frequency' => ucfirst($this->periodType),
                ];
            }
        }

        $data = [
            'comments' => array_values($commentsMap),
        ];

        if (in_array($this->periodType, ['daily', 'nightly'], true)) {
            $startOfWeek = Carbon::parse($this->selectedDate)->startOfWeek(Carbon::MONDAY);
            $days = [];
            $recordMap = [];
            $maintenanceByDate = [];
            $verifierByDate = [];

            for ($i = 0; $i < 7; $i++) {
                $currentDay = $startOfWeek->copy()->addDays($i);
                $days[] = [
                    'name' => strtoupper($currentDay->format('D')),
                    'date' => $currentDay->format('M d'),
                    'full_date' => $currentDay->toDateString(),
                ];
            }

            foreach ($normalizedRecords as $record) {
                $initials = $this->formatInitials($record['maintenance_name']);
                $recordMap[$record['location_area_part_id']][$record['cleaning_date']][$record['shift']] = $initials ?: '✓';

                if ($record['maintenance_name'] !== '') {
                    $maintenanceByDate[$record['cleaning_date']][$record['maintenance_name']] = true;
                }

                if ($record['verifier_name'] !== '' && $record['verifier_status'] === 'YES') {
                    $verifierByDate[$record['cleaning_date']][$record['verifier_name']] = true;
                }
            }

            $data['days'] = $days;
            $data['recordMap'] = $recordMap;
            $data['maintenanceByDate'] = collect($maintenanceByDate)
                ->map(fn (array $names) => $this->formatInitialsList(array_keys($names)))
                ->all();
            $data['verifierByDate'] = collect($verifierByDate)
                ->map(fn (array $names) => $this->formatInitialsList(array_keys($names)))
                ->all();
            $data['periodLabel'] = 'Week of '.$startOfWeek->format('M d').' – '.$startOfWeek->copy()->endOfWeek(Carbon::SUNDAY)->format('M d, Y');

            return $data;
        }

        if ($this->periodType === 'weekly') {
            $refDate = $this->weeklyWeeks['w1']['start_date'] ?? ($this->weeklyStart ?: $this->selectedDate);
            $year = $refDate ? Carbon::parse($refDate)->year : now()->year;

            // Build weeks 1–52 for the year, grouped by month
            $weeks = [];
            for ($w = 1; $w <= 52; $w++) {
                $monday = Carbon::now()->setISODate($year, $w, 1);
                if ($monday->year > $year) break;
                $weeks[$w] = [
                    'label'       => 'W'.str_pad($w, 2, '0', STR_PAD_LEFT),
                    'month'       => (int) $monday->format('n'),
                    'month_label' => $monday->format('M'),
                ];
            }

            $recordMap = [];
            $maintenanceNames = [];
            $verifierNames = [];

            foreach ($normalizedRecords as $record) {
                $date    = Carbon::parse($record['cleaning_date']);
                $weekNum = (int) $date->isoWeek();
                if ($date->year < $year) $weekNum = 1;
                if ($date->year > $year) $weekNum = count($weeks);
                if (!isset($weeks[$weekNum])) continue;

                $initials = $this->formatInitials($record['maintenance_name']);
                $existing = $recordMap[$record['location_area_part_id']][$weekNum] ?? null;
                $recordMap[$record['location_area_part_id']][$weekNum] = $existing
                    ? $existing.($initials ? ' '.$initials : '')
                    : ($initials ?: '✓');

                if ($record['maintenance_name'] !== '') {
                    $maintenanceNames[$record['maintenance_name']] = true;
                }
                if ($record['verifier_name'] !== '' && $record['verifier_status'] === 'YES') {
                    $verifierNames[$record['verifier_name']] = true;
                }
            }

            $data['weeks']              = $weeks;
            $data['recordMap']          = $recordMap;
            $data['year']               = $year;
            $data['maintenanceInitials'] = $this->formatInitialsList(array_keys($maintenanceNames));
            $data['verifierInitials']   = $this->formatInitialsList(array_keys($verifierNames));
            $data['periodLabel']        = 'Year '.$year;

            return $data;
        }

        // Monthly — full year, 12 columns
        $refDate = $this->monthlyPeriods['m1']['start_date'] ?? ($this->monthlyStart ?: $this->selectedDate);
        $year = $refDate ? Carbon::parse($refDate)->year : now()->year;

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create($year, $m, 1)->format('M');
        }

        $recordMap = [];
        $maintenanceNames = [];
        $verifierNames = [];

        foreach ($normalizedRecords as $record) {
            $month    = (int) Carbon::parse($record['cleaning_date'])->format('n');
            $initials = $this->formatInitials($record['maintenance_name']);
            $existing = $recordMap[$record['location_area_part_id']][$month] ?? null;
            $recordMap[$record['location_area_part_id']][$month] = $existing
                ? $existing.($initials ? ' '.$initials : '')
                : ($initials ?: '✓');

            if ($record['maintenance_name'] !== '') {
                $maintenanceNames[$record['maintenance_name']] = true;
            }
            if ($record['verifier_name'] !== '' && $record['verifier_status'] === 'YES') {
                $verifierNames[$record['verifier_name']] = true;
            }
        }

        $data['months']             = $months;
        $data['recordMap']          = $recordMap;
        $data['year']               = $year;
        $data['maintenanceInitials'] = $this->formatInitialsList(array_keys($maintenanceNames));
        $data['verifierInitials']   = $this->formatInitialsList(array_keys($verifierNames));
        $data['periodLabel']        = 'Year '.$year;

        return $data;
    }

    private function formatInitialsList(array $names): string
    {
        $initials = [];

        foreach ($names as $name) {
            $formatted = $this->formatInitials($name);
            if ($formatted !== '') {
                $initials[] = $formatted;
            }
        }

        return implode(', ', array_values(array_unique($initials)));
    }

    private function formatInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $initials = [];

        foreach ($parts as $part) {
            if ($part !== '') {
                $initials[] = strtoupper(Str::substr($part, 0, 1)).'.';
            }
        }

        return implode('', $initials);
    }

    public function mount(): void
    {   
        $this->periodType = in_array(request('period'), ['daily', 'nightly', 'weekly', 'monthly'], true)
        ? request('period')
        : 'daily';
        $requestedDate = request('date');
        $this->selectedDate = $requestedDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $requestedDate)
            ? Carbon::parse($requestedDate)->toDateString()
            : Carbon::now('Asia/Manila')->toDateString();
        $this->calendarMonth = Carbon::parse($this->selectedDate)->startOfMonth()->toDateString();
        $today = Carbon::now('Asia/Manila');
        $this->weeklyStart = $today->copy()->startOfWeek(Carbon::SUNDAY)->toDateString();
        $this->weeklyMonth = Carbon::parse($this->weeklyStart)->startOfMonth()->toDateString();
        $this->monthlyStart = $today->copy()->startOfMonth()->toDateString();
        $requestedLocationId = request('location');
        if (is_numeric($requestedLocationId) && (int) $requestedLocationId > 0) {
            $this->selectedLocationId = (int) $requestedLocationId;
        }
        $requestedLocationName = request('location_name');
        if (is_string($requestedLocationName) && trim($requestedLocationName) !== '') {
            $this->selectedLocation = trim($requestedLocationName);
        }
        $this->buildWeekDates();
        $this->buildWeeklyWeeks();
        $this->buildMonthlyPeriods();
        try {
            $this->hasProofColumn = Schema::hasColumn('records', 'proof');
        } catch (\Throwable) {
            $this->hasProofColumn = false;
        }
        $this->loadLocations();
        $this->loadAreaParts();
        $this->loadExistingSlots();
    }

    public function updatedPeriodType(): void
    {
        $this->selectedSlots = [];
        $this->showDailyChecklist = false;

        // Change this check to include nightly
        if (in_array($this->periodType, ['daily', 'nightly']) && $this->selectedDate === '') {
            $this->selectedDate = Carbon::now('Asia/Manila')->toDateString();
        }

        // Change this check to include nightly
        if (in_array($this->periodType, ['daily', 'nightly'])) {
            $this->calendarMonth = Carbon::parse($this->selectedDate)->startOfMonth()->toDateString();
        } elseif ($this->periodType === 'weekly') {
            $this->buildWeeklyWeeks();
        } elseif ($this->periodType === 'monthly') {
            $this->buildMonthlyPeriods();
        }

        $this->loadLocations();
        $this->loadAreaParts();
        $this->loadExistingSlots();
    }

    public function updatedSelectedDate(): void
    {
        if ($this->selectedDate !== '') {
            $this->calendarMonth = Carbon::parse($this->selectedDate)->startOfMonth()->toDateString();
        }
        $this->selectedSlots = [];
        $this->loadExistingSlots();
    }

    public function previousCalendarMonth(): void
    {
        $this->calendarMonth = Carbon::parse($this->calendarMonth)
            ->subMonthNoOverflow()
            ->startOfMonth()
            ->toDateString();
    }

    public function nextCalendarMonth(): void
    {
        $this->calendarMonth = Carbon::parse($this->calendarMonth)
            ->addMonthNoOverflow()
            ->startOfMonth()
            ->toDateString();
    }

    public function selectCalendarDate(string $date): void
    {
        try {
            $parsed = Carbon::parse($date)->toDateString();
        } catch (\Throwable) {
            return;
        }

        if ($parsed > Carbon::now('Asia/Manila')->toDateString()) {
            return;
        }

        $this->selectedDate = $parsed;
        $this->calendarMonth = Carbon::parse($parsed)->startOfMonth()->toDateString();
        $this->loadExistingSlots();
        $this->showDailyChecklist = true;
    }

    public function updatedSelectedLocation(string $value): void
    {
        $needle = trim($value);
        $matched = collect($this->locations)
            ->first(fn (array $location) => strcasecmp($location['name'], $needle) === 0
                || strcasecmp($location['display_name'], $needle) === 0);

        $this->selectedLocationId = $matched['id'] ?? null;
        if ($matched !== null) {
            $this->selectedLocation = $matched['display_name'];
            // Location swapped while inside checklist — stay on current date, just reload data
        }
        // Do NOT reset showDailyChecklist here: mid-type no-match is transient.
        // Only clearSelectedLocation() should force the user back to the calendar.
        $this->loadAreaParts();
        $this->loadExistingSlots();
    }

    public function clearSelectedLocation(): void
    {
        $this->selectedLocation = '';
        $this->selectedLocationId = null;
        // Keep showDailyChecklist as-is so the user stays on the current date
        // and can immediately type a new location without going back to the calendar.
        $this->selectedSlots = [];
        $this->loadAreaParts();
        $this->loadExistingSlots();
    }

    public function showDailyCalendar(): void
    {
        if ($this->selectedLocationId !== null) {
            $matched = collect($this->locations)
                ->first(fn (array $location) => $location['id'] === $this->selectedLocationId);

            if ($matched !== null) {
                $this->selectedLocation = $matched['display_name'];
            }
        }

        $this->showDailyChecklist = false;
    }

    public function requestToggleWithProof(int $locationAreaPartId, string $dayKey, string $shift): void
    {
        if ($this->isSlotSelected($locationAreaPartId, $dayKey, $shift)) {
            return;
        }

        if ($this->isSlotLockedForFuture($dayKey)) {
            return;
        }

        $key = $this->slotKey($locationAreaPartId, $dayKey, $shift);
        if (! isset($this->slotRecordIds[$key])) {
            return;
        }

        $proofPath = $this->slotProofs[$key] ?? null;
        if (! is_string($proofPath) || trim($proofPath) === '') {
            return;
        }

        $this->verifyRecordId = (int) $this->slotRecordIds[$key];
        $this->verifyPreviewUrl = $this->buildProofPreviewUrl($proofPath);
        $this->verifyComment = '';
        $this->showVerifyModal = true;
    }

    #[On('proof-cancelled')]
    public function cancelProofCapture(): void
    {
        $this->clearPendingProof();
    }

    #[On('proof-captured')]
    public function confirmToggleWithProof(int $partId, string $dayKey, string $shift, string $imageData, ?string $comment = null): void
    {
        if ($this->isSlotLockedForFuture($dayKey)) {
            $this->clearPendingProof();
            return;
        }

        if (
            $this->pendingProofPartId !== $partId
            || $this->pendingProofDayKey !== $dayKey
            || $this->pendingProofShift !== $shift
        ) {
            $this->clearPendingProof();
            return;
        }

        $proofPath = $this->storeProofImage($imageData, $partId, $dayKey, $shift);
        if ($proofPath === null) {
            $this->dispatch('proof-capture-error', message: __('Unable to save proof photo. Please try again.'));
            $this->clearPendingProof();
            return;
        }

        $key = $this->slotKey($partId, $dayKey, $shift);
        $this->selectedSlots[$key] = true;
        $this->slotProofs[$key] = $proofPath;
        $this->slotComments[$key] = is_string($comment) ? trim($comment) : '';

        $this->clearPendingProof();
        $this->saveChecklist();
    }

    private function clearPendingProof(): void
    {
        $this->pendingProofPartId = null;
        $this->pendingProofDayKey = null;
        $this->pendingProofShift = null;
    }

    private function storeProofImage(string $imageData, int $partId, string $dayKey, string $shift): ?string
    {
        if (! str_starts_with($imageData, 'data:image/')) {
            return null;
        }

        if (! preg_match('/^data:image\/([a-zA-Z0-9]+);base64,(.*)$/', $imageData, $matches)) {
            return null;
        }

        $binary = base64_decode($matches[2], true);

        if ($binary === false || $binary === '') {
            return null;
        }

        $cleaningDate = $this->resolveCleaningDate($dayKey);
        if ($cleaningDate === null) {
            return null;
        }

        $safeShift = strtoupper($shift) === 'PM' ? 'PM' : 'AM';
        $filename = 'locationareapart'.$partId.'_'.$cleaningDate.'_'.$safeShift.'.jpg';
        $path = 'checklist-proofs/'.$filename;

        try {
            Storage::disk('public')->put($path, $binary);
            return $path;
        } catch (\Throwable) {
            return null;
        }
    }

    private function resolveCleaningDate(string $dayKey): ?string
{
    return match ($this->periodType) {
        'daily', 'nightly' => $this->selectedDate !== '' ? $this->selectedDate : null,
        'weekly'           => Carbon::now('Asia/Manila')->toDateString(),
        'monthly'          => Carbon::now('Asia/Manila')->toDateString(),
        default            => $this->weekDates[$dayKey] ?? null,
    };
}

    private function extractProofPathFromComments(?string $comments): ?string
    {
        if ($comments === null) {
            return null;
        }

        if (! str_starts_with($comments, 'proof:')) {
            return null;
        }

        return trim(substr($comments, 6)) ?: null;
    }

    public function isSlotSelected(int $locationAreaPartId, string $dayKey, string $shift): bool
    {
        return isset($this->selectedSlots[$this->slotKey($locationAreaPartId, $dayKey, $shift)]);
    }

    public function hasSlotProof(int $locationAreaPartId, string $dayKey, string $shift = 'AM'): bool
    {
        return isset($this->slotProofs[$this->slotKey($locationAreaPartId, $dayKey, $shift)]);
    }

    public function hasSlotRecord(int $locationAreaPartId, string $dayKey, string $shift = 'AM'): bool
    {
        return isset($this->slotRecordIds[$this->slotKey($locationAreaPartId, $dayKey, $shift)]);
    }

    public function openProofPreview(int $locationAreaPartId, string $dayKey, string $shift = 'AM'): void
    {
        $slotKey = $this->slotKey($locationAreaPartId, $dayKey, $shift);
        $path = $this->slotProofs[$slotKey] ?? null;
        if (! is_string($path) || trim($path) === '') {
            return;
        }

        $this->proofPreviewUrl = $this->buildProofPreviewUrl($path);

        $this->proofPreviewTitle = __('Proof Preview');
        $this->proofPreviewComment = $this->slotVerifierComments[$slotKey] ?? null;
        $this->showProofPreviewModal = true;
    }

    public function getProofMetadata(int $locationAreaPartId, string $dayKey, string $shift): array
    {
        $part = collect($this->areaParts)->first(fn (array $row) => (int) ($row['id'] ?? 0) === $locationAreaPartId);
        $cleaningDate = $this->resolveCleaningDate($dayKey);
        $dateLabel = $cleaningDate ? Carbon::parse($cleaningDate)->format('M d, Y') : '';
        $locationLabel = $part['location_display'] ?? '';

        if (! is_string($locationLabel) || trim($locationLabel) === '') {
            try {
                $locationRow = DB::table('location_area_parts as lap')
                    ->join('locations as l', 'l.id', '=', 'lap.location_id')
                    ->where('lap.id', $locationAreaPartId)
                    ->first(['l.name as location_name', 'l.floor as location_floor']);

                if ($locationRow) {
                    $locationLabel = trim($locationRow->location_name.($locationRow->location_floor ? ' ('.$locationRow->location_floor.')' : ''));
                }
            } catch (\Throwable) {
                // Keep empty/previous fallback.
            }
        }

        return [
            'partId' => $locationAreaPartId,
            'dayKey' => $dayKey,
            'shift' => $shift,
            'frequency' => $this->periodType,
            'dateLabel' => $dateLabel,
            'areaPart' => $part['display_name'] ?? $part['name'] ?? '',
            'location' => $locationLabel,
            'capturedBy' => Auth::user()?->name ?? '',
        ];
    }

    public function closeProofPreview(): void
    {
        $this->showProofPreviewModal = false;
        $this->proofPreviewUrl = null;
        $this->proofPreviewTitle = null;
        $this->proofPreviewComment = null;
    }

    public function closeVerifyModal(): void
    {
        $this->showVerifyModal = false;
        $this->verifyRecordId = null;
        $this->verifyPreviewUrl = null;
        $this->verifyComment = '';
    }

    public function confirmVerifyChecklist(): void
    {
        if ($this->verifyRecordId === null) {
            return;
        }

        $recordId = $this->verifyRecordId;
        $verifierComment = trim($this->verifyComment) !== '' ? trim($this->verifyComment) : null;
        $slotKey = collect($this->slotRecordIds)
            ->search(fn ($existingRecordId) => (int) $existingRecordId === $recordId);

        try {
            DB::table('records')
                ->where('id', $recordId)
                ->update([
                    'verifier_status' => 'YES',
                    'verifier_name' => Auth::user()?->name,
                    'verifier_comments' => $verifierComment,
                ]);
        } catch (\Throwable) {
            // Keep verify page usable even if DB update fails.
        }

        if (is_string($slotKey) && $slotKey !== '') {
            $this->selectedSlots[$slotKey] = true;

            if ($verifierComment !== null) {
                $this->slotVerifierComments[$slotKey] = $verifierComment;
            } else {
                unset($this->slotVerifierComments[$slotKey]);
            }
        }

        $this->closeVerifyModal();
    }

    private function buildProofPreviewUrl(string $path): string
    {
        $normalizedPath = ltrim(trim($path), '/');
        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, 8);
        }

        try {
            if (Storage::disk('public')->exists($normalizedPath)) {
                $raw = Storage::disk('public')->get($normalizedPath);
                $mime = Storage::disk('public')->mimeType($normalizedPath) ?: 'image/jpeg';
                return 'data:'.$mime.';base64,'.base64_encode($raw);
            }
        } catch (\Throwable) {
            // fallback url below
        }

        return asset('storage/'.$normalizedPath);
    }

    private function loadAreaParts(): void
    {
        if (! in_array($this->periodType, ['daily', 'nightly', 'weekly', 'monthly'], true) || $this->selectedLocationId === null) {
            $this->areaParts = [];
            return;
        }

        try {
            $query = DB::table('location_area_parts as lap')
                ->join('area_parts as ap', 'ap.id', '=', 'lap.area_part_id')
                ->join('locations as l', 'l.id', '=', 'lap.location_id')
                ->where('lap.frequency', $this->periodType)
                ->where('lap.location_id', $this->selectedLocationId)
                ->orderBy('l.name')
                ->orderBy('ap.name');

            $parts = $query->get([
                'lap.id as location_area_part_id',
                'ap.name as area_part_name',
                'l.name as location_name',
                'l.floor as location_floor',
                'l.id as location_id',
            ]);

            $this->areaParts = $parts->map(fn ($part) => [
                'id' => (int) $part->location_area_part_id,
                'name' => $part->area_part_name,
                'location' => $part->location_name,
                'location_id' => (int) $part->location_id,
                'location_floor' => $part->location_floor,
                'location_display' => trim($part->location_name.($part->location_floor ? ' ('.$part->location_floor.')' : '')),
                'display_name' => $part->area_part_name,
            ])->all();
        } catch (\Throwable) {
            $this->areaParts = [];
        }
    }

    private function loadLocations(): void
    {
        if (! in_array($this->periodType, ['daily', 'nightly', 'weekly', 'monthly'], true)) {
            $this->locations = [];
            $this->selectedLocation = '';
            $this->selectedLocationId = null;
            return;
        }

        try {
            $this->locations = DB::table('location_area_parts as lap')
                ->join('locations as l', 'l.id', '=', 'lap.location_id')
                ->where('lap.frequency', $this->periodType)
                ->distinct()
                ->orderBy('l.name')
                ->get(['l.id as id', 'l.name as name', 'l.floor as floor'])
                ->map(fn ($location) => [
                    'id' => (int) $location->id,
                    'name' => $location->name,
                    'floor' => $location->floor,
                    'display_name' => $location->name.' ('.$location->floor.')',
                ])
                ->all();

            $matchedById = $this->selectedLocationId !== null
                ? collect($this->locations)->first(fn (array $location) => $location['id'] === $this->selectedLocationId)
                : null;
            if ($matchedById !== null) {
                $this->selectedLocation = $matchedById['display_name'];
                return;
            }

            $needle = trim($this->selectedLocation);
            $matched = collect($this->locations)
                ->first(fn (array $location) => strcasecmp($location['name'], $needle) === 0
                    || strcasecmp($location['display_name'], $needle) === 0);

            if ($needle !== '' && $matched === null) {
                $this->selectedLocationId = null;
            } elseif ($matched !== null) {
                $this->selectedLocationId = $matched['id'];
            }
        } catch (\Throwable) {
            $this->locations = [];
            $this->selectedLocation = '';
            $this->selectedLocationId = null;
        }
    }

    private function buildWeekDates(): void
    {
        $today = Carbon::now('Asia/Manila');
        $monday = $today->copy()->startOfWeek(Carbon::MONDAY);

        foreach (array_keys($this->days) as $index => $dayKey) {
            $this->weekDates[$dayKey] = $monday->copy()->addDays($index)->toDateString();
        }
    }

    private function loadExistingSlots(): void
    {
        $this->selectedSlots = [];
        $this->slotProofs = [];
        $this->slotComments = [];
        $this->slotVerifierComments = [];
        $this->slotRecordIds = [];

        if (empty($this->areaParts)) {
            return;
        }

        try {
            $partIds = array_column($this->areaParts, 'id');
            $query = DB::table('records')
                ->whereIn('location_area_part_id', $partIds)
                ->where('period_type', $this->periodType)
                ->where('status', 'YES');

            if (in_array($this->periodType, ['daily', 'nightly'])) {
                $query->where('cleaning_date', $this->selectedDate);
            } elseif ($this->periodType === 'weekly') {
                $weekStart = $this->weeklyWeeks[array_key_first($this->weeklyWeeks)]['start_date'] ?? null;
                $weekEnd   = $this->weeklyWeeks[array_key_last($this->weeklyWeeks)]['end_date'] ?? null;
                if ($weekStart && $weekEnd) {
                    $query->whereBetween('cleaning_date', [$weekStart, $weekEnd]);
                }
            } elseif ($this->periodType === 'monthly') {
                $monthStart = $this->monthlyPeriods[array_key_first($this->monthlyPeriods)]['start_date'] ?? null;
                $monthEnd   = $this->monthlyPeriods[array_key_last($this->monthlyPeriods)]['end_date'] ?? null;
                if ($monthStart && $monthEnd) {
                    $query->whereBetween('cleaning_date', [$monthStart, $monthEnd]);
                }
            }

            $records = $query->get([
                'id',
                'location_area_part_id',
                'cleaning_date',
                'shift',
                'proof',
                'maintenance_comments',
                'verifier_status',
                'verifier_comments',
            ]);

            foreach ($records as $record) {
                $dayKey = match ($this->periodType) {
                    'daily', 'nightly' => 'selected',
                    'weekly'           => $this->weekKeyFromDate(Carbon::parse($record->cleaning_date)),
                    'monthly'          => $this->monthKeyFromDate(Carbon::parse($record->cleaning_date)),
                    default            => strtolower(Carbon::parse($record->cleaning_date)->format('D')),
                };

                if ($dayKey === null) {
                    continue;
                }

                $effectiveShift = is_string($record->shift) && in_array($record->shift, $this->shifts, true)
                    ? $record->shift
                    : 'AM';

                $key = $this->slotKey((int) $record->location_area_part_id, $dayKey, $effectiveShift);

                $this->slotRecordIds[$key] = (int) $record->id;

                if (($record->verifier_status ?? null) === 'YES') {
                    $this->selectedSlots[$key] = true;
                }

                // proof column exists directly on table
                $proofPath = is_string($record->proof ?? null) && $record->proof !== ''
                    ? $record->proof
                    : $this->extractProofPathFromComments($record->maintenance_comments ?? null);

                if (is_string($proofPath) && $proofPath !== '') {
                    $this->slotProofs[$key] = $proofPath;
                }

                if (is_string($record->maintenance_comments ?? null) && trim($record->maintenance_comments) !== '') {
                    $this->slotComments[$key] = trim($record->maintenance_comments);
                }

                if (is_string($record->verifier_comments ?? null) && trim($record->verifier_comments) !== '') {
                    $this->slotVerifierComments[$key] = trim($record->verifier_comments);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('loadExistingSlots error: ' . $e->getMessage());
        }
    }

    private function saveChecklist(): void
    {
        if (empty($this->areaParts)) {
            return;
        }

        try {
            $partIds     = array_column($this->areaParts, 'id');
            $deleteQuery = DB::table('records')
                ->whereIn('location_area_part_id', $partIds)
                ->where('period_type', $this->periodType);

            if ($this->periodType === 'daily') {
                $deleteQuery->where('cleaning_date', $this->selectedDate);
            } elseif ($this->periodType === 'weekly') {
                $weekStart = $this->weeklyWeeks[array_key_first($this->weeklyWeeks)]['start_date'] ?? null;
                $weekEnd   = $this->weeklyWeeks[array_key_last($this->weeklyWeeks)]['end_date'] ?? null;
                if ($weekStart && $weekEnd) {
                    $deleteQuery->whereBetween('cleaning_date', [$weekStart, $weekEnd]);
                }
            } elseif ($this->periodType === 'monthly') {
                $monthStart = $this->monthlyPeriods[array_key_first($this->monthlyPeriods)]['start_date'] ?? null;
                $monthEnd   = $this->monthlyPeriods[array_key_last($this->monthlyPeriods)]['end_date'] ?? null;
                if ($monthStart && $monthEnd) {
                    $deleteQuery->whereBetween('cleaning_date', [$monthStart, $monthEnd]);
                }
            }

            $deleteQuery->delete();

            foreach (array_keys($this->selectedSlots) as $key) {
                [$partId, $dayKey, $shift] = explode('|', $key);

                if (! in_array($shift, $this->shifts, true)) {
                    continue;
                }

                $cleaningDate = match ($this->periodType) {
        'daily'   => $this->selectedDate ?: null,
        'weekly'  => Carbon::now('Asia/Manila')->toDateString(),
        'monthly' => Carbon::now('Asia/Manila')->toDateString(),
        default   => $this->weekDates[$dayKey] ?? null,
    };

                if ($cleaningDate === null) {
                    continue;
                }

                $proofPath    = $this->slotProofs[$key] ?? null;
                $commentValue = $this->slotComments[$key] ?? null;

                DB::table('records')->insert([
                    'location_area_part_id' => (int) $partId,
                    'cleaning_date'         => $cleaningDate,
                    'period_type'           => $this->periodType,
                    'shift'                 => $shift,
                    'status'                => 'YES',
                    'remarks'               => 'Checked',
                    'proof'                 => $proofPath,
                    'maintenance_name'      => Auth::user()?->name,
                    'maintenance_comments'  => $commentValue,
                    'verifier_name'         => null,
                    'verifier_status'       => 'NO',
                    'verifier_comments'     => null,
                ]);
            }

            $this->loadExistingSlots();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('saveChecklist error: ' . $e->getMessage());
        }
    }

    private function slotKey(int $partId, string $dayKey, string $shift): string
    {
        return $partId.'|'.$dayKey.'|'.$shift;
    }

    private function isSlotLockedForFuture(string $dayKey): bool
    {
        $today = Carbon::now('Asia/Manila')->toDateString();
        $currentMonthStart = Carbon::now('Asia/Manila')->startOfMonth()->toDateString();

        return match ($this->periodType) {
            'daily', 'nightly' => $this->selectedDate > $today,
            'weekly' => ($this->weeklyWeeks[$dayKey]['start_date'] ?? $today) > $today,
            'monthly' => (
                ($this->monthlyPeriods[$dayKey]['start_date'] ?? $today) > $today
                || ($this->monthlyPeriods[$dayKey]['start_date'] ?? $today) < $currentMonthStart
            ),
            default => ($this->weekDates[$dayKey] ?? $today) > $today,
        };
    }

    private function buildWeeklyWeeks(): void
    {
        $today = Carbon::now('Asia/Manila');
        $currentWeekStart = $today->copy()->startOfWeek(Carbon::SUNDAY);
        $weekStart = Carbon::parse($this->weeklyStart)->startOfWeek(Carbon::SUNDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SATURDAY);
        $this->weeklyMonth = $weekStart->copy()->startOfMonth()->toDateString();

        $this->weeklyWeeks = [
            'w1' => [
                'label' => $this->formatWeekLabel($weekStart, $weekEnd),
                'start_date' => $weekStart->toDateString(),
                'end_date' => $weekEnd->toDateString(),
                'is_current' => $weekStart->isSameDay($currentWeekStart),
            ],
        ];
    }

    public function previousWeeklyPeriod(): void
    {
        $this->weeklyStart = Carbon::parse($this->weeklyStart)->subWeek()->startOfWeek(Carbon::SUNDAY)->toDateString();
        $this->selectedSlots = [];
        $this->buildWeeklyWeeks();
        $this->loadExistingSlots();
    }

    public function nextWeeklyPeriod(): void
    {
        $this->weeklyStart = Carbon::parse($this->weeklyStart)->addWeek()->startOfWeek(Carbon::SUNDAY)->toDateString();
        $this->selectedSlots = [];
        $this->buildWeeklyWeeks();
        $this->loadExistingSlots();
    }

    private function weekKeyFromDate(Carbon $date): ?string
    {
        foreach ($this->weeklyWeeks as $key => $week) {
            if ($date->betweenIncluded($week['start_date'], $week['end_date'])) {
                return $key;
            }
        }
        return null;
    }

    private function formatWeekLabel(Carbon $start, Carbon $end): string
    {
        if ($start->isSameMonth($end)) {
            return $start->format('F').' '.$start->day.'-'.$end->day;
        }

        return $start->format('F j').' - '.$end->format('F j');
    }

    private function buildMonthlyPeriods(): void
    {
        $today = Carbon::now('Asia/Manila');
        $currentMonthStart = $today->copy()->startOfMonth();
        $monthStart = Carbon::parse($this->monthlyStart)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $this->monthlyPeriods = [
            'm1' => [
                'label' => $monthStart->format('F Y'),
                'start_date' => $monthStart->toDateString(),
                'end_date' => $monthEnd->toDateString(),
                'is_current' => $monthStart->isSameDay($currentMonthStart),
            ],
        ];
    }

    public function previousMonthlyPeriod(): void
    {
        $this->monthlyStart = Carbon::parse($this->monthlyStart)->subMonthNoOverflow()->startOfMonth()->toDateString();
        $this->selectedSlots = [];
        $this->buildMonthlyPeriods();
        $this->loadExistingSlots();
    }

    public function nextMonthlyPeriod(): void
    {
        $this->monthlyStart = Carbon::parse($this->monthlyStart)->addMonthNoOverflow()->startOfMonth()->toDateString();
        $this->selectedSlots = [];
        $this->buildMonthlyPeriods();
        $this->loadExistingSlots();
    }

    private function monthKeyFromDate(Carbon $date): ?string
    {
        foreach ($this->monthlyPeriods as $key => $period) {
            if ($date->betweenIncluded($period['start_date'], $period['end_date'])) {
                return $key;
            }
        }
        return null;
    }

    private function biMonthStart(Carbon $date): Carbon
    {
        $month = $date->month;
        $startMonth = $month % 2 === 0 ? $month - 1 : $month;

        return $date->copy()->month($startMonth)->startOfMonth();
    }
}; ?>

<div>
    <section class="w-full">
        @include('partials.checklist-heading')

        <x-pages::maintenance.checklist.layout
            :wide="true"
            route-name="Maintenance.checklist.verify"
            :locationId="$selectedLocationId"
            :locationName="$selectedLocation"
            :selectedPeriod="$periodType">
            <div class="space-y-4">
                @php
                    $periodLabel = match ($periodType) {
                        'nightly' => __('Nightly'), // Add this line
                        'weekly' => __('Weekly'),
                        'monthly' => __('Monthly'),
                        default => __('Daily'),
                    };
                    $periodContext = match ($periodType) {
                        'weekly' => ($weeklyWeeks['w1']['label'] ?? __('Current Week')),
                        'monthly' => ($monthlyPeriods['m1']['label'] ?? __('Current Month')),
                        default => \Carbon\Carbon::parse($selectedDate)->format('M d, Y'),
                    };
                    $sectionLabel = __('Verify Checklist');
                    $checklistUrl = route('Maintenance.checklist.verify', array_filter([
                        'period' => $periodType,
                        'location' => $selectedLocationId,
                        'location_name' => $selectedLocation,
                        'date' => $periodType === 'daily' ? $selectedDate : null,
                    ], fn ($value) => $value !== null && $value !== ''));
                    $periodUrl = route('Maintenance.checklist.verify', array_filter([
                        'period' => $periodType,
                        'location' => $selectedLocationId,
                        'location_name' => $selectedLocation,
                        'date' => $periodType === 'daily' ? $selectedDate : null,
                    ], fn ($value) => $value !== null && $value !== ''));
                @endphp

                <div class="flex items-start justify-between gap-4 max-md:flex-col">
                    <div class="min-w-0 flex-1 mt-1">
                        <flux:breadcrumbs>
                            @if ($periodType === 'daily' && $showDailyChecklist)
                                <flux:breadcrumbs.item href="#" wire:click.prevent="showDailyCalendar">{{ $sectionLabel }}</flux:breadcrumbs.item>
                            @else
                                <flux:breadcrumbs.item href="{{ $checklistUrl }}" wire:navigate>{{ $sectionLabel }}</flux:breadcrumbs.item>
                            @endif
                            @if ($periodType === 'daily' && $showDailyChecklist)
                                <flux:breadcrumbs.item href="#" wire:click.prevent="showDailyCalendar">{{ $periodLabel }}</flux:breadcrumbs.item>
                            @else
                                <flux:breadcrumbs.item href="{{ $periodUrl }}" wire:navigate>{{ $periodLabel }}</flux:breadcrumbs.item>
                            @endif
                            @if ($periodType === 'daily' && $showDailyChecklist)
                                <flux:breadcrumbs.item href="#" wire:click.prevent="showDailyCalendar">{{ $periodContext }}</flux:breadcrumbs.item>
                            @else
                                <flux:breadcrumbs.item>{{ $periodContext }}</flux:breadcrumbs.item>
                            @endif
                        </flux:breadcrumbs>
                    </div>

                    <div class="space-y-2 md:ms-auto md:w-[420px] md:shrink-0">
                        <div class="flex items-center gap-2">
                            <input
                                id="selectedLocation"
                                type="text"
                                list="location-options"
                                wire:model.live="selectedLocation"
                                placeholder="{{ __('Search location...') }}"
                                class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                            />
                            @if ($selectedLocation !== '')
                                <button
                                    type="button"
                                    wire:click="clearSelectedLocation"
                                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-300"
                                    aria-label="{{ __('Clear location') }}">
                                    &times;
                                </button>
                            @endif
                        </div>
                        <datalist id="location-options">
                            @foreach ($locations as $location)
                                <option value="{{ $location['display_name'] }}"></option>
                            @endforeach
                        </datalist>

                        <div class="flex justify-end pt-1">
                            <button
                                type="button"
                                wire:click="exportToPdf"
                                @if (!$selectedLocationId || ($periodType === 'daily' && !$showDailyChecklist))
                                    disabled
                                @endif
                                style="
                                    display: inline-flex;
                                    width: 100%;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 0.5rem;
                                    border-radius: 0.375rem;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.875rem;
                                    font-weight: 500;
                                    {{ $selectedLocationId && ($periodType !== 'daily' || $showDailyChecklist)
                                        ? 'background-color: #2563eb; color: white; border: 1px solid #1e40af;'
                                        : 'background-color: #d1d5db; color: #374151; border: 1px solid #9ca3af; cursor: not-allowed; opacity: 0.5;'
                                    }}
                                "
                                onmouseover="{{ $selectedLocationId && ($periodType !== 'daily' || $showDailyChecklist) ? 'this.style.backgroundColor=\'#1d4ed8\'' : '' }}"
                                onmouseout="{{ $selectedLocationId && ($periodType !== 'daily' || $showDailyChecklist) ? 'this.style.backgroundColor=\'#2563eb\'' : '' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <span>{{ __('Export PDF') }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if (in_array($periodType, ['daily', 'nightly']) && ! $showDailyChecklist)
                    @php
                        $calendarBase = \Carbon\Carbon::parse($calendarMonth)->startOfMonth();
                        $today = \Carbon\Carbon::now('Asia/Manila')->toDateString();
                        $weekdayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                        $firstVisibleDate = $calendarBase->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                        $cellCount = 42;
                    @endphp
                    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

                        {{-- Calendar Header --}}
                        <div class="flex items-center justify-between px-5 py-4" style="background: linear-gradient(135deg, #1e3a5f 0%, #097b86 100%);">
                            <button type="button" wire:click="previousCalendarMonth"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                    aria-label="{{ __('Previous month') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="text-center">
                                <p class="mb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/55">
                                    {{ $periodType === 'nightly' ? 'Nightly' : 'Daily' }} — Pick a Date
                                </p>
                                <p class="text-lg font-bold leading-none text-white">
                                    {{ $calendarBase->format('F') }}
                                    <span class="font-normal text-white/60">{{ $calendarBase->format('Y') }}</span>
                                </p>
                            </div>
                            <button type="button" wire:click="nextCalendarMonth"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                    aria-label="{{ __('Next month') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Day-of-week headers --}}
                        <div class="grid border-b border-zinc-100 dark:border-zinc-700/50" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                            @foreach ($weekdayHeaders as $weekdayHeader)
                                <div class="py-2 text-center text-[10px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                    {{ $weekdayHeader }}
                                </div>
                            @endforeach
                        </div>

                        {{-- Day grid --}}
                        <div class="grid gap-1 p-3" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                            @for ($cell = 0; $cell < $cellCount; $cell++)
                                @php
                                    $cellDateObj = $firstVisibleDate->copy()->addDays($cell);
                                    $cellDate    = $cellDateObj->toDateString();
                                    $dayNumber   = $cellDateObj->day;
                                    $isCurrentMonth = $cellDateObj->month === $calendarBase->month;
                                    $isSelected  = $cellDate === $selectedDate;
                                    $isToday     = $cellDate === $today;
                                    $isFuture    = $cellDate > $today;
                                @endphp
                                <button type="button"
                                        wire:click="selectCalendarDate('{{ $cellDate }}')"
                                        @disabled($isFuture)
                                        class="relative flex aspect-square items-center justify-center rounded-xl text-sm font-semibold transition-all
                                            {{ $isSelected ? 'text-white shadow-md' : '' }}
                                            {{ $isToday && ! $isSelected ? 'ring-2 ring-offset-1' : '' }}
                                            {{ ! $isSelected && ! $isFuture && $isCurrentMonth ? 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-700/50' : '' }}
                                            {{ ! $isCurrentMonth && ! $isFuture ? 'text-zinc-300 hover:bg-zinc-50 dark:text-zinc-600 dark:hover:bg-zinc-800' : '' }}
                                            {{ $isFuture ? 'cursor-not-allowed opacity-30' : '' }}"
                                        style="{{ $isSelected ? 'background: linear-gradient(135deg, #1e3a5f, #097b86);' : '' }}">
                                    {{ $dayNumber }}
                                    @if ($isToday && ! $isSelected)
                                        <span class="absolute bottom-1 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full" style="background-color:#097b86;"></span>
                                    @endif
                                </button>
                            @endfor
                        </div>

                        {{-- Selected date footer --}}
                        @if ($selectedDate)
                            <div class="border-t border-zinc-100 px-4 py-2 text-center text-[11px] font-medium text-zinc-400 dark:border-zinc-700/50 dark:text-zinc-500">
                                Selected:
                                <span class="font-semibold text-zinc-600 dark:text-zinc-300">
                                    {{ \Carbon\Carbon::parse($selectedDate)->format('l, F d Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($selectedLocationId !== null && in_array($periodType, ['weekly', 'monthly'], true))
                    @php
                        $dayColumns = match ($periodType) {
                            'weekly' => $weeklyWeeks,
                            'monthly' => $monthlyPeriods,
                            default => $days,
                        };
                        $periodShifts = $periodType === 'daily' ? $shifts : ['AM'];
                        $totalColumns = 1 + (count($dayColumns) * count($periodShifts));
                        $activeLabel = $periodType === 'weekly'
                            ? ($weeklyWeeks['w1']['label'] ?? __('Current Week'))
                            : ($monthlyPeriods['m1']['label'] ?? __('Current Month'));
                        $activeDate = $periodType === 'weekly'
                            ? ($weeklyWeeks['w1']['start_date'] ?? now('Asia/Manila')->toDateString())
                            : ($monthlyPeriods['m1']['start_date'] ?? now('Asia/Manila')->toDateString());
                    @endphp
                    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 mb-3">
                        <div class="flex items-center justify-between px-5 py-4" style="background: linear-gradient(135deg, #1e3a5f 0%, #097b86 100%);">
                            <button type="button"
                                    wire:click="{{ $periodType === 'weekly' ? 'previousWeeklyPeriod' : 'previousMonthlyPeriod' }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                    aria-label="{{ __('Previous period') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="text-center">
                                <p class="mb-0.5 text-[10px] font-bold uppercase tracking-widest text-white/55">
                                    {{ $periodType === 'weekly' ? 'Weekly Checklist' : 'Monthly Checklist' }}
                                </p>
                                <p class="text-lg font-bold leading-none text-white">
                                    {{ \Carbon\Carbon::parse($activeDate)->format('F') }}
                                    <span class="font-normal text-white/60">{{ \Carbon\Carbon::parse($activeDate)->format('Y') }}</span>
                                </p>
                                <p class="mt-1 text-xs font-medium text-white/70">{{ $activeLabel }}</p>
                            </div>
                            <button type="button"
                                    wire:click="{{ $periodType === 'weekly' ? 'nextWeeklyPeriod' : 'nextMonthlyPeriod' }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white transition-colors hover:bg-white/20"
                                    aria-label="{{ __('Next period') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                        <table class="min-w-full border-collapse text-sm">
                            <thead class="bg-zinc-100 dark:bg-zinc-800">
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-2 text-left dark:border-zinc-700">{{ __('Area Part') }}</th>
                                    @foreach ($dayColumns as $dayKey => $dayName)
                                        <th colspan="2" class="border border-zinc-200 px-3 py-2 text-center dark:border-zinc-700">
                                            @if ($periodType === 'weekly')
                                                <div class="font-semibold">
                                                    {{ $dayName['label'] }}
                                                    @if (! empty($dayName['is_current']))
                                                        <span class="ml-1 inline-block h-2 w-2 rounded-full bg-sky-500 align-middle"></span>
                                                    @endif
                                                </div>
                                            @elseif ($periodType === 'monthly')
                                                <div class="font-semibold">
                                                    {{ $dayName['label'] }}
                                                    @if (! empty($dayName['is_current']))
                                                        <span class="ml-1 inline-block h-2 w-2 rounded-full bg-sky-500 align-middle"></span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="font-semibold">{{ $dayName }}</div>
                                                <div class="text-xs text-zinc-500">{{ \Carbon\Carbon::parse($weekDates[$dayKey])->format('M d, Y') }}</div>
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                    @foreach (array_keys($dayColumns) as $dayKey)
                                        @if ($periodType === 'daily')
                                            <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-orange-600 dark:border-zinc-700 dark:text-orange-400">AM</th>
                                            <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-sky-600 dark:border-zinc-700 dark:text-sky-400">PM</th>
                                        @else
                                            <th class="border border-zinc-200 px-2 py-1 text-center dark:border-zinc-700">{{ __('Check') }}</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($areaParts as $part)
                                    <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                        <td class="border border-zinc-200 px-4 py-2 font-medium dark:border-zinc-700">
                                            <div class="flex items-center justify-between gap-2">
                                                <span>{{ $part['display_name'] }}</span>
                                                @php
                                                    $previewDayKey = array_key_first($dayColumns);
                                                    $hasRecordPreview = $previewDayKey !== null ? $this->hasSlotRecord($part['id'], $previewDayKey, 'AM') : false;
                                                    $isVerifiedPreview = $previewDayKey !== null ? $this->isSlotSelected($part['id'], $previewDayKey, 'AM') : false;
                                                @endphp
                                                @if ($hasRecordPreview)
                                                    @if ($isVerifiedPreview)
                                                        <button
                                                            type="button"
                                                            wire:click="openProofPreview({{ $part['id'] }}, '{{ $previewDayKey }}', 'AM')"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-violet-300 bg-violet-50 text-violet-700 hover:bg-violet-100 dark:border-violet-700 dark:bg-violet-900/30 dark:text-violet-300 dark:hover:bg-violet-900/40"
                                                            aria-label="{{ __('Preview verified proof and comment') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button
                                                            type="button"
                                                            disabled
                                                            class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-md border border-violet-300 bg-violet-50 text-violet-600 dark:border-violet-700 dark:bg-violet-900/30 dark:text-violet-300"
                                                            aria-label="{{ __('Proof image available') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        @foreach (array_keys($dayColumns) as $dayKey)
                                            @foreach ($periodShifts as $shift)
                                                @php
                                                    $selected = $this->isSlotSelected($part['id'], $dayKey, $shift);
                                                    $locked = $this->isSlotLockedForFuture($dayKey);
                                                    $hasRecord = $this->hasSlotRecord($part['id'], $dayKey, $shift);
                                                @endphp
                                                <td class="border border-zinc-200 px-2 py-2 text-center dark:border-zinc-700 {{ ($locked || ! $hasRecord) ? 'opacity-50' : '' }} {{ $selected ? 'bg-violet-50/60 dark:bg-violet-900/20' : '' }}">
                                                    <input
                                                        type="checkbox"
                                                        wire:key="slot-week-{{ $part['id'] }}-{{ $dayKey }}-{{ $shift }}"
                                                        wire:click.prevent="requestToggleWithProof({{ $part['id'] }}, '{{ $dayKey }}', '{{ $shift }}')"
                                                        @disabled($locked || ! $hasRecord)
                                                        @if ($selected) tabindex="-1" aria-disabled="true" @endif
                                                        @checked($selected)
                                                        class="h-4 w-4 rounded border-zinc-300 text-violet-600 accent-violet-600 focus:ring-violet-500 {{ $selected ? 'pointer-events-none cursor-not-allowed' : 'pointer-events-auto cursor-pointer' }} disabled:cursor-not-allowed disabled:opacity-100 disabled:accent-violet-600 dark:border-zinc-600 dark:bg-zinc-900"
                                                    />
                                                </td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $totalColumns }}" class="border border-zinc-200 px-4 py-6 text-center text-zinc-500 dark:border-zinc-700">
                                            {{ __('No mapped checklist parts found. Add rows to location_area_parts with the selected frequency.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                @elseif ($selectedLocationId !== null && in_array($periodType, ['daily', 'nightly']) && $showDailyChecklist)
                    <div class="space-y-5 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="rounded-xl border border-zinc-200 bg-gradient-to-r from-zinc-50 to-white p-4 dark:border-zinc-700 dark:from-zinc-900 dark:to-zinc-800">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="space-y-1">
                                    <flux:heading size="lg">{{ __('Checklist') }}</flux:heading>
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                                        <span class="rounded-full bg-sky-100 px-2.5 py-0.5 font-medium text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                                            {{ $selectedLocation }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="max-h-[65vh] overflow-auto rounded-xl border border-zinc-200 shadow-sm dark:border-zinc-700">
                            <table class="min-w-full border-collapse text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-zinc-200 px-4 py-3 text-left font-semibold dark:border-zinc-700">{{ __('Area Part') }}</th>
                                        <th colspan="{{ $periodType === 'nightly' ? 1 : 2 }}" class="border border-zinc-200 px-3 py-3 text-center dark:border-zinc-700">
                                            <div class="font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('l') }}</div>
                                            <div class="text-xs text-zinc-500">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                            @if ($periodType === 'daily')
                                                <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-orange-600 dark:border-zinc-700 dark:text-orange-400">AM</th>
                                                <th class="border border-zinc-200 px-2 py-1 text-center font-semibold text-sky-600 dark:border-zinc-700 dark:text-sky-400">PM</th>
                                            @else
                                                <th class="border border-zinc-200 px-2 py-1 text-center dark:border-zinc-700">{{ __('Check') }}</th>
                                            @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($areaParts as $part)
                                        <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                            <td class="border border-zinc-200 px-4 py-3 font-medium dark:border-zinc-700">
                                                @php
                                                    $hasAmRecord = $this->hasSlotRecord($part['id'], 'selected', 'AM');
                                                    $hasPmRecord = $this->hasSlotRecord($part['id'], 'selected', 'PM');
                                                    $hasAmVerified = $this->isSlotSelected($part['id'], 'selected', 'AM');
                                                    $hasPmVerified = $this->isSlotSelected($part['id'], 'selected', 'PM');
                                                @endphp
                                                <div class="flex items-center justify-between gap-2">
                                                    <span>{{ $part['display_name'] }}</span>
                                                    <div class="flex items-center gap-1">
                                                        @if ($hasAmRecord)
                                                            @if ($hasAmVerified)
                                                                <button
                                                                    type="button"
                                                                    wire:click="openProofPreview({{ $part['id'] }}, 'selected', 'AM')"
                                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-orange-300 bg-orange-50 text-orange-700 hover:bg-orange-100 dark:border-orange-700 dark:bg-orange-900/30 dark:text-orange-300 dark:hover:bg-orange-900/40"
                                                                    aria-label="{{ __('Preview AM verified proof and comment') }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                                    </svg>
                                                                </button>
                                                            @else
                                                                <button
                                                                    type="button"
                                                                    disabled
                                                                    class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-md border border-orange-300 bg-orange-50 text-orange-600 dark:border-orange-700 dark:bg-orange-900/30 dark:text-orange-300"
                                                                    aria-label="{{ __('AM proof image available') }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                        <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                        <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        @endif
                                                        @if ($hasPmRecord)
                                                            @if ($hasPmVerified)
                                                                <button
                                                                    type="button"
                                                                    wire:click="openProofPreview({{ $part['id'] }}, 'selected', 'PM')"
                                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-sky-300 bg-sky-50 text-sky-700 hover:bg-sky-100 dark:border-sky-700 dark:bg-sky-900/30 dark:text-sky-300 dark:hover:bg-sky-900/40"
                                                                    aria-label="{{ __('Preview PM verified proof and comment') }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="#0284c7" stroke-width="2">
                                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                                    </svg>
                                                                </button>
                                                            @else
                                                                <button
                                                                    type="button"
                                                                    disabled
                                                                    class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-md border border-sky-300 bg-sky-50 text-sky-600 dark:border-sky-700 dark:bg-sky-900/30 dark:text-sky-300"
                                                                    aria-label="{{ __('PM proof image available') }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                        <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                        <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            @foreach ($shifts as $shift)
                                                @if($periodType === 'nightly' && $shift === 'AM') @continue @endif
                                                @php
                                                    $selected = $this->isSlotSelected($part['id'], 'selected', $shift);
                                                    $locked = $this->isSlotLockedForFuture('selected');
                                                    $hasRecord = $this->hasSlotRecord($part['id'], 'selected', $shift);
                                                @endphp
                                                <td
                                                    @if (! ($locked || ! $hasRecord || $selected))
                                                        wire:click="requestToggleWithProof({{ $part['id'] }}, 'selected', '{{ $shift }}')"
                                                    @endif
                                                    class="border border-zinc-200 px-2 py-3 text-center dark:border-zinc-700 {{ ($locked || ! $hasRecord) ? 'opacity-50' : 'cursor-pointer' }} {{ $selected ? 'bg-violet-50/60 dark:bg-violet-900/20' : '' }}"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        wire:key="slot-day-{{ $part['id'] }}-selected-{{ $shift }}"
                                                        @disabled($locked || ! $hasRecord)
                                                        @if ($selected) tabindex="-1" aria-disabled="true" @endif
                                                        @checked($selected)
                                                        class="pointer-events-none h-4 w-4 rounded border-zinc-300 text-violet-600 accent-violet-600 focus:ring-violet-500 disabled:cursor-not-allowed disabled:opacity-100 disabled:accent-violet-600 dark:border-zinc-600 dark:bg-zinc-900"
                                                    />
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="border border-zinc-200 px-4 py-8 text-center text-zinc-500 dark:border-zinc-700">
                                                {{ __('No mapped checklist parts found. Add rows to location_area_parts with daily frequency.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                @elseif ($selectedLocationId !== null && in_array($periodType, ['daily', 'nightly']))
                    <div class="rounded-xl border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                        {{ __('Select a date to load the daily checklist.') }}
                    </div>
                @else
                    <div class="rounded-xl border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                        {{ __('Select a frequency and area location to load the checklist table.') }}
                    </div>
                @endif

                @if ($showProofPreviewModal)
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-zinc-900">
                            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                    {{ $proofPreviewTitle ?? __('Proof Preview') }}
                                </h3>
                                <button
                                    type="button"
                                    wire:click="closeProofPreview"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                    aria-label="{{ __('Close preview') }}">
                                    &times;
                                </button>
                            </div>
                            <div class="p-4">
                                @if ($proofPreviewUrl)
                                    <div class="mx-auto w-full max-w-sm">
                                        <div class="aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                            <img src="{{ $proofPreviewUrl }}" alt="{{ __('Proof image') }}" class="h-full w-full object-contain">
                                        </div>
                                    </div>
                                    @if (filled($proofPreviewComment))
                                        <div class="mx-auto mt-3 w-full max-w-sm rounded-md border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/60 dark:text-zinc-200">
                                            <div class="mb-1 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Verifier Comment') }}</div>
                                            <div>{{ $proofPreviewComment }}</div>
                                        </div>
                                    @endif
                                @else
                                    <div class="rounded-md border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                                        {{ __('No proof image available for this item.') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if ($showVerifyModal)
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-zinc-900">
                            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Verify Checklist Record') }}</h3>
                                <button
                                    type="button"
                                    wire:click="closeVerifyModal"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-600 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                    aria-label="{{ __('Close verify modal') }}">
                                    &times;
                                </button>
                            </div>
                            <div class="space-y-4 p-4">
                                @if ($verifyPreviewUrl)
                                    <div class="mx-auto w-full max-w-sm">
                                        <div class="aspect-square w-full overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                            <img src="{{ $verifyPreviewUrl }}" alt="{{ __('Proof image') }}" class="h-full w-full object-contain">
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-md border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                                        {{ __('No proof image available for this record.') }}
                                    </div>
                                @endif

                                <div class="space-y-2">
                                    <label for="verifyComment" class="block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                                        {{ __('Verifier Comment') }}
                                    </label>
                                    <textarea
                                        id="verifyComment"
                                        wire:model.defer="verifyComment"
                                        rows="3"
                                        placeholder="{{ __('Add verification comment...') }}"
                                        class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="closeVerifyModal"
                                        class="rounded-md border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="confirmVerifyChecklist"
                                        class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                        {{ __('Confirm Verification') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </x-pages::maintenance.checklist.layout>
    </section>
</div>