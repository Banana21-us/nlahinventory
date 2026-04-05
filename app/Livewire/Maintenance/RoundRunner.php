<?php

declare(strict_types=1);

namespace App\Livewire\Maintenance;

use App\Models\LocationAreaPart;
use App\Models\MaintenanceRound;
use App\Models\MaintenanceRoundItem;
use App\Models\MaintenanceRoundLock;
use App\Services\MaintenanceRoundService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RoundRunner extends Component
{
    public MaintenanceRound $round;

    public int $currentIndex = 0;

    public ?MaintenanceRoundItem $currentItem = null;

    public bool $isCR = false;

    public array $checkedPartIds = [];

    public bool $showSkipModal = false;

    public bool $showCancelModal = false;

    public string $skipReason = 'Patient present';

    public bool $processing = false;

    public bool $voiceEnabled = true;

    public int $totalItems = 0;

    public int $completedCount = 0;

    /** All parts for the current location (for CR checklist) */
    public array $locationParts = [];

    public function mount(MaintenanceRound $round): void
    {
        if ($round->user_id !== Auth::id()) {
            abort(403);
        }

        $this->round = $round->load([
            'items.locationArea',
            'items.part.areaPart',
            'slot',
        ]);

        $this->totalItems = $this->round->items->count();

        // Find first pending item
        $firstPending = $this->round->items
            ->search(fn ($item) => $item->status === 'pending');

        $this->currentIndex = $firstPending !== false ? $firstPending : 0;
        $this->loadCurrentItem();
    }

    private function loadCurrentItem(): void
    {
        $this->currentItem = $this->round->items->get($this->currentIndex);

        if (! $this->currentItem) {
            return;
        }

        $locationName = $this->currentItem->locationArea?->name ?? '';
        $this->isCR = str_contains($locationName, '| CR')
            || str_ends_with(trim($locationName), 'CR');

        $this->checkedPartIds = [];

        $this->completedCount = $this->round->items
            ->whereIn('status', ['completed', 'skipped'])
            ->count();

        // Load all parts for this location for the checklist
        if ($this->isCR) {
            $locationId = $this->currentItem->location_area_id;
            $this->locationParts = LocationAreaPart::with('areaPart')
                ->where('location_id', $locationId)
                ->get()
                ->map(fn ($p) => ['id' => $p->id, 'name' => $p->areaPart?->name ?? ''])
                ->toArray();
        } else {
            $this->locationParts = [];
        }

        $this->dispatch('area-changed', isCR: $this->isCR);
    }

    public function completeWithPhoto(string $photoPath): void
    {
        if ($this->processing) {
            return;
        }

        $this->processing = true;

        try {
            app(MaintenanceRoundService::class)->completeItem(
                $this->currentItem,
                $photoPath,
                'completed'
            );
            $this->nextItem();
        } catch (\RuntimeException $e) {
            $this->dispatch('notify', type: 'danger', title: 'Error', body: $e->getMessage());
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Optimistically advance after a photo is captured on the client.
     * The real photo path is written by the upload endpoint separately;
     * we store a sentinel value so requiresPhoto() is satisfied.
     */
    public function advanceAfterCapture(): void
    {
        if ($this->processing || ! $this->currentItem) {
            return;
        }

        $this->processing = true;

        try {
            app(MaintenanceRoundService::class)->completeItem(
                $this->currentItem,
                'pending_upload',
                'completed'
            );
            $this->nextItem();
        } catch (\RuntimeException $e) {
            $this->dispatch('notify', type: 'danger', title: 'Error', body: $e->getMessage());
        } finally {
            $this->processing = false;
        }
    }

    public function completeChecklist(): void
    {
        if ($this->processing) {
            return;
        }

        $allPartIds = array_column($this->locationParts, 'id');
        $unchecked = array_diff($allPartIds, $this->checkedPartIds);

        if (! empty($unchecked)) {
            $this->dispatch('notify', type: 'warning', title: 'Incomplete', body: 'Check all items before submitting.');

            return;
        }

        $this->processing = true;

        try {
            app(MaintenanceRoundService::class)->completeItem(
                $this->currentItem,
                null,
                'completed'
            );
            $this->nextItem();
        } catch (\RuntimeException $e) {
            $this->dispatch('notify', type: 'danger', title: 'Error', body: $e->getMessage());
        } finally {
            $this->processing = false;
        }
    }

    public function togglePartCheck(int $partId): void
    {
        if (in_array($partId, $this->checkedPartIds, true)) {
            $this->checkedPartIds = array_values(
                array_filter($this->checkedPartIds, fn ($id) => $id !== $partId)
            );
        } else {
            $this->checkedPartIds[] = $partId;
        }
    }

    public function cancelRound(): void
    {
        // 'abandoned' matches the enum in the maintenance_rounds migration.
        $this->round->update([
            'status' => 'abandoned',
            'completed_at' => now(),
        ]);

        // Release all locks held by this user so other staff can claim them.
        MaintenanceRoundLock::active()
            ->where('locked_by_user_id', Auth::id())
            ->update(['released_at' => now()]);

        $this->redirect(route('maintenance.slots'), navigate: false);
    }

    public function openSkipModal(): void
    {
        if (! $this->currentItem?->isSkippable()) {
            return;
        }

        $this->showSkipModal = true;
    }

    public function setSkipReason(string $reason): void
    {
        $this->skipReason = $reason;
        $this->skipItem();
    }

    public function skipItem(): void
    {
        if ($this->processing) {
            return;
        }

        $this->processing = true;

        try {
            app(MaintenanceRoundService::class)->completeItem(
                $this->currentItem,
                null,
                'skipped',
                $this->skipReason
            );
            $this->showSkipModal = false;
            $this->nextItem();
        } catch (\RuntimeException $e) {
            $this->dispatch('notify', type: 'danger', title: 'Error', body: $e->getMessage());
        } finally {
            $this->processing = false;
        }
    }

    public function nextItem(): void
    {
        // Refresh round items
        $this->round->load(['items.locationArea', 'items.part.areaPart']);

        // Find next pending after current index
        $next = null;
        foreach ($this->round->items as $idx => $item) {
            if ($idx > $this->currentIndex && $item->status === 'pending') {
                $next = $idx;
                break;
            }
        }

        if ($next === null) {
            // Round complete
            $this->redirect(
                route('maintenance.slots').'?success=Round+complete!+Great+work.'
            );

            return;
        }

        $this->currentIndex = $next;
        $this->loadCurrentItem();
        $this->dispatch('item-advanced', nextArea: $this->currentItem?->locationArea?->name ?? '');
    }

    public function previousItem(): void
    {
        if ($this->currentIndex <= 0) {
            return;
        }

        $this->currentIndex--;
        $this->loadCurrentItem();
    }

    public function render()
    {
        return view('livewire.maintenance.round-runner')
            ->layout('layouts.app');
    }
}
