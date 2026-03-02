<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

new class extends Component {
    public array $selectedSlots = [];
    public bool $editMode = false;
    public array $areaParts = [];
    public array $locations = [];
    public string $selectedLocation = '';
    public ?int $selectedLocationId = null;
    public string $periodType = 'daily';
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
        $this->periodType = in_array(request('period'), ['daily', 'weekly', 'monthly'], true)
            ? request('period')
            : 'daily';
        $this->loadLocations();
        $this->loadAreaParts();
        $this->buildWeekDates();
        $this->loadExistingSlots();
    }

    public function updatedPeriodType(): void
    {
        $this->loadAreaParts();
        $this->loadExistingSlots();
    }

    public function updatedSelectedLocation(string $value): void
    {
        $matched = collect($this->locations)
            ->first(fn (array $location) => strcasecmp($location['name'], trim($value)) === 0);

        $this->selectedLocationId = $matched['id'] ?? null;
        $this->loadAreaParts();
        $this->loadExistingSlots();
    }

    public function toggleEditMode(): void
    {
        if ($this->editMode) {
            $this->saveChecklist();
        }

        $this->editMode = ! $this->editMode;
    }

    public function toggleSlot(int $locationAreaPartId, string $dayKey, string $shift): void
    {
        if (! $this->editMode) {
            return;
        }

        $key = $this->slotKey($locationAreaPartId, $dayKey, $shift);

        if (isset($this->selectedSlots[$key])) {
            unset($this->selectedSlots[$key]);
        } else {
            $this->selectedSlots[$key] = true;
        }
    }

    public function isSlotSelected(int $locationAreaPartId, string $dayKey, string $shift): bool
    {
        return isset($this->selectedSlots[$this->slotKey($locationAreaPartId, $dayKey, $shift)]);
    }

    private function loadAreaParts(): void
    {
        try {
            $query = DB::table('location_area_parts as lap')
                ->join('area_parts as ap', 'ap.id', '=', 'lap.area_part_id')
                ->join('locations as l', 'l.id', '=', 'lap.location_id')
                ->where('lap.frequency', $this->periodType)
                ->orderBy('l.name')
                ->orderBy('ap.name');

            if ($this->selectedLocationId !== null) {
                $query->where('lap.location_id', $this->selectedLocationId);
            }

            $parts = $query->get([
                'lap.id as location_area_part_id',
                'ap.name as area_part_name',
                'l.name as location_name',
            ]);

            $this->areaParts = $parts->map(fn ($part) => [
                'id' => (int) $part->location_area_part_id,
                'name' => $part->area_part_name,
                'location' => $part->location_name,
                'display_name' => $part->location_name.' - '.$part->area_part_name,
            ])->all();
        } catch (\Throwable) {
            $this->areaParts = [];
        }
    }

    private function loadLocations(): void
    {
        try {
            $this->locations = DB::table('locations')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn ($location) => [
                    'id' => (int) $location->id,
                    'name' => $location->name,
                ])
                ->all();
        } catch (\Throwable) {
            $this->locations = [];
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

        if (empty($this->areaParts)) {
            return;
        }

        try {
            $partIds = array_column($this->areaParts, 'id');
            $records = DB::table('records')
                ->whereIn('location_area_part_id', $partIds)
                ->where('period_type', $this->periodType)
                ->whereBetween('cleaning_date', [
                    $this->weekDates['mon'],
                    $this->weekDates['fri'],
                ])
                ->where('status', 'YES')
                ->get(['location_area_part_id', 'cleaning_date', 'shift']);

            foreach ($records as $record) {
                $dayKey = strtolower(Carbon::parse($record->cleaning_date)->format('D'));
                $this->selectedSlots[$this->slotKey((int) $record->location_area_part_id, $dayKey, $record->shift)] = true;
            }
        } catch (\Throwable) {
            // Keep UI usable even when table is not migrated yet.
        }
    }

    private function saveChecklist(): void
    {
        if (empty($this->areaParts)) {
            return;
        }

        try {
            $partIds = array_column($this->areaParts, 'id');

            DB::table('records')
                ->whereIn('location_area_part_id', $partIds)
                ->where('period_type', $this->periodType)
                ->whereBetween('cleaning_date', [
                    $this->weekDates['mon'],
                    $this->weekDates['fri'],
                ])
                ->delete();

            foreach (array_keys($this->selectedSlots) as $key) {
                [$partId, $dayKey, $shift] = explode('|', $key);

                if (! isset($this->weekDates[$dayKey]) || ! in_array($shift, $this->shifts, true)) {
                    continue;
                }

                $cleaningDate = $this->weekDates[$dayKey];
                $periodStart = match ($this->periodType) {
                    'weekly' => Carbon::parse($cleaningDate)->startOfWeek(Carbon::MONDAY)->toDateString(),
                    'monthly' => Carbon::parse($cleaningDate)->startOfMonth()->toDateString(),
                    default => $cleaningDate,
                };

                DB::table('records')->insert([
                    'location_area_part_id' => (int) $partId,
                    'cleaning_date' => $cleaningDate,
                    'period_type' => $this->periodType,
                    'period_start' => $periodStart,
                    'shift' => $shift,
                    'status' => 'YES',
                    'inspector_name' => Auth::user()?->name,
                    'verifier_name' => null,
                    'comments' => null,
                ]);
            }

            $this->loadExistingSlots();
        } catch (\Throwable) {
            // Silently ignore DB write errors to avoid hard-crashing the page.
        }
    }

    private function slotKey(int $partId, string $dayKey, string $shift): string
    {
        return $partId.'|'.$dayKey.'|'.$shift;
    }
}; ?>

<section class="w-full">
    @include('partials.checklist-heading')

    <x-pages::Maintenance.checklist.layout
        :heading="__('Checklist Schedule')"
        :subheading="__('Set AM/PM cleaning checks per location part for the current week')"
        :wide="true"
    >
        <div class="space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-64">
                        <label for="selectedLocation" class="mb-1 block text-xs font-medium text-zinc-600 dark:text-zinc-300">
                            {{ __('Area Location') }}
                        </label>
                        <input
                            id="selectedLocation"
                            type="text"
                            list="location-options"
                            wire:model.live.debounce.300ms="selectedLocation"
                            placeholder="{{ __('Search location...') }}"
                            class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900"
                        />
                        <datalist id="location-options">
                            @foreach ($locations as $location)
                                <option value="{{ $location['name'] }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-1">
                    <flux:button variant="primary" wire:click="toggleEditMode">
                        {{ $editMode ? __('Save Checklist') : __('Edit Checklist') }}
                    </flux:button>

                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
                        {{ $editMode ? __('Edit mode: click cells to toggle checks.') : __('View mode: click Edit Checklist to modify.') }}
                    </flux:text>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                <table class="min-w-full border-collapse text-sm">
                    <thead class="bg-zinc-100 dark:bg-zinc-800">
                        <tr>
                            <th class="border border-zinc-200 px-4 py-2 text-left dark:border-zinc-700">{{ __('Area Part') }}</th>
                            @foreach ($days as $dayKey => $dayName)
                                <th colspan="2" class="border border-zinc-200 px-3 py-2 text-center dark:border-zinc-700">
                                    <div class="font-semibold">{{ $dayName }}</div>
                                    <div class="text-xs text-zinc-500">{{ \Carbon\Carbon::parse($weekDates[$dayKey])->format('M d, Y') }}</div>
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="border border-zinc-200 px-4 py-2 dark:border-zinc-700"></th>
                            @foreach (array_keys($days) as $dayKey)
                                <th class="border border-zinc-200 px-2 py-1 text-center dark:border-zinc-700">AM</th>
                                <th class="border border-zinc-200 px-2 py-1 text-center dark:border-zinc-700">PM</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($areaParts as $part)
                            <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/60">
                                <td class="border border-zinc-200 px-4 py-2 font-medium dark:border-zinc-700">
                                    {{ $part['display_name'] }}
                                </td>
                                @foreach (array_keys($days) as $dayKey)
                                    @foreach ($shifts as $shift)
                                        @php
                                            $selected = $this->isSlotSelected($part['id'], $dayKey, $shift);
                                        @endphp
                                        <td
                                            wire:click="toggleSlot({{ $part['id'] }}, '{{ $dayKey }}', '{{ $shift }}')"
                                            class="border border-zinc-200 px-2 py-2 text-center transition dark:border-zinc-700 {{ $editMode ? 'cursor-pointer hover:bg-zinc-200 dark:hover:bg-zinc-700' : '' }} {{ $selected ? 'bg-green-500/80 text-white' : '' }}"
                                        >
                                            {{ $selected ? 'YES' : '-' }}
                                        </td>
                                    @endforeach
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="border border-zinc-200 px-4 py-6 text-center text-zinc-500 dark:border-zinc-700">
                                    {{ __('No mapped checklist parts found. Add rows to location_area_parts with the selected frequency.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-pages::Maintenance.checklist.layout>
</section>
