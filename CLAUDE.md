# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Full project setup (install deps, app key, migrations, build frontend)
composer setup

# Run dev servers in parallel (PHP at 192.168.100.55:8888 + queue listener + Vite)
composer dev

# Run tests (clears config, runs Pint lint, then PHPUnit)
composer test

# Run a single test file or filter by name
php artisan test --filter=TestName
./vendor/bin/phpunit tests/Feature/SomeTest.php

# Check code style only
composer test:lint

# Auto-fix code style
composer lint

# Frontend only
npm run dev
npm run build

# Leave-related artisan commands
php artisan leave:process-anniversaries   # VL on hiring anniversary
php artisan leave:process-annual-reset    # SL/BL/SPL reset on Jan 1
```

## Architecture

**NLAH (Northern Luzon Adventist Hospital) Management System** — a multi-module Laravel 12 + Livewire 4 app serving hospital staff across several domains.

### Modules & Access Control

Gates are defined in `app/Providers/AppServiceProvider.php`. **All gate checks use `$user->accessKey?->hasPermission($slug)`** — the `AccessKey` model stores a `permissions` JSON array and an `is_super` boolean. A super access key bypasses all gates via `Gate::before`. Users without an assigned access key are kicked out at login by `EnforceAccessKey` middleware.

Login redirect is driven by `$user->accessKey->redirect_to` (a named route stored on the AccessKey record), configured in `FortifyServiceProvider`. Users whose access key has no matching route land on `users.waiting`.

| Gate / Middleware | Permission Slug | Module |
|---|---|---|
| `access-dept-head` | `access-dept-head` | Dept head leave queue (`/LeaveForm/dhead`) |
| `access-maintenance` | `access-maintenance` | Maintenance dashboard + checklists |
| `access-verify` | `access-verify` | Checklist verification |
| `can-maintenance-or-verify` (custom middleware) | `access-verify` OR `access-hr-only` | Verify route |
| `access-hr-only` | `access-hr-only` | All HR routes (`/HR`) — also bypasses verify |
| `access-payroll` | `access-payroll` | Payroll-specific HR sub-routes |
| `access-cashier-only` | `access-cashier-only` | Point of Sale (`/pos`) |
| `access-medical` | `access-medical` | Medical module (currently commented out) |
| `is_super = true` on AccessKey | — | Bypasses all gates |

`can-maintenance-or-verify` is `App\Http\Middleware\CanAccessMaintenanceOrVerify` — it calls `Gate::check('access-verify') || Gate::check('access-hr-only')`.

The `/` root route still does a position-based redirect for convenience (Housekeeping → Maintenance.dashboard, etc.) but gates themselves are entirely AccessKey-driven.

### Data Model

**Key relationship chain:** `User → hasOne Employee → hasOne EmploymentDetail` (accessed as `$user->employmentDetail` via `hasOneThrough`). Department via `$user->employmentDetail->department`. Access key via `$user->accessKey` (belongsTo `AccessKey`).

- `users` — `employee_number`, `username`, `is_active`, `access_key_id`; **no role column**
- `employee` (table name) — `user_id`, `is_solo_parent` (bool, drives SPL eligibility)
- `employment_details` — `position`, `hiring_date`, `regularization_date`, `department_id`; `hiring_date` drives VL anniversary; setting `regularization_date` auto-triggers a transition grant via `EmploymentDetail::booted()`
- `access_keys` — `name`, `redirect_to` (named route), `is_super` (bool), `permissions` (JSON array of slug strings)
- `departments` — `dept_head_id` points to a `users` record
- `leave_types` — DB-driven leave catalogue (`code`, `label`, `annual_days`, `reset_type`, `solo_parent_only`, `is_paid`, `requires_attachment`). **Do not hardcode leave type strings** — use `LeaveType::resolve($codeOrLabel)` and `$lt->getPayrollKey()` to map to balance columns
- `leave_balances` — **normalized** per-employee leave credits: `(user_id, leave_type_id, total, consumed)` with soft-deletes. Primary source for balance reads/writes
- `payroll_and_leaves` — **legacy** flat balance columns (`vl_total/vl_consumed`, `sl_total/sl_consumed`, etc.); also stores `initial_transition_grant`, `years_accrued_count`. Still used by `EmployeeManagement` for bulk import/edit
- `leaves` — `dept_head_status` + `hr_status` for two-stage approval; `leave_type` stores LeaveType **code** (e.g. `VL`, `SL`) — legacy records may store full label; `LeaveType::resolve()` handles both
- `nurse_schedule_entries` — `(schedule_date, section, slot, period, employee_id, custom_name)`; section = `ward|dr_or|head_nurse`; slot = `1st|2nd|3rd|4th|5th|OPD`; period = `am|pm`
- `records` — maintenance checklist entries joined via `location_area_parts → location_areas → locations`
- `sales` + `sales_items` — POS transactions; `customers` tracks credit `balance`/`charges`
- `overtime_applications` / `payoff_applications` — employee self-service overtime and cash payoff requests

### Leave System

Two-stage approval: **Staff → Dept Head → HR**. Dept Heads bypass dept_head step for their own leaves (auto-approved, sent to HR directly).

**Balance lookup** — `LeaveType->getPayrollKey()` returns `'vl'|'sl'|'bl'|'spl'|'el'|'ml'|'pl'|'syl'|'cal'|'stl'|'mwl'|null`. Sub-types `SL_X` / `SL_M` share the `sl` bucket via `getCanonicalCode()`. LWOP returns `null` and `isLWOP()` returns `true`.

**Probationary employees** (no `regularization_date`) cannot apply for VL, SL, or BL. Expected regularization = `hiring_date + 6 months` (`LeaveAccrualService::computeExpectedRegularizationDate()`).

**Transition grant** — triggered when HR sets `regularization_date` on `EmploymentDetail` (`booted()` hook → `LeaveAccrualService::onRegularization()`).

**Annual resets** — `leave:process-anniversaries` (VL on hiring anniversary) and `leave:process-annual-reset` (SL/BL/SPL on Jan 1).

**Mail chain:**
- Staff submits → `LeaveRequestMail` to dept head (or `LeaveHRNotificationMail` to HR if no dept head)
- Dept head decides → `LeaveDHeadDecisionMail` to staff; if approved: `LeaveHRNotificationMail` to HR
- HR decides → `LeaveStatusUpdateMail` to staff + `LeaveHRResultMail` to dept head
- Cancellation: `LeaveCancellationDHeadMail` to dept head → `LeaveCancellationDHeadDecisionMail` to staff; HR result → `LeaveCancellationResultMail`

### Attendance

`AttendanceService` processes `biometric_logs` into `attendance_summaries`. Two shift modes:
- **Office** (ACCOUNTING, HR, PHARMACY, etc.): requires AM + PM check-in/out; AM grace cutoff 08:15, PM 13:15
- **Nurse/Duty** (NURSING, DIETARY, MAINTENANCE, etc.): single check-in / check-out pair

Late alerts sent via `LateAlertMail`. Import biometric logs via `AttendanceManagement` Livewire component.

### Nursing Scheduler

`/nursing/schedule` → `NurseSchedule` Livewire component. Schedule grid has three sections (`ward`, `dr_or`, `head_nurse`), each with slots and AM/PM periods. One nurse per slot/period. Uses **Alpine.store('nurseModal')** for the assign modal — modal state lives in the global store so it survives Livewire re-renders (morphdom never touches `Alpine.store`). The Blade template uses `wire:key` on shift cells and nurse pill entries to give morphdom stable references.

### Key Livewire Components

**Leave & HR:**
- `app/Livewire/LeaveForm.php` — staff self-service leave filing; types from DB; solo parent + probation filters
- `app/Livewire/DHead.php` — dept head approval queue + own leave filing (auto-approves dept_head step)
- `app/Livewire/HrLeaveManagement.php` — HR final approval; `restoreConsumed()` handles code/label duality
- `app/Livewire/EmployeeManagement.php` — full employee CRUD across `employee`, `employment_details`, `payroll_and_leaves`
- `app/Livewire/LeaveTypeManagement.php` — HR manages the `leave_types` catalogue
- `app/Livewire/HolidayManagement.php` — HR manages holidays
- `app/Livewire/PositionManagement.php` — HR manages positions table
- `app/Livewire/AccessKeyManagement.php` — HR creates/assigns access keys (controls all gate permissions)
- `app/Livewire/DepartmentManagement.php` — manages departments and dept head assignments
- `app/Livewire/HrApplicationsManagement.php` — HR overview of overtime/payoff applications
- `app/Livewire/PayrollCompliance.php` — payroll compliance view
- `app/Livewire/OvertimeManagement.php` / `HrOvertimeManagement.php` — overtime application workflow
- `app/Livewire/PayoffManagement.php` / `HrPayoffManagement.php` — cash payoff application workflow

**Operations:**
- `app/Livewire/NurseSchedule.php` — date-based nurse scheduler; `assignEmployee()`, `assignCustom()`, `removeEntry()`
- `app/Livewire/AttendanceManagement.php` — biometric log import + attendance summary
- `app/Livewire/MaintenanceDashboard.php` — KPI stats from `records` filtered by `maintenance_name`
- `app/Livewire/PointofSale/POS.php` — cart with budget meal bundles, multi-payment (Cash/GCash/Credit), DB transaction rollback
- `app/Livewire/Assets.php` — asset inventory with transaction records
- `app/Livewire/Transfer.php` — asset transfer management

### Frontend Conventions

- Tailwind CSS + FluxUI (`flux/` components) + DaisyUI
- Livewire Volt used alongside standard Livewire classes
- **Android-safe dropdowns**: Use `<x-custom-select>` (Alpine.js) instead of native `<select>` for mobile-facing pages. Props: `wire-property`, `:current`, `:options` (array of `['value'=>,'label'=>]`), `placeholder`, `:error`
- **Livewire + Alpine coexistence**: When Alpine state must survive a Livewire re-render, use `Alpine.store()` (global, never touched by morphdom). Add `wire:key` to dynamically rendered elements so morphdom patches in-place instead of replacing. Use `wire:ignore.self` on modal wrappers if Alpine controls visibility.
- DomPDF for PDF generation (`resources/views/pdf/`)
- Vite bundles assets; Flux requires `FLUX_USERNAME`/`FLUX_LICENSE_KEY` secrets for CI

### AI Chat (NLAH Wellness Companion)

`POST /nlah/chat` proxies to a local **Ollama** instance (`config('services.ollama.host')` / `.model`). System prompt enforces identity as "NLAH Wellness Companion" and bans markdown. Supports image analysis when model is vision-capable (llava, moondream, qwen2-vl). Rate-limited to 30 req/min. Implemented entirely in `routes/web.php`. `google-gemini-php/client` is also installed for Gemini integration.

### PWA + Offline Sync (Maintenance Checklists)

The maintenance checklist (`/Maintenance/checklist/check`) is a PWA. Service worker at `public/sw.js`; manifest at `public/manifest.json`. Registered in `resources/views/partials/head.blade.php`.

When offline, proof photos are queued to **IndexedDB** (`nlah-checklist` / `pending` store) inside `resources/views/components/checklist-proof-camera-modal.blade.php`. On reconnect the queue is flushed via `POST /api/maintenance/checklist/sync` → `ChecklistSyncController`. The SW skips `/livewire/` and the sync endpoint (never cached).

### Authentication

- Laravel Fortify with **username-based login** (not email)
- Registration triggers email verification; user is logged out immediately after registering and must verify before their first login
- Login redirect: driven by `AccessKey->redirect_to` (named route). Users with no access key see `users.waiting` with an error.
- `EnforceAccessKey` middleware kicks out authenticated users if their access key is removed mid-session
- Two-factor auth supported; config: `config/fortify.php`

### CI/CD

GitHub Actions on push to `develop`/`main`: lint (`.github/workflows/lint.yml`) and tests on PHP 8.4 + 8.5 (`.github/workflows/tests.yml`). Tests use SQLite in-memory database.
