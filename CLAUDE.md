# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Full project setup (install deps, app key, migrations, build frontend)
composer setup

# Run dev servers in parallel (PHP serve + queue listener + Vite)
composer dev

# Run tests (clears config, runs Pint lint, then PHPUnit)
composer test

# Check code style only
composer test:lint

# Auto-fix code style
composer lint

# Frontend only
npm run dev
npm run build
```

## Architecture

**NLAH (Northern Luzon Adventist Hospital) Management System** — a multi-module Laravel 12 + Livewire 4 app serving hospital staff across several domains.

### Modules & Access Control

Gates are defined in `app/Providers/AppServiceProvider.php` — all checks use `$user->employmentDetail?->position` (not a `role` column). Login redirects by position are in `app/Providers/FortifyServiceProvider.php`.

| Position | Gate | Module |
|----------|------|--------|
| `Staff` | — | Leave form (`/LeaveForm/leave`) |
| `Department Head` | `access-dept-head` | Dept head leave queue (`/LeaveForm/dhead`) |
| `Maintenance` / `Housekeeping` | `access-maintenance` | Maintenance dashboard + checklists |
| `Maintenance_Head` / `Inspector` | `can-maintenance-or-verify` | Verification |
| `HR Manager` | `access-hr-only`, `access-payroll` | HR management (`/HR`) — `Gate::before` gives super-access |
| `Cashier` | `access-cashier-only` | Point of Sale (`/pos`) |
| inactive (`is_active=false`) | — | Blocked at login |

Users without a matched position land on `users.waiting`. The `/nursing/schedule` route is accessible to **all authenticated users** (no gate guard).

### Data Model

**Key relationship chain:** `User → hasOne Employee → hasOne EmploymentDetail` (accessed as `$user->employmentDetail` via `hasOneThrough`). Department via `$user->employmentDetail->department`.

- `users` — `employee_number`, `username`, `is_active`; **no role column**
- `employee` (table name) — `user_id`, `is_solo_parent` (bool, drives SPL eligibility)
- `employment_details` — `position`, `hiring_date`, `regularization_date`, `department_id`; `hiring_date` drives VL anniversary; setting `regularization_date` auto-triggers a transition grant via `EmploymentDetail::booted()`
- `departments` — `dept_head_id` points to a `users` record
- `leave_types` — DB-driven leave catalogue (`code`, `label`, `annual_days`, `reset_type`, `solo_parent_only`, `is_paid`, `requires_attachment`). **Do not hardcode leave type strings** — use `LeaveType::resolve($codeOrLabel)` and `$lt->getPayrollKey()` to map to balance columns
- `leave_balances` — **normalized** per-employee leave credits: `(user_id, leave_type_id, total, consumed)` with soft-deletes. Primary source for balance reads/writes
- `payroll_and_leaves` — **legacy** flat balance columns (`vl_total/vl_consumed`, `sl_total/sl_consumed`, `bl_total/bl_consumed`, `spl_total/spl_consumed`, `ml_total/ml_consumed`, `pl_total/pl_consumed`, `syl_total/syl_consumed`); also stores `initial_transition_grant`, `years_accrued_count`. Still used by `EmployeeManagement` for bulk import/edit
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

- `app/Livewire/LeaveForm.php` — staff self-service leave filing; types from DB; solo parent + probation filters
- `app/Livewire/DHead.php` — dept head approval queue + own leave filing (auto-approves dept_head step)
- `app/Livewire/HrLeaveManagement.php` — HR final approval; `restoreConsumed()` handles code/label duality
- `app/Livewire/EmployeeManagement.php` — full employee CRUD across `employee`, `employment_details`, `payroll_and_leaves`; all leave balances (VL/SL/BL/SPL/ML/PL/SYL) editable for data import
- `app/Livewire/NurseSchedule.php` — date-based nurse scheduler; `assignEmployee()`, `assignCustom()`, `removeEntry()`
- `app/Livewire/AttendanceManagement.php` — biometric log import + attendance summary
- `app/Livewire/OvertimeManagement.php` / `HrOvertimeManagement.php` — overtime application workflow
- `app/Livewire/PayoffManagement.php` / `HrPayoffManagement.php` — cash payoff application workflow
- `app/Livewire/MaintenanceDashboard.php` — KPI stats from `records` filtered by `maintenance_name`
- `app/Livewire/PointofSale/POS.php` — cart with budget meal bundles, multi-payment (Cash/GCash/Credit), DB transaction rollback
- `app/Livewire/Assets.php` — asset inventory with transaction records

### Frontend Conventions

- Tailwind CSS + FluxUI (`flux/` components) + DaisyUI
- Livewire Volt used alongside standard Livewire classes
- **Android-safe dropdowns**: Use `<x-custom-select>` (Alpine.js) instead of native `<select>` for mobile-facing pages. Props: `wire-property`, `:current`, `:options` (array of `['value'=>,'label'=>]`), `placeholder`, `:error`
- **Livewire + Alpine coexistence**: When Alpine state must survive a Livewire re-render, use `Alpine.store()` (global, never touched by morphdom). Add `wire:key` to dynamically rendered elements so morphdom patches in-place instead of replacing. Use `wire:ignore.self` on modal wrappers if Alpine controls visibility.
- DomPDF for PDF generation (`resources/views/pdf/`)
- Vite bundles assets; Flux requires `FLUX_USERNAME`/`FLUX_LICENSE_KEY` secrets for CI

### AI Chat (NLAH Wellness Companion)

`POST /nlah/chat` proxies to a local **Ollama** instance (`config('services.ollama.host')` / `.model`). System prompt enforces identity as "NLAH Wellness Companion" and bans markdown. Supports image analysis when model is vision-capable (llava, moondream, qwen2-vl). Rate-limited to 30 req/min. Implemented entirely in `routes/web.php`.

### PWA + Offline Sync (Maintenance Checklists)

The maintenance checklist (`/Maintenance/checklist/check`) is a PWA. Service worker at `public/sw.js`; manifest at `public/manifest.json`. Registered in `resources/views/partials/head.blade.php`.

When offline, proof photos are queued to **IndexedDB** (`nlah-checklist` / `pending` store) inside `resources/views/components/checklist-proof-camera-modal.blade.php`. On reconnect the queue is flushed via `POST /api/maintenance/checklist/sync` → `ChecklistSyncController`. The SW skips `/livewire/` and the sync endpoint (never cached).

### Authentication

- Laravel Fortify with **username-based login** (not email)
- Email verification required before first login; two-factor auth supported
- Config: `config/fortify.php`

### CI/CD

GitHub Actions on push to `develop`/`main`: lint (`.github/workflows/lint.yml`) and tests on PHP 8.4 + 8.5 (`.github/workflows/tests.yml`).
