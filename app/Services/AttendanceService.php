<?php

namespace App\Services;

use App\Mail\LateAlertMail;
use App\Models\AttendanceSummary;
use App\Models\BiometricLog;
use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\MdbUploadLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class AttendanceService
{
    // Shift 1 (Office): require AM and PM check-in/out
    const OFFICE_DEPTS = [
        'ACCOUNTING', 'PHILHEALTH', 'HUMAN RESOURCE', 'ADMINISTRATIVE',
        'MEDICAL RECORDS', 'ADMITTING', 'MIS', 'LABORATORY',
        'RADIOLOGY', 'PHARMACY', 'SOCIAL WORKER', 'PASTOR',
        'LIAISON', 'AMBULANCE', 'CSR',
    ];

    // Shift 2 (Nurses/Duty): single check-in / check-out
    const NURSE_DEPTS = [
        'NURSING', 'DIETARY', 'JANITORIAL', 'LAUNDRY', 'MAINTENANCE',
    ];

    // Grace periods (HH:MM)
    const AM_CUTOFF  = '08:15'; // on-time if ≤ this
    const PM_CUTOFF  = '13:15';
    const SHIFT_END  = '17:00'; // office end-of-day for OT calculation

    // Morning window: 05:00–12:29 | Afternoon: 12:30–23:59
    const MORNING_BOUNDARY = '12:30';

    // ─────────────────────────────────────────────────────
    //  MDB IMPORT
    // ─────────────────────────────────────────────────────

    /**
     * Read a ZKTeco att2000.mdb file on Windows using PDO ODBC (ext-pdo_odbc).
     * The Microsoft Access ODBC driver ships with Windows — no extra install needed.
     * Returns ['imported', 'skipped', 'unmatched', 'dates'].
     */
    public function importFromMdb(string $filePath, string $originalName, int $uploadedBy): array
    {
        // Ensure the file actually exists before handing to ODBC
        if (! file_exists($filePath)) {
            throw new \RuntimeException("Uploaded file not found at: {$filePath}");
        }

        // ODBC Access driver requires all backslashes and NO quotes around the path
        $winPath = str_replace('/', '\\', realpath($filePath));

        // Try PDO ODBC first (ext-pdo_odbc), then fall back to odbc_connect (ext-odbc)
        if (extension_loaded('pdo_odbc')) {
            $dsn = 'odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=' . $winPath . ';Uid=;Pwd=;';
            try {
                $pdo = new \PDO($dsn, '', '', [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            } catch (\PDOException $e) {
                throw new \RuntimeException('Could not open MDB via PDO ODBC: ' . $e->getMessage());
            }

            return $this->readMdbViaPdo($pdo, $originalName, $uploadedBy);
        }

        if (function_exists('odbc_connect')) {
            $dsn = 'Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=' . $winPath . ';Uid=;Pwd=;';
            $conn = @odbc_connect($dsn, '', '');
            if (! $conn) {
                throw new \RuntimeException('Could not open MDB via ODBC. Check that ext-pdo_odbc or ext-odbc is enabled in php.ini.');
            }
            try {
                return $this->readMdbViaOdbc($conn, $originalName, $uploadedBy);
            } finally {
                odbc_close($conn);
            }
        }

        throw new \RuntimeException('Neither ext-pdo_odbc nor ext-odbc is enabled. Enable one in php.ini to process MDB files.');
    }

    private function readMdbViaPdo(\PDO $pdo, string $originalName, int $uploadedBy): array
    {
        // ── Step 1: USERID → BADGENUMBER from USERINFO ──
        $badgeMap = [];
        try {
            foreach ($pdo->query('SELECT USERID, BADGENUMBER FROM USERINFO') as $row) {
                $badgeMap[(int) $row['USERID']] = (string) $row['BADGENUMBER'];
            }
        } catch (\Throwable) {
            // USERINFO table absent in some exports
        }

        $userMap = $this->buildUserMap($badgeMap);

        // ── Step 2: Read CHECKINOUT ──
        $imported  = 0;
        $skipped   = 0;
        $unmatched = 0;
        $dates     = [];

        $stmt = $pdo->query('SELECT USERID, CHECKTIME, CHECKTYPE FROM CHECKINOUT ORDER BY CHECKTIME');
        foreach ($stmt as $row) {
            $zkId = (int) $row['USERID'];

            if (! isset($userMap[$zkId])) {
                $unmatched++;
                continue;
            }

            [$imported, $skipped, $dates] = $this->insertPunch(
                $userMap[$zkId],
                $row['CHECKTIME'],
                $row['CHECKTYPE'] ?? 0,
                $originalName,
                $imported, $skipped, $dates
            );
        }

        return compact('imported', 'skipped', 'unmatched', 'dates');
    }

    private function readMdbViaOdbc($conn, string $originalName, int $uploadedBy): array
    {
        // ── Step 1: USERID → BADGENUMBER from USERINFO ──
        $badgeMap = [];
        try {
            $rs = odbc_exec($conn, 'SELECT USERID, BADGENUMBER FROM USERINFO');
            while ($row = odbc_fetch_array($rs)) {
                $badgeMap[(int) $row['USERID']] = (string) $row['BADGENUMBER'];
            }
            odbc_free_result($rs);
        } catch (\Throwable) {}

        $userMap = $this->buildUserMap($badgeMap);

        // ── Step 2: Read CHECKINOUT ──
        $imported  = 0;
        $skipped   = 0;
        $unmatched = 0;
        $dates     = [];

        $rs = odbc_exec($conn, 'SELECT USERID, CHECKTIME, CHECKTYPE FROM CHECKINOUT ORDER BY CHECKTIME');
        while ($row = odbc_fetch_array($rs)) {
            $zkId = (int) $row['USERID'];

            if (! isset($userMap[$zkId])) {
                $unmatched++;
                continue;
            }

            [$imported, $skipped, $dates] = $this->insertPunch(
                $userMap[$zkId],
                $row['CHECKTIME'],
                $row['CHECKTYPE'] ?? 0,
                $originalName,
                $imported, $skipped, $dates
            );
        }
        odbc_free_result($rs);

        return compact('imported', 'skipped', 'unmatched', 'dates');
    }

    private function buildUserMap(array $badgeMap): array
    {
        $userMap = [];
        foreach ($badgeMap as $zkId => $badge) {
            $emp = Employee::where('biometric_id', $badge)->whereNotNull('user_id')->first();
            if ($emp) {
                $userMap[$zkId] = ['user_id' => $emp->user_id, 'biometric_id' => $badge];
            }
        }

        return $userMap;
    }

    private function insertPunch(
        array $mapped, mixed $checktime, mixed $checktype,
        string $originalName,
        int $imported, int $skipped, array $dates
    ): array {
        $punchTime = Carbon::parse($checktime);
        $punchType = $this->normalizePunchType($checktype);
        $dateKey   = $punchTime->toDateString();

        try {
            BiometricLog::create([
                'biometric_id' => $mapped['biometric_id'],
                'user_id'      => $mapped['user_id'],
                'punch_time'   => $punchTime,
                'punch_type'   => $punchType,
                'is_processed' => false,
                'source_file'  => $originalName,
            ]);
            $imported++;
            $dates[$dateKey] = true;
        } catch (\Illuminate\Database\UniqueConstraintViolationException) {
            $skipped++;
        } catch (\Throwable) {
            $skipped++;
        }

        return [$imported, $skipped, $dates];
    }

    /**
     * Normalise CHECKTYPE from ZKTeco – can be int (0/1) or string ('I'/'O').
     */
    private function normalizePunchType(mixed $raw): int
    {
        if (is_string($raw)) {
            return strtoupper(trim($raw)) === 'O' ? 1 : 0;
        }

        return (int) $raw;
    }

    // ─────────────────────────────────────────────────────
    //  ATTENDANCE PROCESSING
    // ─────────────────────────────────────────────────────

    /**
     * Process attendance summaries for every employee on the given date.
     * Only employees who have a biometric_id AND a linked user_id are processed.
     */
    public function processDate(Carbon $date): int
    {
        $employees = Employee::whereNotNull('biometric_id')
                             ->whereNotNull('user_id')
                             ->get();

        $processed = 0;
        foreach ($employees as $employee) {
            $this->processEmployeeDay($employee, $date);
            $processed++;
        }

        return $processed;
    }

    private function processEmployeeDay(Employee $employee, Carbon $date): void
    {
        $logs = BiometricLog::where('user_id', $employee->user_id)
                            ->whereDate('punch_time', $date)
                            ->orderBy('punch_time')
                            ->get();

        $detail    = EmploymentDetail::where('user_id', $employee->user_id)->first();
        $dept      = strtoupper(trim($detail?->department ?? ''));
        $shiftType = $this->getShiftType($dept);

        $data = $shiftType === 'office'
            ? $this->calculateOffice($logs, $date)
            : $this->calculateNurse($logs, $date);

        $existing = AttendanceSummary::where('user_id', $employee->user_id)
                                     ->where('attendance_date', $date->toDateString())
                                     ->first();

        $shouldSendEmail = false;

        if ($existing) {
            // Only re-send email if status changed to a late variant
            $wasLate   = $this->isLateStatus($existing->status);
            $isNowLate = $this->isLateStatus($data['status']);
            if ($isNowLate && (! $wasLate || ! $existing->email_sent)) {
                $shouldSendEmail = true;
                $data['email_sent'] = false; // reset so we re-flag below
            }
            $existing->update(array_merge(['shift_type' => $shiftType], $data));
            $summary = $existing->fresh();
        } else {
            $summary = AttendanceSummary::create(array_merge(
                ['user_id' => $employee->user_id, 'attendance_date' => $date->toDateString(), 'shift_type' => $shiftType],
                $data
            ));
            $shouldSendEmail = $this->isLateStatus($data['status']);
        }

        // Mark biometric logs as processed
        BiometricLog::where('user_id', $employee->user_id)
                    ->whereDate('punch_time', $date)
                    ->update(['is_processed' => true]);

        // Send late alert email
        if ($shouldSendEmail) {
            $this->sendLateAlert($employee, $summary);
        }
    }

    // ─────────────────────────────────────────────────────
    //  SHIFT 1 – OFFICE WORKERS
    // ─────────────────────────────────────────────────────

    private function calculateOffice(Collection $logs, Carbon $date): array
    {
        if ($logs->isEmpty()) {
            return $this->absentRecord();
        }

        $morning   = $logs->filter(fn ($l) => $l->punch_time->format('H:i') < self::MORNING_BOUNDARY);
        $afternoon = $logs->filter(fn ($l) => $l->punch_time->format('H:i') >= self::MORNING_BOUNDARY);

        $amInLog  = $morning->first();
        $amOutLog = $morning->count() > 1 ? $morning->last() : null;
        $pmInLog  = $afternoon->first();
        $pmOutLog = $afternoon->count() > 1 ? $afternoon->last() : null;

        $hasAm = $amInLog !== null;
        $hasPm = $pmInLog !== null;

        // ── Status ──
        if (! $hasAm && ! $hasPm) {
            $status = 'absent';
        } elseif ($hasAm && ! $hasPm) {
            $status = 'half_day_am';
        } elseif (! $hasAm && $hasPm) {
            $status = 'half_day_pm';
        } else {
            $amLate = $amInLog->punch_time->format('H:i') > self::AM_CUTOFF;
            $pmLate = $pmInLog->punch_time->format('H:i') > self::PM_CUTOFF;

            $status = match (true) {
                ! $amLate && ! $pmLate => 'on_time',
                $amLate  && ! $pmLate => 'late_am',
                ! $amLate && $pmLate  => 'late_pm',
                default               => 'late_both',
            };
        }

        // ── Late minutes ──
        $lateMinutes = 0;
        if ($amInLog && $amInLog->punch_time->format('H:i') > self::AM_CUTOFF) {
            $cutoff      = Carbon::parse($date->format('Y-m-d') . ' ' . self::AM_CUTOFF . ':00');
            $lateMinutes += $amInLog->punch_time->diffInMinutes($cutoff);
        }
        if ($pmInLog && $pmInLog->punch_time->format('H:i') > self::PM_CUTOFF) {
            $cutoff       = Carbon::parse($date->format('Y-m-d') . ' ' . self::PM_CUTOFF . ':00');
            $lateMinutes += $pmInLog->punch_time->diffInMinutes($cutoff);
        }

        // ── Overtime (after 17:00) ──
        $overtimeHours = 0.0;
        $lastOut       = $pmOutLog ?? $amOutLog;
        if ($lastOut && $lastOut->punch_time->format('H:i') > self::SHIFT_END) {
            $endOfDay      = Carbon::parse($date->format('Y-m-d') . ' ' . self::SHIFT_END . ':00');
            $overtimeHours = round($lastOut->punch_time->diffInMinutes($endOfDay) / 60, 2);
        }

        // ── Total hours worked ──
        $totalHours = 0.0;
        if ($amInLog && $amOutLog) {
            $totalHours += $amInLog->punch_time->diffInMinutes($amOutLog->punch_time) / 60;
        }
        if ($pmInLog && $pmOutLog) {
            $totalHours += $pmInLog->punch_time->diffInMinutes($pmOutLog->punch_time) / 60;
        }
        $totalHours = round($totalHours, 2);

        return [
            'clock_in'         => $amInLog?->punch_time,
            'clock_out'        => ($pmOutLog ?? $amOutLog)?->punch_time,
            'am_in'            => $amInLog?->punch_time->format('H:i:s'),
            'am_out'           => $amOutLog?->punch_time->format('H:i:s'),
            'pm_in'            => $pmInLog?->punch_time->format('H:i:s'),
            'pm_out'           => $pmOutLog?->punch_time->format('H:i:s'),
            'status'           => $status,
            'late_minutes'     => $lateMinutes,
            'total_hours'      => $totalHours,
            'regular_hours'    => min($totalHours, 8),
            'overtime_hours'   => $overtimeHours,
            'night_diff_hours' => 0,
            'is_holiday'       => false,
            'email_sent'       => false,
        ];
    }

    // ─────────────────────────────────────────────────────
    //  SHIFT 2 – NURSES / DUTY STAFF
    // ─────────────────────────────────────────────────────

    private function calculateNurse(Collection $logs, Carbon $date): array
    {
        if ($logs->isEmpty()) {
            return $this->absentRecord();
        }

        $first   = $logs->first();
        $last    = $logs->last();
        $clockIn = $first->punch_time;

        // Infer shift start from clock-in time
        $hour       = (int) $clockIn->format('H');
        $shiftStart = match (true) {
            $hour >= 5  && $hour < 10  => Carbon::parse($date->format('Y-m-d') . ' 07:00:00'),
            $hour >= 12 && $hour < 16  => Carbon::parse($date->format('Y-m-d') . ' 15:00:00'),
            $hour >= 21 || $hour < 2   => Carbon::parse($date->format('Y-m-d') . ' 23:00:00'),
            default                     => $clockIn->copy(), // unknown shift, not late
        };

        $lateMinutes = 0;
        if ($clockIn->isAfter($shiftStart->copy()->addMinutes(10))) {
            $lateMinutes = (int) $clockIn->diffInMinutes($shiftStart);
        }

        $totalHours    = round($clockIn->diffInMinutes($last->punch_time) / 60, 2);
        $overtimeHours = round(max(0, $totalHours - 8), 2);

        $status = match (true) {
            $lateMinutes > 10    => 'late',
            $overtimeHours > 0  => 'overtime',
            default              => 'on_time',
        };

        // Night differential hours (22:00–06:00)
        $nightDiff = $this->calcNightDiff($clockIn, $last->punch_time);

        return [
            'clock_in'         => $clockIn,
            'clock_out'        => $last->punch_time,
            'am_in'            => null,
            'am_out'           => null,
            'pm_in'            => null,
            'pm_out'           => null,
            'status'           => $status,
            'late_minutes'     => $lateMinutes,
            'total_hours'      => $totalHours,
            'regular_hours'    => min($totalHours, 8),
            'overtime_hours'   => $overtimeHours,
            'night_diff_hours' => $nightDiff,
            'is_holiday'       => false,
            'email_sent'       => false,
        ];
    }

    // ─────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────

    private function absentRecord(): array
    {
        return [
            'clock_in' => null, 'clock_out' => null,
            'am_in' => null, 'am_out' => null,
            'pm_in' => null, 'pm_out' => null,
            'status' => 'absent', 'late_minutes' => 0,
            'total_hours' => 0, 'regular_hours' => 0,
            'overtime_hours' => 0, 'night_diff_hours' => 0,
            'is_holiday' => false, 'email_sent' => false,
        ];
    }

    private function getShiftType(string $dept): string
    {
        foreach (self::OFFICE_DEPTS as $d) {
            if (str_contains($dept, $d)) {
                return 'office';
            }
        }

        return 'nurse'; // default to nurse shift for unlisted/duty departments
    }

    private function isLateStatus(string $status): bool
    {
        return in_array($status, ['late_am', 'late_pm', 'late_both', 'late'], true);
    }

    /**
     * Approximate night differential hours (22:00–06:00) within the shift.
     */
    private function calcNightDiff(Carbon $start, Carbon $end): float
    {
        $nightMinutes = 0;
        $current      = $start->copy();

        while ($current < $end) {
            $h = (int) $current->format('H');
            if ($h >= 22 || $h < 6) {
                $nightMinutes++;
            }
            $current->addMinute();
        }

        return round($nightMinutes / 60, 2);
    }

    // ─────────────────────────────────────────────────────
    //  EMAIL
    // ─────────────────────────────────────────────────────

    private function sendLateAlert(Employee $employee, AttendanceSummary $summary): void
    {
        // Prefer employee's own email, fall back to linked user's email
        $email = $employee->email_add;
        if (empty($email) && $employee->user_id) {
            $email = $employee->user?->email;
        }

        if (empty($email)) {
            return;
        }

        try {
            Mail::to($email)->send(new LateAlertMail($employee, $summary));
            $summary->update(['email_sent' => true]);
        } catch (\Throwable) {
            // Don't fail the whole import if mail fails
        }
    }
}
