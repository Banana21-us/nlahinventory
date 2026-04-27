<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\NurseScheduleEntry;
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
            ->get()
            ->each(function ($entry) {
                $name = $entry->employee
                    ? trim($entry->employee->first_name.' '.$entry->employee->last_name)
                    : $entry->custom_name;

                if (! isset($this->schedule[$entry->section])) {
                    $this->schedule[$entry->section] = [];
                }

                if ($entry->section === 'or') {
                    $slotKey = $entry->slot ?? '1st';
                    if (! isset($this->schedule[$entry->section][$slotKey])) {
                        $this->schedule[$entry->section][$slotKey] = [];
                    }
                    $this->schedule[$entry->section][$slotKey][] = [
                        'id' => $entry->id,
                        'employee_id' => $entry->employee_id,
                        'name' => $name,
                        'period' => $entry->period,
                    ];
                } elseif ($entry->section === 'hn') {
                    // For head nurse, period contains the shift (8-3, 3-11, IPCN)
                    $this->schedule[$entry->section][$entry->period] = [
                        'id' => $entry->id,
                        'employee_id' => $entry->employee_id,
                        'name' => $name,
                    ];
                } else {
                    $this->schedule[$entry->section][$entry->period] = [
                        'id' => $entry->id,
                        'employee_id' => $entry->employee_id,
                        'name' => $name,
                    ];
                }
            });
    }

    public function assignEmployee(int $employeeId, string $section, string $period, ?string $slot = null): void
    {
        $employee = Employee::findOrFail($employeeId);

        $query = [
            'schedule_date' => $this->selectedDate,
            'section' => $section,
            'period' => $period,
        ];

        // Only add slot to query if explicitly provided for OR section
        if ($section === 'or' && $slot) {
            $query['slot'] = $slot;
        }

        $exists = NurseScheduleEntry::where($query)->exists();

        if (! $exists) {
            // Delete existing entry for this specific period and slot combination
            NurseScheduleEntry::where('schedule_date', $this->selectedDate)
                ->where('section', $section)
                ->where('period', $period)
                ->when($section === 'or' && $slot, fn ($q) => $q->where('slot', $slot))
                ->delete();

            $createData = [
                'schedule_date' => $this->selectedDate,
                'section' => $section,
                'period' => $period,
                'employee_id' => $employeeId,
                'custom_name' => null,
            ];

            if ($section === 'or' && $slot) {
                $createData['slot'] = $slot;
            }

            NurseScheduleEntry::create($createData);
        }

        $name = trim($employee->first_name.' '.$employee->last_name);
        $this->loadSchedule();
        $this->dispatch('show-toast', message: "{$name} assigned successfully.");
    }

    public function assignCustom(string $section, string $period, string $customName, ?string $slot = null): void
    {
        $customName = trim($customName);
        if (empty($customName)) {
            return;
        }

        // Delete existing entry for this specific period and slot combination
        NurseScheduleEntry::where('schedule_date', $this->selectedDate)
            ->where('section', $section)
            ->where('period', $period)
            ->when($section === 'or' && $slot, fn ($q) => $q->where('slot', $slot))
            ->delete();

        $createData = [
            'schedule_date' => $this->selectedDate,
            'section' => $section,
            'period' => $period,
            'employee_id' => null,
            'custom_name' => $customName,
        ];

        if ($section === 'or' && $slot) {
            $createData['slot'] = $slot;
        }

        NurseScheduleEntry::create($createData);

        $this->loadSchedule();
        $this->dispatch('show-toast', message: "{$customName} assigned successfully.");
    }

    public function loadPreviewRange(string $from, string $to): void
    {
        $from = \Carbon\Carbon::parse($from)->toDateString();
        $to = \Carbon\Carbon::parse($to)->toDateString();
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $this->previewFrom = $from;
        $this->previewTo = $to;

        $data = [];
        NurseScheduleEntry::with('employee')
            ->whereBetween('schedule_date', [$from, $to])
            ->orderBy('schedule_date')
            ->get()
            ->each(function ($entry) use (&$data) {
                $name = $entry->employee
                    ? trim($entry->employee->first_name.' '.$entry->employee->last_name)
                    : $entry->custom_name;
                $date = $entry->schedule_date->toDateString();

                if ($entry->section === 'or' && $entry->slot) {
                    // For OR, use slot as the sub-key
                    if (! isset($data[$entry->section])) {
                        $data[$entry->section] = [];
                    }
                    if (! isset($data[$entry->section][$entry->period])) {
                        $data[$entry->section][$entry->period] = [];
                    }
                    if (! isset($data[$entry->section][$entry->period][$entry->slot])) {
                        $data[$entry->section][$entry->period][$entry->slot] = [];
                    }
                    $data[$entry->section][$entry->period][$entry->slot][$date] = $name;
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

        $previewDates = [];
        if ($this->previewFrom && $this->previewTo) {
            $cur = \Carbon\Carbon::parse($this->previewFrom);
            $end = \Carbon\Carbon::parse($this->previewTo);
            while ($cur->lte($end)) {
                $previewDates[] = $cur->toDateString();
                $cur->addDay();
            }
        }

        return view('pages.nursing.nurse-schedule', [
            'nurses' => $nurses,
            'schedule' => $this->schedule,
            'previewData' => $this->previewData,
            'previewDates' => $previewDates,
            'previewFrom' => $this->previewFrom,
            'previewTo' => $this->previewTo,
        ])->layout('layouts.app');
    }
}
