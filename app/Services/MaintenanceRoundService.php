<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Location;
use App\Models\LocationAreaPart;
use App\Models\MaintenanceRound;
use App\Models\MaintenanceRoundItem;
use App\Models\MaintenanceRoundLock;
use App\Models\MaintenanceSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MaintenanceRoundService
{
    public function startRound(
        int $userId,
        MaintenanceSlot $slot,
    ): MaintenanceRound {
        return DB::transaction(function () use ($userId, $slot) {
            foreach ($slot->slotLocations as $slotLoc) {
                $alreadyLocked = MaintenanceRoundLock::active()
                    ->where('location_area_id', $slotLoc->location_area_id)
                    ->exists();

                if ($alreadyLocked) {
                    throw new \RuntimeException(
                        'Location already taken by another staff.'
                    );
                }

                MaintenanceRoundLock::create([
                    'location_area_id' => $slotLoc->location_area_id,
                    'locked_by_user_id' => $userId,
                    'locked_at' => now(),
                    'released_at' => null,
                ]);
            }

            $round = MaintenanceRound::create([
                'user_id' => $userId,
                'slot_id' => $slot->id,
                'started_at' => now(),
                'status' => 'in_progress',
            ]);

            $order = 0;
            foreach ($slot->slotLocations as $slotLoc) {
                // location_area_id references locations.id;
                // location_area_parts uses location_id column
                $parts = LocationAreaPart::where(
                    'location_id',
                    $slotLoc->location_area_id
                )->get();

                foreach ($parts as $part) {
                    MaintenanceRoundItem::create([
                        'round_id' => $round->id,
                        'location_area_id' => $slotLoc->location_area_id,
                        'location_area_part_id' => $part->id,
                        'status' => 'pending',
                        'order_number' => $order++,
                    ]);
                }
            }

            $slot->update(['last_used_at' => now()]);

            AuditService::log(
                action: 'round_started',
                module: 'maintenance',
                modelType: 'MaintenanceRound',
                modelId: $round->id,
            );

            return $round;
        });
    }

    public function completeItem(
        MaintenanceRoundItem $item,
        ?string $photoPath,
        string $status,
        ?string $skipReason = null,
    ): void {
        if ($status === 'completed'
            && $item->requiresPhoto()
            && ! $photoPath) {
            throw new \RuntimeException('Photo required for this area.');
        }

        $item->update([
            'status' => $status,
            'photo_path' => $photoPath,
            'skip_reason' => $skipReason,
            'completed_at' => now(),
        ]);

        AuditService::log(
            action: 'item_'.$status,
            module: 'maintenance',
            modelType: 'MaintenanceRoundItem',
            modelId: $item->id,
            newValues: ['skip_reason' => $skipReason],
        );

        if ($item->round->isComplete()) {
            $this->completeRound($item->round);
        }
    }

    public function completeRound(MaintenanceRound $round): void
    {
        $round->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        MaintenanceRoundLock::active()
            ->where('locked_by_user_id', $round->user_id)
            ->update(['released_at' => now()]);

        AuditService::log(
            action: 'round_completed',
            module: 'maintenance',
            modelType: 'MaintenanceRound',
            modelId: $round->id,
        );
    }

    public function releaseLocation(
        MaintenanceRound $round,
        int $locationAreaId,
    ): void {
        MaintenanceRoundItem::where('round_id', $round->id)
            ->where('location_area_id', $locationAreaId)
            ->where('status', 'pending')
            ->update([
                'status' => 'skipped',
                'skip_reason' => 'Released to pool',
            ]);

        MaintenanceRoundLock::active()
            ->where('location_area_id', $locationAreaId)
            ->where('locked_by_user_id', $round->user_id)
            ->update(['released_at' => now()]);

        AuditService::log(
            action: 'location_released',
            module: 'maintenance',
            modelType: 'MaintenanceRoundLock',
            modelId: $locationAreaId,
        );
    }

    public function getEmergencyPool(): Collection
    {
        $lockedIds = MaintenanceRoundLock::active()
            ->pluck('location_area_id');

        return Location::whereNotIn('id', $lockedIds)
            ->orderBy('name')
            ->get();
    }

    public function releaseStaleLocks(): void
    {
        $staleUserIds = MaintenanceRound::where('status', 'in_progress')
            ->where('started_at', '<', now()->subHours(12))
            ->pluck('user_id');

        MaintenanceRoundLock::active()
            ->whereIn('locked_by_user_id', $staleUserIds)
            ->update(['released_at' => now()]);
    }
}
