<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\NurseScheduleEntry;
use Livewire\Component;

class NurseSchedule extends Component
{
    public string $selectedDate = '';
    public string $modalSearch = '';
    public bool   $toastShow    = false;
    public string $toastMessage = '';
    public array $schedule = [];

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
            ->when($this->modalSearch, fn ($q) =>
                $q->where(fn ($inner) =>
                    $inner->where('first_name', 'like', "%{$this->modalSearch}%")
                          ->orWhere('last_name',  'like', "%{$this->modalSearch}%")
                          ->orWhere('employee_number', 'like', "%{$this->modalSearch}%")
                )
            )
            ->orderBy('last_name')
            ->get()
            ->map(fn ($e) => [
                'id'       => $e->id,
                'name'     => trim($e->first_name . ' ' . $e->last_name),
                'position' => $e->employmentDetail?->position ?? 'Nurse',
                'emp_no'   => $e->employee_number,
            ]);

        return view('pages.nursing.nurse-schedule', [
            'nurses'   => $nurses,
            'schedule' => $this->schedule,
        ])->layout('layouts.app');
    }
}