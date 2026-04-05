<?php

declare(strict_types=1);

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceRound;
use App\Models\MaintenanceRoundItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifierDashboard extends Component
{
    public $todayRounds = [];

    public ?int $selectedRoundId = null;

    public string $filter = 'all';

    public ?string $enlargedPhoto = null;

    public string $flagComment = '';

    public function mount(): void
    {
        $this->loadRounds();

        if ($this->todayRounds->isNotEmpty()) {
            $this->selectedRoundId = $this->todayRounds->first()->id;
        }
    }

    private function loadRounds(): void
    {
        $this->todayRounds = MaintenanceRound::with([
            'user',
            'items.locationArea',
            'items.part.areaPart',
        ])
            ->whereDate('started_at', today())
            ->orderByDesc('started_at')
            ->get();
    }

    public function selectRound(int $roundId): void
    {
        $this->selectedRoundId = $roundId;
        $this->filter = 'all';
    }

    public function approveItem(int $itemId): void
    {
        MaintenanceRoundItem::findOrFail($itemId)->update([
            'verification_status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $this->loadRounds();
    }

    public function flagItem(int $itemId): void
    {
        MaintenanceRoundItem::findOrFail($itemId)->update([
            'verification_status' => 'flagged',
            'verification_comment' => $this->flagComment,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $this->flagComment = '';
        $this->loadRounds();
    }

    public function rejectItem(int $itemId, string $reason): void
    {
        MaintenanceRoundItem::findOrFail($itemId)->update([
            'verification_status' => 'rejected',
            'verification_comment' => $reason,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $this->loadRounds();
    }

    public function bulkApprove(int $roundId): void
    {
        MaintenanceRoundItem::where('round_id', $roundId)
            ->where('status', 'completed')
            ->where('verification_status', 'pending')
            ->update([
                'verification_status' => 'approved',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

        $this->loadRounds();
        session()->flash('message', 'All items approved.');
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function enlargePhoto(string $path): void
    {
        $this->enlargedPhoto = $path;
    }

    public function closePhoto(): void
    {
        $this->enlargedPhoto = null;
    }

    public function getSelectedRound()
    {
        if (! $this->selectedRoundId) {
            return null;
        }

        return $this->todayRounds->firstWhere('id', $this->selectedRoundId);
    }

    public function getFilteredItems()
    {
        $round = $this->getSelectedRound();

        if (! $round) {
            return collect();
        }

        return $round->items->when($this->filter !== 'all', function ($items) {
            return $items->where('verification_status', $this->filter);
        });
    }

    public function render()
    {
        return view('livewire.maintenance.verifier-dashboard', [
            'selectedRound' => $this->getSelectedRound(),
            'filteredItems' => $this->getFilteredItems(),
        ])->layout('layouts.app');
    }
}
