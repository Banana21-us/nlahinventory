<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChecklistSyncController extends Controller
{
    /**
     * Receive batched offline checklist records and persist them.
     * Called by the client when returning online after working in a dead-spot.
     */
    public function sync(Request $request): JsonResponse
    {
        $records = $request->input('records', []);

        if (! is_array($records) || count($records) === 0) {
            return response()->json(['synced' => 0, 'errors' => []]);
        }

        $synced = 0;
        $errors = [];

        foreach ($records as $i => $record) {
            try {
                $this->processRecord($record);
                $synced++;
            } catch (\Throwable $e) {
                Log::warning("Checklist offline sync failed for record {$i}: " . $e->getMessage());
                $errors[] = $i;
            }
        }

        return response()->json(['synced' => $synced, 'errors' => $errors]);
    }

    private function processRecord(array $r): void
    {
        $partId      = (int) ($r['partId'] ?? 0);
        $dayKey      = (string) ($r['dayKey'] ?? 'selected');
        $shift       = strtoupper((string) ($r['shift'] ?? 'AM'));
        $periodType  = (string) ($r['periodType'] ?? 'daily');
        $selectedDate = (string) ($r['selectedDate'] ?? Carbon::now('Asia/Manila')->toDateString());
        $imageData   = $r['imageData'] ?? null;
        $skipReason  = $r['skipReason'] ?? null;
        $comment     = $r['comment'] ?? null;

        if ($partId <= 0) {
            throw new \InvalidArgumentException('Invalid partId');
        }

        $normalizedShift = in_array($shift, ['AM', 'PM'], true) ? $shift : null;
        $cleaningDate    = $this->resolveCleaningDate($dayKey, $periodType, $selectedDate);

        if ($cleaningDate === null) {
            throw new \InvalidArgumentException('Cannot resolve cleaning date');
        }

        // Remove any previous record for the same slot so we don't duplicate
        DB::table('records')
            ->where('location_area_part_id', $partId)
            ->where('period_type', $periodType)
            ->where('shift', $normalizedShift)
            ->where('status', 'YES')
            ->whereDate('cleaning_date', $cleaningDate)
            ->delete();

        if ($skipReason) {
            $remarks   = match ($skipReason) {
                'patient_present' => 'Skipped — Patient Present',
                'gloves'          => 'Skipped — Gloves On',
                default           => 'Skipped',
            };
            // Store as "skip:<reason>" to match what confirmToggleWithSkip() writes,
            // so loadExistingSlots() populates slotProofs and the preview button appears.
            $proofPath = 'skip:' . $skipReason;
        } else {
            $proofPath = $this->storeProofImage($imageData, $partId, $cleaningDate, $normalizedShift);
            $remarks   = 'Checked';
        }

        DB::table('records')->insert([
            'location_area_part_id' => $partId,
            'cleaning_date'         => $cleaningDate,
            'period_type'           => $periodType,
            'shift'                 => $normalizedShift,
            'status'                => 'YES',
            'remarks'               => $remarks,
            'proof'                 => $proofPath,
            'maintenance_name'      => Auth::user()?->name,
            'verifier_name'         => null,
            'verifier_status'       => 'NO',
            'verifier_comments'     => null,
            'maintenance_comments'  => is_string($comment) && trim($comment) !== '' ? trim($comment) : null,
        ]);
    }

    private function resolveCleaningDate(string $dayKey, string $periodType, string $selectedDate): ?string
    {
        $today = Carbon::now('Asia/Manila')->toDateString();

        return match ($periodType) {
            'daily', 'nightly' => $selectedDate !== '' ? $selectedDate : null,
            'weekly', 'monthly' => $today,
            default => null,
        };
    }

    private function storeProofImage(?string $imageData, int $partId, string $cleaningDate, ?string $shift): ?string
    {
        if (! $imageData || ! str_starts_with($imageData, 'data:image/')) {
            return null;
        }

        if (! preg_match('/^data:image\/([a-zA-Z0-9]+);base64,(.*)$/', $imageData, $matches)) {
            return null;
        }

        $binary = base64_decode($matches[2], true);
        if ($binary === false || $binary === '') {
            return null;
        }

        $safeShift = in_array($shift, ['AM', 'PM'], true) ? $shift : null;
        $filename  = $safeShift
            ? "locationareapart{$partId}_{$cleaningDate}_{$safeShift}.jpg"
            : "locationareapart{$partId}_{$cleaningDate}.jpg";

        $path = "checklist-proofs/{$filename}";
        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
