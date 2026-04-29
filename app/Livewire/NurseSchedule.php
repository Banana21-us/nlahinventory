<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\NurseScheduleEntry;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NurseSchedule extends Component
{
    public string $selectedDate = '';

    public bool $toastShow = false;

    public string $toastMessage = '';

    public array $schedule = [];

    public array $previewData = [];

    public string $previewFrom = '';

    public string $previewTo = '';

    public function mount(): void
    {
        $this->selectedDate = now()->toDateString();
        $this->loadSchedule();
    }

    public function changeDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->loadSchedule();
    }

    public function loadSchedule(): void
    {
        $this->schedule = [];

        NurseScheduleEntry::with('employee')
            ->where('schedule_date', $this->selectedDate)
            ->orderBy('slot')
            ->get()
            ->each(function ($entry) {
                $name = $entry->employee
                    ? trim($entry->employee->first_name.' '.$entry->employee->last_name)
                    : $entry->custom_name;

                $this->schedule[$entry->section][$entry->period][] = [
                    'id' => $entry->id,
                    'employee_id' => $entry->employee_id,
                    'name' => $name,
                    'slot' => $entry->slot,
                ];
            });
    }

    // Sections that allow more than one nurse per period (section => max count)
    private const MULTI_NURSE = ['or' => 2];

    public function assignEmployee(int $employeeId, string $section, string $period): void
    {
        $employee = Employee::findOrFail($employeeId);
        $slot = $this->resolveSlot($section, $period);
        if ($slot === false) {
            $this->dispatch('show-toast', message: 'Maximum nurses already assigned for this slot.');
            return;
        }

        if ($slot === null) {
            NurseScheduleEntry::where('schedule_date', $this->selectedDate)
                ->where('section', $section)
                ->where('period', $period)
                ->delete();
        }

        NurseScheduleEntry::create([
            'schedule_date' => $this->selectedDate,
            'section' => $section,
            'period' => $period,
            'slot' => $slot,
            'employee_id' => $employeeId,
            'custom_name' => null,
        ]);

        $name = trim($employee->first_name.' '.$employee->last_name);
        $this->loadSchedule();
        $this->dispatch('show-toast', message: "{$name} assigned successfully.");
    }

    public function assignCustom(string $section, string $period, string $customName): void
    {
        $customName = trim($customName);
        if (empty($customName)) {
            return;
        }

        $slot = $this->resolveSlot($section, $period);
        if ($slot === false) {
            $this->dispatch('show-toast', message: 'Maximum nurses already assigned for this slot.');
            return;
        }

        if ($slot === null) {
            NurseScheduleEntry::where('schedule_date', $this->selectedDate)
                ->where('section', $section)
                ->where('period', $period)
                ->delete();
        }

        NurseScheduleEntry::create([
            'schedule_date' => $this->selectedDate,
            'section' => $section,
            'period' => $period,
            'slot' => $slot,
            'employee_id' => null,
            'custom_name' => $customName,
        ]);

        $this->loadSchedule();
        $this->dispatch('show-toast', message: "{$customName} assigned successfully.");
    }

    /**
     * Returns the slot value to use for a new entry.
     * null  → single-nurse section (caller should delete existing first)
     * int   → next available slot number for multi-nurse sections
     * false → section is full, reject the assignment
     */
    private function resolveSlot(string $section, string $period): int|null|false
    {
        $max = self::MULTI_NURSE[$section] ?? null;
        if ($max === null) {
            return null;
        }

        $existing = NurseScheduleEntry::where('schedule_date', $this->selectedDate)
            ->where('section', $section)
            ->where('period', $period)
            ->count();

        if ($existing >= $max) {
            return false;
        }

        return $existing + 1;
    }

    public function loadPreviewRange(string $from, string $to): void
    {
        $from = Carbon::parse($from)->toDateString();
        $to = Carbon::parse($to)->toDateString();
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $this->previewFrom = $from;
        $this->previewTo = $to;

        $data = [];
        NurseScheduleEntry::with('employee')
            ->whereBetween('schedule_date', [$from, $to])
            ->orderBy('schedule_date')
            ->orderBy('slot')
            ->get()
            ->each(function ($entry) use (&$data) {
                $name = $entry->employee
                    ? trim($entry->employee->first_name.' '.$entry->employee->last_name)
                    : $entry->custom_name;
                $date = $entry->schedule_date->toDateString();
                if (isset($data[$entry->section][$entry->period][$date])) {
                    $data[$entry->section][$entry->period][$date] .= ' / '.$name;
                } else {
                    $data[$entry->section][$entry->period][$date] = $name;
                }
            });

        $this->previewData = $data;
    }

    public function removeEntry(int $entryId): void
    {
        NurseScheduleEntry::findOrFail($entryId)->delete();
        $this->loadSchedule();
        $this->dispatch('show-toast', message: 'Assignment removed.');
    }

    public function dismissToast(): void
    {
        $this->toastShow = false;
    }

    // ── Auto-schedule ──────────────────────────────────────────────────────────

    public function autoGenerate(string $block, int $month, int $year): void
    {
        [$startDate, $endDate] = $this->getBlockRange($block, $month, $year);

        $nurses = $this->getNursingNurses();
        if ($nurses->isEmpty()) {
            $this->dispatch('auto-schedule-result',
                success: false,
                message: 'No nursing staff found. Please assign nurses to the NURSING department first.'
            );

            return;
        }

        $leaveDays = $this->getLeaveDays($nurses->pluck('id'), $startDate, $endDate);
        $pools = $this->determinePools($nurses, $startDate);

        // Clear existing entries for the block
        NurseScheduleEntry::whereBetween('schedule_date', [$startDate, $endDate])->delete();

        // OPD (weekdays only, fixed nurse)
        if (! empty($pools['opd'])) {
            $this->generateOpdSchedule($pools['opd'], $startDate, $endDate, $leaveDays);
        }

        // ER, Triage, Ward (AM / PM / NOC)
        foreach (['er', 'triage', 'ward'] as $section) {
            if (! empty($pools[$section])) {
                $this->generateSectionSchedule($section, $pools[$section], $startDate, $endDate, $leaveDays);
            }
        }

        $this->loadSchedule();
        $this->dispatch('auto-schedule-result', success: true, message: "Schedule generated ({$startDate} – {$endDate}).");
        $this->dispatch('show-toast', message: "Schedule generated ({$startDate} – {$endDate}).");
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    private function getBlockRange(string $block, int $month, int $year): array
    {
        if ($block === 'A') {
            // Block A: 11th – 25th
            return [
                Carbon::createFromDate($year, $month, 11)->toDateString(),
                Carbon::createFromDate($year, $month, 25)->toDateString(),
            ];
        }

        // Block B: 26th – 10th of next month (15 days)
        $start = Carbon::createFromDate($year, $month, 26);

        return [
            $start->toDateString(),
            $start->copy()->addDays(14)->toDateString(),
        ];
    }

    private function getNursingNurses(): Collection
    {
        $nurses = Employee::with('employmentDetail.department')
            ->whereHas('employmentDetail', function ($q) {
                $q->whereHas('department', function ($q2) {
                    $q2->whereRaw('LOWER(name) LIKE ?', ['%nurs%']);
                });
            })
            ->orderBy('last_name')
            ->get();

        // Fallback: use nurses who have previously appeared in schedule entries
        if ($nurses->isEmpty()) {
            $nurseIds = NurseScheduleEntry::whereNotNull('employee_id')
                ->distinct()
                ->pluck('employee_id');
            $nurses = Employee::whereIn('id', $nurseIds)->orderBy('last_name')->get();
        }

        return $nurses->map(fn ($e) => [
            'id' => $e->id,
            'name' => trim($e->first_name.' '.$e->last_name),
            'user_id' => $e->user_id,
        ]);
    }

    private function getLeaveDays(Collection $employeeIds, string $startDate, string $endDate): array
    {
        $rows = DB::table('leaves')
            ->join('employee', 'employee.user_id', '=', 'leaves.user_id')
            ->whereIn('employee.id', $employeeIds)
            ->where('leaves.hr_status', 'approved')
            ->where('leaves.start_date', '<=', $endDate)
            ->where('leaves.end_date', '>=', $startDate)
            ->select('employee.id as employee_id', 'leaves.start_date', 'leaves.end_date')
            ->get();

        // [employee_id => [date_string => true, ...]]
        $leaveDays = [];
        foreach ($rows as $row) {
            $d = Carbon::parse($row->start_date);
            $dEnd = Carbon::parse($row->end_date);
            while ($d->lte($dEnd)) {
                $leaveDays[$row->employee_id][$d->toDateString()] = true;
                $d->addDay();
            }
        }

        return $leaveDays;
    }

    private function determinePools(Collection $nurses, string $startDate): array
    {
        // Find each nurse's most-used section in the most recent completed block
        $recentSection = NurseScheduleEntry::where('schedule_date', '<', $startDate)
            ->whereNotNull('employee_id')
            ->whereIn('section', ['er', 'triage', 'ward', 'opd'])
            ->select('employee_id', 'section')
            ->orderBy('schedule_date', 'desc')
            ->get()
            ->groupBy('employee_id')
            ->map(fn ($entries) => $entries
                ->groupBy('section')
                ->map->count()
                ->sortDesc()
                ->keys()
                ->first()
            );

        // Rotation: er → triage → ward → er (3-way cycle for shift sections)
        $rotationMap = ['er' => 'triage', 'triage' => 'ward', 'ward' => 'er', 'opd' => 'opd'];

        $pools = ['er' => [], 'triage' => [], 'ward' => [], 'opd' => []];
        $opdId = null;

        // Assign OPD nurse first (whoever was exclusively in OPD last)
        foreach ($nurses as $nurse) {
            if (($recentSection[$nurse['id']] ?? null) === 'opd') {
                $pools['opd'][] = $nurse;
                $opdId = $nurse['id'];
                break; // OPD = one fixed nurse
            }
        }

        // Assign the rest to shift sections
        foreach ($nurses as $nurse) {
            if ($nurse['id'] === $opdId) {
                continue;
            }

            $last = $recentSection[$nurse['id']] ?? null;

            if ($last && $last !== 'opd' && isset($rotationMap[$last])) {
                $next = $rotationMap[$last];
            } else {
                // No history — distribute evenly
                $next = collect(['er', 'triage', 'ward'])
                    ->sortBy(fn ($s) => count($pools[$s]))
                    ->first();
            }

            $pools[$next][] = $nurse;
        }

        return $pools;
    }

    private function generateSectionSchedule(
        string $section,
        array $nurses,
        string $startDate,
        string $endDate,
        array $leaveDays
    ): void {
        $shifts = ['am', 'pm', 'noc'];
        $nurseIds = array_column($nurses, 'id');
        $hours = array_fill_keys($nurseIds, 0);
        $lastShift = array_fill_keys($nurseIds, null);
        $lastShiftDate = array_fill_keys($nurseIds, null);
        $entries = [];

        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dateStr = $current->toDateString();

            foreach ($shifts as $shift) {
                // Snapshot current tracking state for closure capture
                $hoursSnap = $hours;
                $lastShiftSnap = $lastShift;
                $lastDateSnap = $lastShiftDate;

                // Track which nurses are already assigned a shift today
                $assignedToday = array_column(
                    array_filter($entries, fn ($e) => $e['schedule_date'] === $dateStr),
                    'employee_id'
                );

                $baseFilter = function ($n) use ($dateStr, $shift, $leaveDays, $lastShiftSnap, $lastDateSnap, $assignedToday) {
                    if (isset($leaveDays[$n['id']][$dateStr])) return false;
                    if (in_array($n['id'], $assignedToday)) return false;
                    if (
                        $lastShiftSnap[$n['id']] === 'noc'
                        && $shift === 'am'
                        && $lastDateSnap[$n['id']] !== null
                        && Carbon::parse($lastDateSnap[$n['id']])->addDay()->toDateString() === $dateStr
                    ) return false;
                    return true;
                };

                // Nurses who pass base rules AND are under the 80h cap
                $underCap = array_values(array_filter(
                    array_filter($nurses, $baseFilter),
                    fn ($n) => $hoursSnap[$n['id']] < 80
                ));

                // If all eligible nurses have hit 80h (e.g. leave coverage), allow over-cap
                $available = !empty($underCap)
                    ? $underCap
                    : array_values(array_filter($nurses, $baseFilter));

                if (empty($available)) {
                    continue;
                }

                // Greedy: nurse with fewest accumulated hours gets priority
                usort($available, fn ($a, $b) => $hoursSnap[$a['id']] <=> $hoursSnap[$b['id']]);
                $picked = $available[0];

                $entries[] = [
                    'schedule_date' => $dateStr,
                    'section' => $section,
                    'period' => $shift,
                    'slot' => null,
                    'employee_id' => $picked['id'],
                    'custom_name' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $hours[$picked['id']] += 8;
                $lastShift[$picked['id']] = $shift;
                $lastShiftDate[$picked['id']] = $dateStr;
            }

            $current->addDay();
        }

        foreach (array_chunk($entries, 100) as $chunk) {
            NurseScheduleEntry::insert($chunk);
        }
    }

    private function generateOpdSchedule(
        array $nurses,
        string $startDate,
        string $endDate,
        array $leaveDays
    ): void {
        if (empty($nurses)) {
            return;
        }

        $nurse = $nurses[0]; // OPD = single fixed nurse
        $entries = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            if (
                ! $current->isWeekend()
                && ! isset($leaveDays[$nurse['id']][$current->toDateString()])
            ) {
                $entries[] = [
                    'schedule_date' => $current->toDateString(),
                    'section' => 'opd',
                    'period' => 'day',
                    'slot' => null,
                    'employee_id' => $nurse['id'],
                    'custom_name' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $current->addDay();
        }

        if (! empty($entries)) {
            NurseScheduleEntry::insert($entries);
        }
    }

    private function getNurseHoursByBlock(): array
    {
        $base = Carbon::parse($this->selectedDate);
        $year = $base->year;
        $month = $base->month;

        // Block A: 11th–25th of current month
        $blockA = [
            Carbon::createFromDate($year, $month, 11)->toDateString(),
            Carbon::createFromDate($year, $month, 25)->toDateString(),
        ];

        // Block B: 26th of current month – 10th of next month
        $blockBStart = Carbon::createFromDate($year, $month, 26);
        $blockB = [
            $blockBStart->toDateString(),
            $blockBStart->copy()->addDays(14)->toDateString(),
        ];

        $result = ['A' => [], 'B' => []];

        foreach (['A' => $blockA, 'B' => $blockB] as $label => [$start, $end]) {
            $rows = NurseScheduleEntry::whereBetween('schedule_date', [$start, $end])
                ->whereNotNull('employee_id')
                ->select('employee_id', DB::raw('COUNT(*) as shifts'))
                ->groupBy('employee_id')
                ->get();

            foreach ($rows as $row) {
                $emp = Employee::find($row->employee_id);
                $result[$label][] = [
                    'name' => $emp ? trim($emp->first_name.' '.$emp->last_name) : 'Unknown',
                    'shifts' => $row->shifts,
                    'hours' => $row->shifts * 8,
                    'over_cap' => ($row->shifts * 8) > 80,
                ];
            }

            usort($result[$label], fn ($a, $b) => $b['hours'] <=> $a['hours']);
        }

        return $result;
    }

    public function render()
    {
        $nurses = Employee::with('employmentDetail')
            ->orderBy('last_name')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'name' => trim($e->first_name.' '.$e->last_name),
                'position' => $e->employmentDetail?->position ?? 'Nurse',
                'emp_no' => $e->employee_number ?? '',
            ]);

        $isWeekend = Carbon::parse($this->selectedDate)->isWeekend();
        $isOpdClosed = $isWeekend;

        $previewDates = [];
        if ($this->previewFrom && $this->previewTo) {
            $cur = Carbon::parse($this->previewFrom);
            $end = Carbon::parse($this->previewTo);
            while ($cur->lte($end)) {
                $previewDates[] = $cur->toDateString();
                $cur->addDay();
            }
        }

        $monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];

        return view('pages.nursing.nurse-schedule', [
            'nurses' => $nurses,
            'schedule' => $this->schedule,
            'isOpdClosed' => $isOpdClosed,
            'isWeekend' => $isWeekend,
            'previewData' => $this->previewData,
            'previewDates' => $previewDates,
            'previewFrom' => $this->previewFrom,
            'previewTo' => $this->previewTo,
            'monthNames' => $monthNames,
            'nurseHoursByBlock' => $this->getNurseHoursByBlock(),
        ])->layout('layouts.app');
    }
}
