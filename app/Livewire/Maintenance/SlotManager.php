<?php

declare(strict_types=1);

namespace App\Livewire\Maintenance;

use App\Models\Location;
use App\Models\MaintenanceRound;
use App\Models\MaintenanceRoundLock;
use App\Models\MaintenanceSlot;
use App\Models\MaintenanceSlotLocation;
use App\Services\MaintenanceRoundService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class SlotManager extends Component
{
    public $mySlots = [];

    public $allLocations = [];

    public $lockedLocationIds = [];

    public $emergencyPool = [];

    public $activeRound = null;

    public bool $showLocationPicker = false;

    public ?int $pickerSlotId = null;

    public ?int $editingSlotId = null;

    public string $editingSlotName = '';

    public string $locationSearch = '';

    public function mount(): void
    {
        $userId = Auth::id();

        // Ensure 3 default slots exist
        for ($i = 1; $i <= 3; $i++) {
            MaintenanceSlot::firstOrCreate(
                ['user_id' => $userId, 'slot_number' => $i],
                ['slot_name' => "Slot {$i}"]
            );
        }

        $this->loadData();
    }

    private function loadData(): void
    {
        $userId = Auth::id();

        $this->mySlots = MaintenanceSlot::where('user_id', $userId)
            ->with(['slotLocations.locationArea'])
            ->orderBy('slot_number')
            ->get();

        $this->allLocations = Location::orderBy('name')->get();

        $this->lockedLocationIds = MaintenanceRoundLock::active()
            ->where('locked_by_user_id', '!=', $userId)
            ->pluck('location_area_id')
            ->toArray();

        $service = app(MaintenanceRoundService::class);
        $this->emergencyPool = $service->getEmergencyPool();

        $this->activeRound = MaintenanceRound::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->with(['items'])
            ->latest()
            ->first();
    }

    public function saveSlotName(int $slotId, string $name): void
    {
        $slot = MaintenanceSlot::where('id', $slotId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $slot->update(['slot_name' => trim($name) ?: $slot->slot_name]);
        $this->editingSlotId = null;
        $this->loadData();
    }

    public function startEditName(int $slotId, string $currentName): void
    {
        $this->editingSlotId = $slotId;
        $this->editingSlotName = $currentName;
    }

    public function openLocationPicker(int $slotId): void
    {
        $this->pickerSlotId = $slotId;
        $this->showLocationPicker = true;
        $this->locationSearch = '';
    }

    public function closeLocationPicker(): void
    {
        $this->showLocationPicker = false;
        $this->pickerSlotId = null;
    }

    public function addLocation(int $slotId, int $locationId): void
    {
        $slot = MaintenanceSlot::where('id', $slotId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $maxOrder = MaintenanceSlotLocation::where('slot_id', $slotId)
            ->max('order_number') ?? 0;

        MaintenanceSlotLocation::firstOrCreate(
            ['slot_id' => $slotId, 'location_area_id' => $locationId],
            ['order_number' => $maxOrder + 1]
        );

        $this->loadData();
    }

    public function removeLocation(int $slotId, int $locationId): void
    {
        MaintenanceSlotLocation::where('slot_id', $slotId)
            ->where('location_area_id', $locationId)
            ->where(function ($q) {
                // Only if slot belongs to auth user
                $q->whereHas('slot', fn ($s) => $s->where('user_id', Auth::id()));
            })
            ->delete();

        $this->loadData();
    }

    #[On('sortable-updated')]
    public function updateOrder(int $slotId, array $orderedIds): void
    {
        foreach ($orderedIds as $order => $locationId) {
            MaintenanceSlotLocation::where('slot_id', $slotId)
                ->where('location_area_id', $locationId)
                ->update(['order_number' => $order]);
        }

        $this->loadData();
    }

    public function startRound(int $slotId): void
    {
        $slot = MaintenanceSlot::where('id', $slotId)
            ->where('user_id', Auth::id())
            ->with('slotLocations')
            ->firstOrFail();

        if ($slot->slotLocations->isEmpty()) {
            $this->dispatch('notify', type: 'danger', title: 'No locations in slot', body: 'Add at least one location before starting.');

            return;
        }

        try {
            $round = app(MaintenanceRoundService::class)->startRound(Auth::id(), $slot);
            $this->redirect(route('maintenance.round', $round->id));
        } catch (\RuntimeException $e) {
            $this->dispatch('notify', type: 'danger', title: 'Cannot start round', body: $e->getMessage());
        }
    }

    public function grabFromPool(int $locationId): void
    {
        // Add to a 4th emergency slot (create if not exist)
        $userId = Auth::id();
        $slot = MaintenanceSlot::firstOrCreate(
            ['user_id' => $userId, 'slot_number' => 4],
            ['slot_name' => 'Emergency']
        );

        $this->addLocation($slot->id, $locationId);
    }

    public function getFilteredLocations()
    {
        if (empty($this->locationSearch)) {
            return $this->allLocations;
        }

        $search = strtolower($this->locationSearch);

        return $this->allLocations->filter(
            fn ($loc) => str_contains(strtolower($loc->name), $search)
                || str_contains(strtolower($loc->floor ?? ''), $search)
        );
    }

    public function render()
    {
        return view('livewire.maintenance.slot-manager', [
            'mySlots' => $this->mySlots,
            'filteredLocations' => $this->getFilteredLocations(),
        ])->layout('layouts.app');
    }
}
