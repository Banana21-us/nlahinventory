<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MaintenanceRoundItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenancePhotoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'string'],
            'round_item_id' => ['nullable', 'integer'],
        ]);

        try {
            $base64 = $request->input('image');

            // Strip data URI prefix if present
            if (str_contains($base64, ',')) {
                $base64 = explode(',', $base64, 2)[1];
            }

            $binary = base64_decode($base64, strict: true);

            if ($binary === false) {
                return response()->json(['error' => 'Invalid image data'], 422);
            }

            $userId = Auth::id();
            $date = now()->format('Y-m-d');
            $filename = "{$userId}_".now()->timestamp.'_'.uniqid().'.jpg';
            $path = "maintenance/{$date}/{$userId}/{$filename}";

            Storage::disk('public')->put($path, $binary);

            // If this upload corresponds to a round item that was optimistically
            // marked complete with a 'pending_upload' sentinel, back-fill the
            // real storage path now that the file exists.
            $roundItemId = $request->input('round_item_id');
            if ($roundItemId) {
                $item = MaintenanceRoundItem::find($roundItemId);
                if ($item
                    && $item->round?->user_id === $userId
                    && in_array($item->photo_path, [null, '', 'pending_upload', 'pending'], true)
                ) {
                    $item->update(['photo_path' => $path]);
                }
            }

            return response()->json(['path' => $path]);
        } catch (\Throwable) {
            return response()->json(['error' => 'Upload failed'], 422);
        }
    }
}
