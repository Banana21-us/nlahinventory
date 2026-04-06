<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public array $selectedSlots = [];
    public array $slotProofs = [];
    public array $slotComments = [];
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
    public array $days = [
        'mon' => 'Monday',
        'tue' => 'Tuesday',
        'wed' => 'Wednesday',
        'thu' => 'Thursday',
        'fri' => 'Friday',
    ];
    public array $shifts = ['AM', 'PM'];
    public array $weekDates = [];

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
        if (request()->boolean('prefill_location', false)) {
            $requestedLocationId = request('location');
            if (is_numeric($requestedLocationId) && (int) $requestedLocationId > 0) {
                $this->selectedLocationId = (int) $requestedLocationId;
            }
            $requestedLocationName = request('location_name');
            if (is_string($requestedLocationName) && trim($requestedLocationName) !== '') {
                $this->selectedLocation = trim($requestedLocationName);
            }
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
        if (in_array($this->periodType, ['daily', 'nightly'], true) && $this->selectedDate === '') {
            $this->selectedDate = Carbon::now('Asia/Manila')->toDateString();
        }
        if (in_array($this->periodType, ['daily', 'nightly'], true)) {
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

    public function selectLocationByName(string $name): void
    {
        $this->selectedLocation = $name;
        $this->updatedSelectedLocation($name);
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
            // Location changed while inside checklist — stay on current date, just reload data
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
        if ($this->isSlotLockedForFuture($dayKey)) {
            return;
        }

        $key = $this->slotKey($locationAreaPartId, $dayKey, $shift);

        // In requestToggleWithProof, replace the uncheck block:
        if (isset($this->selectedSlots[$key])) {
    unset($this->selectedSlots[$key]);
    unset($this->slotProofs[$key]);
    unset($this->slotComments[$key]);
    $this->clearPendingProof();

    $normalizedShift  = in_array(strtoupper($shift), ['AM', 'PM'], true) ? strtoupper($shift) : null;
    $normalizedPeriod = $this->periodType;
    try {
        DB::table('records')
            ->where('location_area_part_id', $locationAreaPartId)
            ->where('period_type', $normalizedPeriod)
            ->where('shift', $normalizedShift)
            ->where('status', 'YES')
            ->whereDate('cleaning_date', $this->selectedDate)
            ->delete();
    } catch (\Throwable) {}

    return;
}

        $this->pendingProofPartId = $locationAreaPartId;
        $this->pendingProofDayKey = $dayKey;
        $this->pendingProofShift = $shift;

        $part = collect($this->areaParts)->first(fn (array $row) => (int) ($row['id'] ?? 0) === $locationAreaPartId);
        $cleaningDate = $this->resolveCleaningDate($dayKey);
        $dateLabel = $cleaningDate ? Carbon::parse($cleaningDate)->format('M d, Y') : '';
        $timeLabel = Carbon::now('Asia/Manila')->format('H:i');
        $dateLabel = $dateLabel ? $dateLabel . ' | ' . $timeLabel : '';
        $locationLabel = $part['location_display'] ?? $this->selectedLocation;

        try {
            if (! is_string($locationLabel) || trim($locationLabel) === '') {
                $locationRow = DB::table('location_area_parts as lap')
                    ->join('locations as l', 'l.id', '=', 'lap.location_id')
                    ->where('lap.id', $locationAreaPartId)
                    ->first(['l.name as location_name', 'l.floor as location_floor']);

                if ($locationRow) {
                    $locationLabel = trim($locationRow->location_name.($locationRow->location_floor ? ' ('.$locationRow->location_floor.')' : ''));
                }
            }
        } catch (\Throwable) {
            // Keep selected location fallback if query fails.
        }

        $this->dispatch(
            'open-proof-camera',
            partId: $locationAreaPartId,
            dayKey: $dayKey,
            shift: $shift,
            frequency: $this->periodType,
            dateLabel: $dateLabel,
            areaPart: $part['display_name'] ?? $part['name'] ?? '',
            location: $locationLabel,
            capturedBy: Auth::user()?->name ?? ''
        );
    }

    public function setPendingProof(int $locationAreaPartId, string $dayKey, string $shift): void
    {
        if ($this->isSlotLockedForFuture($dayKey)) {
            return;
        }

        $this->pendingProofPartId = $locationAreaPartId;
        $this->pendingProofDayKey = $dayKey;
        $this->pendingProofShift = $shift;
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

    $proofPath = $this->storeProofImage($imageData, $partId, $dayKey, $shift);
    if ($proofPath === null) {
        $this->dispatch('proof-capture-error', message: __('Unable to save proof photo. Please try again.'));
        $this->clearPendingProof();
        return;
    }

    $cleaningDate = $this->resolveCleaningDate($dayKey);
    if ($cleaningDate === null) {
        $this->clearPendingProof();
        return;
    }

    $normalizedShift    = in_array(strtoupper($shift), ['AM', 'PM'], true) ? strtoupper($shift) : null;
    $normalizedPeriod   = $this->periodType;
    $commentValue       = is_string($comment) && trim($comment) !== '' ? trim($comment) : null;

    try {
        DB::table('records')
            ->where('location_area_part_id', $partId)
            ->where('period_type', $normalizedPeriod)
            ->where('shift', $normalizedShift)
            ->where('status', 'YES')
            ->whereDate('cleaning_date', $cleaningDate)
            ->delete();

        DB::table('records')->insert([
            'location_area_part_id' => $partId,
            'cleaning_date'         => $cleaningDate,
            'period_type'           => $normalizedPeriod,
            'shift'                 => $normalizedShift,
            'status'                => 'YES',
            'remarks'               => 'Checked',
            'proof'                 => $proofPath,
            'maintenance_name'      => Auth::user()?->name,
            'verifier_name'         => null,
            'verifier_status'       => 'NO',
            'verifier_comments'     => null,
            'maintenance_comments'  => $commentValue,
        ]);

    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('confirmToggleWithProof DB error: ' . $e->getMessage());
        $this->dispatch('proof-capture-error', message: __('Unable to save record. Please try again.'));
        $this->clearPendingProof();
        return;
    }

    $key = $this->slotKey($partId, $dayKey, $shift);
    $this->selectedSlots[$key] = true;
    $this->slotProofs[$key]    = $proofPath;
    $this->slotComments[$key]  = $commentValue ?? '';

    $this->clearPendingProof();
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

        $upperShift = strtoupper($shift ?? '');
        $safeShift = $upperShift === 'PM' ? 'PM' : ($upperShift === 'AM' ? 'AM' : null);
        $filename = $safeShift
            ? 'locationareapart'.$partId.'_'.$cleaningDate.'_'.$safeShift.'.jpg'
            : 'locationareapart'.$partId.'_'.$cleaningDate.'.jpg';
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
        $today = Carbon::now('Asia/Manila')->toDateString();

        return match ($this->periodType) {
            'daily', 'nightly' => $this->selectedDate !== '' ? $this->selectedDate : null,
            'weekly' => $today,
            'monthly' => $today,
            default => $this->weekDates[$dayKey] ?? null,
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

    public function openProofPreview(int $locationAreaPartId, string $dayKey, string $shift = 'AM'): void
    {
        $path = $this->slotProofs[$this->slotKey($locationAreaPartId, $dayKey, $shift)] ?? null;
        if (! is_string($path) || trim($path) === '') {
            return;
        }

        $normalizedPath = ltrim(trim($path), '/');
        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, 8);
        }

        try {
            if (Storage::disk('public')->exists($normalizedPath)) {
                $raw = Storage::disk('public')->get($normalizedPath);
                $mime = Storage::disk('public')->mimeType($normalizedPath) ?: 'image/jpeg';
                $this->proofPreviewUrl = 'data:'.$mime.';base64,'.base64_encode($raw);
            } else {
                $this->proofPreviewUrl = asset('storage/'.$normalizedPath);
            }
        } catch (\Throwable) {
            $this->proofPreviewUrl = asset('storage/'.$normalizedPath);
        }

        $this->proofPreviewTitle = __('Proof Preview');
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
    $this->slotProofs    = [];
    $this->slotComments  = [];

    if (empty($this->areaParts)) {
        return;
    }

    try {
        $partIds        = array_column($this->areaParts, 'id');
        $normalizedPeriod = $this->periodType;

        $query = DB::table('records')
            ->whereIn('location_area_part_id', $partIds)
            ->where('period_type', $normalizedPeriod)
            ->where('status', 'YES');

        if (in_array($this->periodType, ['daily', 'nightly'], true)) {
            $query->whereDate('cleaning_date', $this->selectedDate);
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

        $records = $query->get(['location_area_part_id', 'cleaning_date', 'shift', 'proof', 'maintenance_comments']);

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

            $key = $this->slotKey((int) $record->location_area_part_id, $dayKey, $record->shift);
            $this->selectedSlots[$key] = true;

            if (is_string($record->proof) && $record->proof !== '') {
                $this->slotProofs[$key] = $record->proof;
            }

            if (is_string($record->maintenance_comments) && trim($record->maintenance_comments) !== '') {
                $this->slotComments[$key] = trim($record->maintenance_comments);
            }
        }
    } catch (\Throwable) {
        // Keep UI usable.
    }
}

    private function saveChecklist(): void
{
    if (empty($this->areaParts)) {
        return;
    }

    $normalizedPeriod = $this->periodType;

    try {
        $partIds     = array_column($this->areaParts, 'id');
        $deleteQuery = DB::table('records')
            ->whereIn('location_area_part_id', $partIds)
            ->where('period_type', $normalizedPeriod);

        if (in_array($this->periodType, ['daily', 'nightly'], true)) {
            $deleteQuery->whereDate('cleaning_date', $this->selectedDate);
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

            $cleaningDate = match ($this->periodType) {
                'daily', 'nightly' => $this->selectedDate ?: null,
                'weekly', 'monthly' => Carbon::now('Asia/Manila')->toDateString(),
                default             => $this->weekDates[$dayKey] ?? null,
            };

            if ($cleaningDate === null) {
                continue;
            }

            DB::table('records')->insert([
                'location_area_part_id' => (int) $partId,
                'cleaning_date'         => $cleaningDate,
                'period_type'           => $normalizedPeriod,
                'shift'                 => in_array($shift, ['AM', 'PM'], true) ? $shift : null,
                'status'                => 'YES',
                'remarks'               => 'Checked',
                'proof'                 => $this->slotProofs[$key] ?? null,
                'maintenance_name'      => Auth::user()?->name,
                'verifier_name'         => null,
                'verifier_status'       => 'NO',
                'verifier_comments'     => null,
                'maintenance_comments'  => $this->slotComments[$key] ?? null,
            ]);
        }

        $this->loadExistingSlots();
    } catch (\Throwable) {
        //
    }
}

    private function saveSlot(int $partId, string $dayKey, string $shift): void
{
    if (! in_array($this->periodType, ['daily', 'nightly', 'weekly', 'monthly'], true)) {
        return;
    }

    $cleaningDate = match ($this->periodType) {
        'daily', 'nightly' => $this->selectedDate !== '' ? $this->selectedDate : null,
        'weekly'  => Carbon::now('Asia/Manila')->toDateString(),
        'monthly' => Carbon::now('Asia/Manila')->toDateString(),
        default   => $this->weekDates[$dayKey] ?? null,
    };

    if ($cleaningDate === null) {
        return;
    }

    $key          = $this->slotKey($partId, $dayKey, $shift);
    $proofPath    = $this->slotProofs[$key] ?? null;
    $commentValue = $this->slotComments[$key] ?? null;

    try {
        // Delete only THIS slot's existing record (same part + date + shift + period)
        $deleteQuery = DB::table('records')
            ->where('location_area_part_id', $partId)
            ->where('period_type', $this->periodType)
            ->where('shift', in_array($shift, ['AM', 'PM'], true) ? $shift : null)
            ->where('status', 'YES');

        if (in_array($this->periodType, ['daily', 'nightly'], true)) {
            $deleteQuery->whereDate('cleaning_datetime', $cleaningDate);
        } elseif ($this->periodType === 'weekly') {
            $weekStart = $this->weeklyWeeks[array_key_first($this->weeklyWeeks)]['start_date'] ?? null;
            $weekEnd   = $this->weeklyWeeks[array_key_last($this->weeklyWeeks)]['end_date'] ?? null;
            if ($weekStart && $weekEnd) {
                $deleteQuery->whereBetween('cleaning_datetime', [
                    Carbon::parse($weekStart)->startOfDay(),
                    Carbon::parse($weekEnd)->endOfDay(),
                ]);
            }
        } elseif ($this->periodType === 'monthly') {
            $monthStart = $this->monthlyPeriods[array_key_first($this->monthlyPeriods)]['start_date'] ?? null;
            $monthEnd   = $this->monthlyPeriods[array_key_last($this->monthlyPeriods)]['end_date'] ?? null;
            if ($monthStart && $monthEnd) {
                $deleteQuery->whereBetween('cleaning_datetime', [
                    Carbon::parse($monthStart)->startOfDay(),
                    Carbon::parse($monthEnd)->endOfDay(),
                ]);
            }
        }

        $deleteQuery->delete();

        $payload = [
            'location_area_part_id' => $partId,
            'cleaning_datetime'     => Carbon::now('Asia/Manila')->toDateTimeString(),
            'period_type'           => $this->periodType,
            'shift'                 => in_array($shift, ['AM', 'PM'], true) ? $shift : null,
            'status'                => 'YES',
            'remarks'               => 'Checked',
            'maintenance_name'      => Auth::user()?->name,
            'verifier_name'         => null,
            'verifier_status'       => 'NO',
            'verifier_comments'     => null,
            'maintenance_comments'  => $commentValue,
        ];

        if ($this->hasProofColumn) {
            $payload['proof'] = $proofPath;
        } elseif (is_string($proofPath) && $proofPath !== '') {
            $existingComment = is_string($commentValue) ? trim($commentValue) : '';
            $payload['maintenance_comments'] = $existingComment !== ''
                ? $existingComment . "\n" . 'proof:' . $proofPath
                : 'proof:' . $proofPath;
        }

        DB::table('records')->insert($payload);
    } catch (\Throwable) {
        // Silently ignore to avoid crashing the UI.
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

    <x-pages::Maintenance.checklist.layout
        :wide="true"
        route-name="Maintenance.checklist.check"
        :locationId="$selectedLocationId"
        :locationName="$selectedLocation"
        :selectedPeriod="$periodType"
    >
        <div class="space-y-4">
            @php
                $periodLabel = match ($periodType) {
                    'weekly' => __('Weekly'),
                    'monthly' => __('Monthly'),
                    'nightly' => __('Nightly'),
                    default => __('Daily'),
                };
                $periodContext = match ($periodType) {
                    'weekly' => ($weeklyWeeks['w1']['label'] ?? __('Current Week')),
                    'monthly' => ($monthlyPeriods['m1']['label'] ?? __('Current Month')),
                    default => \Carbon\Carbon::parse($selectedDate)->format('M d, Y'),
                };
                $sectionLabel = __('Maintenance Checklist');
                $checklistUrl = route('Maintenance.checklist.check', array_filter([
                    'period' => $periodType,
                    'location' => $selectedLocationId,
                    'location_name' => $selectedLocation,
                    'prefill_location' => $selectedLocationId ? 1 : null,
                    'date' => $periodType === 'daily' ? $selectedDate : null,
                ], fn ($value) => $value !== null && $value !== ''));
                $periodUrl = route('Maintenance.checklist.check', array_filter([
                    'period' => $periodType,
                    'location' => $selectedLocationId,
                    'location_name' => $selectedLocation,
                    'prefill_location' => $selectedLocationId ? 1 : null,
                    'date' => $periodType === 'daily' ? $selectedDate : null,
                ], fn ($value) => $value !== null && $value !== ''));
            @endphp
            <div class="flex flex-col gap-3">
                <div class="min-w-0">
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

                <div class="w-full overflow-hidden">
                    @php $locationChunks = array_chunk($locations, 9); @endphp
                    <div
                        x-data="{
                            page: 0,
                            total: {{ count($locationChunks) }},
                            touchStartX: 0,
                            prev() { if (this.page > 0) this.page--; },
                            next() { if (this.page < this.total - 1) this.page++; },
                            onTouchStart(e) { this.touchStartX = e.changedTouches[0].screenX; },
                            onTouchEnd(e) {
                                const dx = e.changedTouches[0].screenX - this.touchStartX;
                                if (dx < -40) this.next();
                                else if (dx > 40) this.prev();
                            },
                        }"
                        class="w-full"
                    >
                        {{-- Selected badge --}}
                        @if ($selectedLocation !== '')
                            <div class="mb-2 flex items-center gap-2">
                                <span class="flex-1 truncate rounded-lg bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">
                                    {{ $selectedLocation }}
                                </span>
                                <button
                                    type="button"
                                    wire:click="clearSelectedLocation"
                                    class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-zinc-300 bg-white text-zinc-500 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                                    aria-label="{{ __('Clear location') }}"
                                >&times;</button>
                            </div>
                        @endif

                        {{-- Grid pages --}}
                        <div
                            class="w-full overflow-hidden"
                            @touchstart.passive="onTouchStart($event)"
                            @touchend.passive="onTouchEnd($event)"
                        >
                            <div
                                class="flex transition-transform duration-300 ease-in-out"
                                :style="`transform: translateX(-${page * 100}%)`"
                            >
                                @foreach ($locationChunks as $chunk)
                                    <div class="grid w-full shrink-0 grid-cols-3 gap-2" style="min-width:100%">
                                        @foreach ($chunk as $location)
                                            <button
                                                type="button"
                                                wire:click="selectLocationByName('{{ addslashes($location['display_name']) }}')"
                                                class="flex flex-col items-center gap-1 rounded-xl border px-1 py-3 text-center text-xs font-medium transition
                                                    {{ $selectedLocationId === ($location['id'] ?? null)
                                                        ? 'border-sky-500 bg-sky-50 text-sky-700 dark:border-sky-500 dark:bg-sky-900/30 dark:text-sky-300'
                                                        : 'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                                            >
                                                
                                                <span class="line-clamp-2 leading-tight">{{ $location['display_name'] }}</span>
                                            </button>
                                        @endforeach
                                        {{-- Fill empty cells to maintain 3-col grid --}}
                                        @for ($i = count($chunk); $i < 9; $i++)
                                            @if ($i % 3 === 0 && $i !== 0) {{-- only pad last row --}} @endif
                                            <div></div>
                                        @endfor
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Pagination dots + arrows --}}
                        @if (count($locationChunks) > 1)
                            <div class="mt-2 flex items-center justify-between">
                                <button type="button" @click="prev" :disabled="page === 0"
                                    :class="page === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-zinc-100 dark:hover:bg-zinc-700'"
                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-zinc-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-800">
                                    <svg class="h-3.5 w-3.5 text-zinc-600 dark:text-zinc-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <div class="flex gap-1.5">
                                    @foreach ($locationChunks as $i => $_)
                                        <span
                                            :class="{{ $i }} === page ? 'bg-sky-500 w-4' : 'bg-zinc-300 dark:bg-zinc-600 w-1.5'"
                                            class="h-1.5 rounded-full transition-all duration-300"
                                        ></span>
                                    @endforeach
                                </div>
                                <button type="button" @click="next" :disabled="page >= total - 1"
                                    :class="page >= total - 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-zinc-100 dark:hover:bg-zinc-700'"
                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-zinc-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-800">
                                    <svg class="h-3.5 w-3.5 text-zinc-600 dark:text-zinc-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
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
                                    style="{{ $isSelected ? 'background: linear-gradient(135deg, #1e3a5f, #097b86);' : '' }}
                                           {{ $isToday && ! $isSelected ? 'ring-color: #097b86;' : '' }}">
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
                    $periodCardTitle = $periodType === 'weekly' ? __('Checklist Week') : __('Checklist Month');
                    $activeDate = $periodType === 'weekly'
                        ? ($weeklyWeeks['w1']['start_date'] ?? now('Asia/Manila')->toDateString())
                        : ($monthlyPeriods['m1']['start_date'] ?? now('Asia/Manila')->toDateString());
                @endphp
                {{-- Weekly / Monthly header card --}}
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
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold text-white" style="background-color:#097b86;">
                            {{ $selectedLocation }}
                        </span>
                        <button type="button"
                                id="openDailyCameraBtn"
                                data-frequency="{{ $periodType }}"
                                data-day-key="{{ $periodType === 'weekly' ? 'w1' : 'm1' }}"
                                data-date-label="{{ \Carbon\Carbon::parse($activeDate)->format('M d, Y') }}"
                                data-location="{{ $selectedLocation }}"
                                data-captured-by="{{ auth()->user()?->name ?? '' }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-semibold text-zinc-700 shadow-sm transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200"
                                aria-label="{{ __('Open camera') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" />
                                <path d="M10 8a3 3 0 100 6 3 3 0 000-6z" />
                            </svg>
                            Camera
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
                                                $hasProofPreview = $previewDayKey !== null
                                                    ? $this->hasSlotProof($part['id'], $previewDayKey, 'AM')
                                                    : false;
                                            @endphp
                                            @if ($hasProofPreview)
                                                <button
                                                    type="button"
                                                    wire:click="openProofPreview({{ $part['id'] }}, '{{ $previewDayKey }}', 'AM')"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                                    aria-label="{{ __('Preview proof image') }}"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                        <circle cx="16.5" cy="9" r="1.5"></circle>
                                                        <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    @foreach (array_keys($dayColumns) as $dayKey)
                                        @foreach ($periodShifts as $shift)
                                            @php
                                                $selected = $this->isSlotSelected($part['id'], $dayKey, $shift);
                                                $locked = $this->isSlotLockedForFuture($dayKey);
                                            @endphp
                                            <td
                                                class="border border-zinc-200 px-2 py-2 text-center dark:border-zinc-700 {{ $locked ? 'opacity-50' : '' }}"
                                            >
                                                <input
                                                    type="checkbox"
                                                    wire:key="slot-week-{{ $part['id'] }}-{{ $dayKey }}-{{ $shift }}"
                                                    disabled
                                                    @checked($selected)
                                                    class="h-4 w-4 cursor-not-allowed rounded border-zinc-300 text-sky-600 focus:ring-sky-500 disabled:cursor-not-allowed dark:border-zinc-600 dark:bg-zinc-900"
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
            @elseif ($selectedLocationId !== null && $periodType === 'nightly' && $showDailyChecklist)
                <div class="space-y-5 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="rounded-xl border border-indigo-200 bg-gradient-to-r from-indigo-50 to-white p-4 dark:border-indigo-700 dark:from-indigo-900/30 dark:to-zinc-800">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="space-y-1">
                                <flux:heading size="lg">{{ __('Nightly Checklist') }}</flux:heading>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                                    <span class="rounded-full bg-indigo-100 px-2.5 py-0.5 font-medium text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                                        {{ $selectedLocation }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    id="openDailyCameraBtn"
                                    data-frequency="nightly"
                                    data-day-key="selected"
                                    data-date-label="{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}"
                                    data-location="{{ $selectedLocation }}"
                                    data-captured-by="{{ auth()->user()?->name ?? '' }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-indigo-300 bg-white px-3 py-2 text-sm font-medium text-indigo-700 shadow-sm hover:bg-indigo-50 dark:border-indigo-700 dark:bg-zinc-900 dark:text-indigo-300 dark:hover:bg-indigo-900/30"
                                    aria-label="{{ __('Open camera') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" />
                                        <path d="M10 8a3 3 0 100 6 3 3 0 000-6z" />
                                    </svg>
                                    {{ __('Camera') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="max-h-[65vh] overflow-auto rounded-xl border border-zinc-200 shadow-sm dark:border-zinc-700">
                        <table class="min-w-full border-collapse text-sm">
                            <thead class="sticky top-0 z-10 bg-indigo-50 dark:bg-indigo-900/30">
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-3 text-left font-semibold dark:border-zinc-700">{{ __('Area Part') }}</th>
                                    <th class="border border-zinc-200 px-3 py-3 text-center dark:border-zinc-700">
                                        <div class="font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('l') }}</div>
                                        <div class="text-xs text-zinc-500">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</div>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                    <th class="border border-zinc-200 px-2 py-2 text-center font-semibold text-indigo-600 dark:border-zinc-700 dark:text-indigo-400">{{ __('Check') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($areaParts as $part)
                                    <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                        <td class="border border-zinc-200 px-4 py-3 font-medium dark:border-zinc-700">
                                            @php $hasNightProof = $this->hasSlotProof($part['id'], 'selected', 'PM'); @endphp
                                            <div class="flex items-center justify-between gap-2">
                                                <span>{{ $part['display_name'] }}</span>
                                                @if ($hasNightProof)
                                                    <button
                                                        type="button"
                                                        wire:click="openProofPreview({{ $part['id'] }}, 'selected', 'PM')"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-indigo-300 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:border-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50"
                                                        aria-label="{{ __('Preview proof image') }}"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                            <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                            <circle cx="16.5" cy="9" r="1.5"></circle>
                                                            <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        @php
                                            $selected = $this->isSlotSelected($part['id'], 'selected', 'PM');
                                            $locked   = $this->isSlotLockedForFuture('selected');
                                        @endphp
                                        <td class="border border-zinc-200 px-2 py-3 text-center dark:border-zinc-700 {{ $locked ? 'opacity-50' : '' }}">
                                            <input
                                                type="checkbox"
                                                wire:key="slot-night-{{ $part['id'] }}-selected-PM"
                                                disabled
                                                @checked($selected)
                                                class="h-4 w-4 cursor-not-allowed rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500 disabled:cursor-not-allowed dark:border-indigo-600 dark:bg-zinc-900"
                                            />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="border border-zinc-200 px-4 py-8 text-center text-zinc-500 dark:border-zinc-700">
                                            {{ __('No mapped checklist parts found. Add rows to location_area_parts with nightly frequency.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif ($selectedLocationId !== null && $periodType === 'daily' && $showDailyChecklist)
                <div class="space-y-5 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="rounded-xl border border-zinc-200 bg-gradient-to-r from-zinc-50 to-white p-4 dark:border-zinc-700 dark:from-zinc-900 dark:to-zinc-800">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="space-y-1">
                                <flux:heading size="lg">{{ $periodType === 'nightly' ? __('Nightly Checklist') : __('Daily Checklist') }}</flux:heading>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                                    <span class="rounded-full bg-sky-100 px-2.5 py-0.5 font-medium text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                                        {{ $selectedLocation }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    id="openDailyCameraBtn"
                                    data-frequency="daily"
                                    data-day-key="selected"
                                    data-date-label="{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}"
                                    data-location="{{ $selectedLocation }}"
                                    data-captured-by="{{ auth()->user()?->name ?? '' }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-medium text-zinc-700 shadow-sm hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                    aria-label="{{ __('Open camera') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" />
                                        <path d="M10 8a3 3 0 100 6 3 3 0 000-6z" />
                                    </svg>
                                    {{ __('Camera') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="max-h-[65vh] overflow-auto rounded-xl border border-zinc-200 shadow-sm dark:border-zinc-700">
                        <table class="min-w-full border-collapse text-sm">
                            <thead class="sticky top-0 z-10 bg-zinc-100 dark:bg-zinc-800">
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-3 text-left font-semibold dark:border-zinc-700">{{ __('Area Part') }}</th>
                                    <th colspan="2" class="border border-zinc-200 px-3 py-3 text-center dark:border-zinc-700">
                                        <div class="font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('l') }}</div>
                                        <div class="text-xs text-zinc-500">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</div>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                                    <th class="border border-zinc-200 px-2 py-2 text-center font-semibold text-orange-600 dark:border-zinc-700 dark:text-orange-400">AM</th>
                                    <th class="border border-zinc-200 px-2 py-2 text-center font-semibold text-sky-600 dark:border-zinc-700 dark:text-sky-400">PM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($areaParts as $part)
                                    <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                        <td class="border border-zinc-200 px-4 py-3 font-medium dark:border-zinc-700">
                                            @php
                                                $hasAmProof = $this->hasSlotProof($part['id'], 'selected', 'AM');
                                                $hasPmProof = $this->hasSlotProof($part['id'], 'selected', 'PM');
                                            @endphp
                                            <div class="flex items-center justify-between gap-2">
                                                <span>{{ $part['display_name'] }}</span>
                                                <div class="flex items-center gap-1">
                                                    @if ($hasAmProof)
                                                        <button
                                                            type="button"
                                                            wire:click="openProofPreview({{ $part['id'] }}, 'selected', 'AM')"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-orange-300 bg-orange-50 text-orange-600 hover:bg-orange-100 dark:border-orange-700 dark:bg-orange-900/30 dark:text-orange-300 dark:hover:bg-orange-900/50"
                                                            aria-label="{{ __('Preview AM proof image') }}"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                    @if ($hasPmProof)
                                                        <button
                                                            type="button"
                                                            wire:click="openProofPreview({{ $part['id'] }}, 'selected', 'PM')"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-sky-300 bg-sky-50 text-sky-600 hover:bg-sky-100 dark:border-sky-700 dark:bg-sky-900/30 dark:text-sky-300 dark:hover:bg-sky-900/50"
                                                            aria-label="{{ __('Preview PM proof image') }}"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                                <circle cx="16.5" cy="9" r="1.5"></circle>
                                                                <path d="M5.5 17l5-5 3.5 3.5 2.5-2.5 2.5 4"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        @foreach ($shifts as $shift)
                                            @php
                                                $selected = $this->isSlotSelected($part['id'], 'selected', $shift);
                                                $locked = $this->isSlotLockedForFuture('selected');
                                            @endphp
                                            <td
                                                class="border border-zinc-200 px-2 py-3 text-center dark:border-zinc-700 {{ $locked ? 'opacity-50' : '' }}"
                                            >
                                                <input
                                                    type="checkbox"
                                                    wire:key="slot-day-{{ $part['id'] }}-selected-{{ $shift }}"
                                                    disabled
                                                    @checked($selected)
                                                    class="h-4 w-4 cursor-not-allowed rounded border-zinc-300 text-sky-600 focus:ring-sky-500 disabled:cursor-not-allowed dark:border-zinc-600 dark:bg-zinc-900"
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
                                aria-label="{{ __('Close preview') }}"
                            >
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
                            @else
                                <div class="rounded-md border border-zinc-200 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-300">
                                    {{ __('No proof image available for this item.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-pages::Maintenance.checklist.layout>

</section>

@php
    $activePeriodKey = $periodType === 'weekly'
        ? 'w1'
        : ($periodType === 'monthly' ? 'm1' : 'selected');
@endphp
<x-checklist-proof-camera-modal 
    :area-parts="$areaParts"
    :selected-slots="$selectedSlots"
    :selected-location="$selectedLocation"
    :selected-date="$selectedDate"
    :period-type="$periodType"
    :active-period-key="$activePeriodKey"
/>
</div>
