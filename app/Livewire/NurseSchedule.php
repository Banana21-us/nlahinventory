<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\NurseScheduleEntry;
use Livewire\Component;

class NurseSchedule extends Component
{
    public string $selectedDate = '';
    public bool   $toastShow    = false;
    public string $toastMessage = '';
    public array  $schedule     = [];
    public array  $previewData  = [];
    public string $previewFrom  = '';
    public string $previewTo    = '';

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
                    ? trim($entry->employee->first_name . ' ' . $entry->employee->last_name)
                    : $entry->custom_name;

                $this->schedule[$entry->section][$entry->slot][$entry->period][] = [
                    'id'          => $entry->id,
                    'employee_id' => $entry->employee_id,
                    'name'        => $name,
                ];
            });
    }

    public function assignEmployee(int $employeeId, string $section, string $slot, string $period): void
    {
        $employee = Employee::findOrFail($employeeId);

        $exists = NurseScheduleEntry::where([
            'schedule_date' => $this->selectedDate,
            'section'       => $section,
            'slot'          => $slot,
            'period'        => $period,
            'employee_id'   => $employeeId,
        ])->exists();

        if (! $exists) {
            NurseScheduleEntry::create([
                'schedule_date' => $this->selectedDate,
                'section'       => $section,
                'slot'          => $slot,
                'period'        => $period,
                'employee_id'   => $employeeId,
                'custom_name'   => null,
            ]);
        }

        $name = trim($employee->first_name . ' ' . $employee->last_name);
        $this->loadSchedule();
        $this->dispatch('show-toast', message: "{$name} assigned successfully.");
    }

    public function assignCustom(string $section, string $slot, string $period, string $customName): void
    {
        $customName = trim($customName);
        if (empty($customName)) {
            return;
        }

        NurseScheduleEntry::create([
            'schedule_date' => $this->selectedDate,
            'section'       => $section,
            'slot'          => $slot,
            'period'        => $period,
            'employee_id'   => null,
            'custom_name'   => $customName,
        ]);

        $this->loadSchedule();
        $this->dispatch('show-toast', message: "{$customName} assigned successfully.");
    }

    public function loadPreviewRange(string $from, string $to): void
    {
        $from = \Carbon\Carbon::parse($from)->toDateString();
        $to   = \Carbon\Carbon::parse($to)->toDateString();
        if ($from > $to) [$from, $to] = [$to, $from];

        $this->previewFrom = $from;
        $this->previewTo   = $to;

        $data = [];
        NurseScheduleEntry::with('employee')
            ->whereBetween('schedule_date', [$from, $to])
            ->orderBy('schedule_date')
            ->get()
            ->each(function ($entry) use (&$data) {
                $name = $entry->employee
                    ? trim($entry->employee->first_name . ' ' . $entry->employee->last_name)
                    : $entry->custom_name;
                $date = $entry->schedule_date->toDateString();
                $data[$entry->section][$entry->slot][$entry->period][$date][] = $name;
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
                'id'       => $e->id,
                'name'     => trim($e->first_name . ' ' . $e->last_name),
                'position' => $e->employmentDetail?->position ?? 'Nurse',
                'emp_no'   => $e->employee_number ?? '',
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
            'nurses'       => $nurses,
            'schedule'     => $this->schedule,
            'previewData'  => $this->previewData,
            'previewDates' => $previewDates,
            'previewFrom'  => $this->previewFrom,
            'previewTo'    => $this->previewTo,
        ])->layout('layouts.app');
    }
}