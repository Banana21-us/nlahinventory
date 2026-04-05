<?php

namespace App\Livewire;

use App\Models\AttendanceSummary;
use App\Models\MdbUploadLog;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class AttendanceManagement extends Component
{
    use WithFileUploads;

    public string $selectedDate = '';

    public string $search = '';

    public string $statusFilter = '';

    public bool $showUpload = false;

    /** @var TemporaryUploadedFile|null */
    public $mdbFile = null;

    public ?array $uploadResult = null;

    public bool $processing = false;

    public ?string $toastMessage = null;

    public ?string $toastError = null;

    protected function rules(): array
    {
        return [
            'mdbFile' => ['required', 'file', 'max:51200', 'extensions:mdb'],
        ];
    }

    public function mount(): void
    {
        $this->selectedDate = today()->toDateString();
    }

    // ── Upload + Import ──────────────────────────────────

    public function uploadAndProcess(AttendanceService $service): void
    {
        $this->validate();

        $original = $this->mdbFile->getClientOriginalName();

        // Use the temp file path directly — storeAs on Livewire uploads can
        // resolve to an unexpected location. getRealPath() gives the actual
        // path on disk where Livewire already saved the chunk.
        $fullPath = $this->mdbFile->getRealPath();

        if (! $fullPath || ! file_exists($fullPath)) {
            // Fallback: copy to a known location with the correct .mdb extension
            $dest = storage_path('app/temp/mdb/'.$original);
            if (! is_dir(dirname($dest))) {
                mkdir(dirname($dest), 0755, true);
            }
            $this->mdbFile->move(dirname($dest), $original);
            $fullPath = $dest;
        }

        $log = MdbUploadLog::create([
            'filename' => $original,
            'uploaded_by' => Auth::id(),
            'status' => 'failed',
        ]);

        try {
            $result = $service->importFromMdb($fullPath, $original, Auth::id());

            // Process each date found in the import
            foreach ($result['dates'] as $dateStr) {
                $service->processDate(Carbon::parse($dateStr));
            }

            $log->update([
                'records_imported' => $result['imported'],
                'records_skipped' => $result['skipped'],
                'employees_unmatched' => $result['unmatched'],
                'dates_processed' => $result['dates'],
                'status' => 'success',
            ]);

            $this->uploadResult = [
                'type' => 'success',
                'imported' => $result['imported'],
                'skipped' => $result['skipped'],
                'unmatched' => $result['unmatched'],
                'dates' => count($result['dates']),
            ];

            // Jump to the first date processed
            if (! empty($result['dates'])) {
                $this->selectedDate = $result['dates'][0];
            }

        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $this->uploadResult = [
                'type' => 'error',
                'message' => $e->getMessage(),
            ];
        } finally {
            // Clean up temp file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $this->mdbFile = null;
    }

    // ── Manual re-process for selected date ─────────────

    public function processSelectedDate(AttendanceService $service): void
    {
        $this->toastMessage = null;
        $this->toastError = null;

        try {
            $count = $service->processDate(Carbon::parse($this->selectedDate));
            $this->toastMessage = "Processed {$count} employee record(s) for {$this->selectedDate}.";
        } catch (\Throwable $e) {
            $this->toastError = 'Processing failed: '.$e->getMessage();
        }
    }

    // ── Render ───────────────────────────────────────────

    public function render()
    {
        $date = $this->selectedDate ?: today()->toDateString();

        $query = AttendanceSummary::with(['user', 'user.employee'])
            ->where('attendance_date', $date);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('employee_number', 'like', "%{$this->search}%")
            );
        }

        $records = $query->get();

        // Summary stats for selected date
        $all = AttendanceSummary::where('attendance_date', $date)->get();
        $stats = [
            'total' => $all->count(),
            'on_time' => $all->where('status', 'on_time')->count(),
            'late' => $all->whereIn('status', ['late_am', 'late_pm', 'late_both', 'late'])->count(),
            'half_day' => $all->whereIn('status', ['half_day_am', 'half_day_pm'])->count(),
            'absent' => $all->where('status', 'absent')->count(),
            'overtime' => $all->where('overtime_hours', '>', 0)->count(),
        ];

        $recentUploads = MdbUploadLog::latest()->limit(5)->get();

        return view('pages.HR.attendance-management', compact('records', 'stats', 'recentUploads'))
            ->layout('layouts.app');
    }
}
