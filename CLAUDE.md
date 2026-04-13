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

**NLAH (Northern Luzon Adventist Hospital) Management System** — a multi-module Laravel 12 + Livewire 4 app serving hospital staff across four main domains.

### Modules & Access Control

Gates are defined in `app/Providers/AppServiceProvider.php` — all checks use `$user->employmentDetail?->position` (not a `role` column). Login redirects by position are in `app/Providers/FortifyServiceProvider.php`.

| Position | Gate | Module |
|----------|------|--------|
| `Staff` | `access-medical` | Medical Mission (`/medmission`) |
| `Maintenance` | `access-maintenance` | Maintenance dashboard + checklists |
| `Inspector` | `access-verify` | Verification |
| `HR Manager` | `access-hr-only`, `access-payroll` | HR management (`/HR`) — also has `Gate::before` super-access |
| `Cashier` | `access-cashier-only` | Point of Sale (`/pos`) |
| inactive (`is_active=false`) | — | Blocked at login |

Users without a known position land on `users.waiting`.

### Data Model

**Key relationship chain:** `User → hasOne Employee → hasOne EmploymentDetail` (accessed as `$user->employmentDetail` via `hasOneThrough`). Department reached via `$user->employmentDetail->department`.

- `users` — `employee_number`, `username`, `is_active`; **no role column**
- `employee` (table name) — `user_id`, `is_solo_parent` (bool, drives SPL eligibility)
- `employment_details` — `position`, `hiring_date`, `regularization_date`, `department_id`; `hiring_date` drives VL anniversary increments; setting `regularization_date` triggers a VL transition grant automatically via `EmploymentDetail::booted()`
- `departments` — `dept_head_id` points to a `users` record
- `leave_types` — DB-driven leave catalogue (`code`, `label`, `annual_days`, `reset_type`, `solo_parent_only`, `is_paid`, `requires_attachment`). **Do not hardcode leave type strings** — use `LeaveType::resolve($codeOrLabel)` and `$lt->getPayrollKey()` to map to `payroll_and_leaves` columns
- `payroll_and_leaves` — per-employee balances: `vl_total/vl_consumed`, `sl_total/sl_consumed`, `bl_total/bl_consumed`, `spl_total/spl_consumed`, `el_total/el_consumed`; also stores `initial_transition_grant`, `years_accrued_count`
- `leaves` — `dept_head_status` + `hr_status` for two-stage approval; `leave_type` stores the LeaveType **code** (e.g. `VL`, `SL`) for records created after the DB-driven refactor; legacy records may store the full label string — `LeaveType::resolve()` handles both
- `records` — maintenance checklist entries joined via `location_area_parts → location_areas → locations`
- `sales` + `sales_items` — POS transactions; `customers` tracks credit `balance`/`charges`

### Leave System

Two-stage approval: **Staff → Dept Head → HR**. Department Heads bypass dept_head approval for their own leaves (auto-approved, sent directly to HR).

**Credit cap logic** — always use `LeaveType->getPayrollKey()`: returns `'vl'|'sl'|'bl'|'spl'|'el'|null`. If null, the type has no tracked balance (LWOP returns `-1` via `isLWOP()`). Payroll columns follow the pattern `{key}_total` / `{key}_consumed`.

**Probationary employees** (no `regularization_date`) cannot apply for VL, SL, or BL — these are filtered from the dropdown. Expected regularization date = `hiring_date + 6 months` (`LeaveAccrualService::computeExpectedRegularizationDate()`).

**Transition grant** — called automatically when HR sets `regularization_date` on `EmploymentDetail` (via `booted()` hook → `LeaveAccrualService::onRegularization()`). Grant amount is month-based via `computeTransitionGrant()`.

**Annual resets** — `leave:process-anniversaries` (VL increments on hiring anniversary) and `leave:process-annual-reset` (SL/BL/SPL reset on Jan 1) are scheduled console commands.

**Mail chain:**
- Staff submits → `LeaveRequestMail` to dept head (or `LeaveHRNotificationMail` to HR if no dept head configured)
- Dept head decides → `LeaveDHeadDecisionMail` to staff; if approved: `LeaveHRNotificationMail` to HR
- HR decides → `LeaveStatusUpdateMail` to staff + `LeaveHRResultMail` to dept head
- Cancellation request → `LeaveCancellationRequestMail` to HR; result → `LeaveCancellationResultMail`

### Key Livewire Components

- `app/Livewire/LeaveForm.php` — employee self-service leave filing; leave types loaded from DB; solo parent filter; probation filter
- `app/Livewire/DHead.php` — dept head approval queue + own leave filing (auto-approves dept_head step)
- `app/Livewire/HrLeaveManagement.php` — HR final approval; `restoreConsumed()` uses `LeaveType::resolve()` to handle code/label duality
- `app/Livewire/EmployeeManagement.php` — full employee CRUD across three DB tables (`employee`, `employment_details`, `payroll_and_leaves`); also manages `is_solo_parent`
- `app/Livewire/MaintenanceDashboard.php` — KPI stats from `records` table filtered by `maintenance_name`
- `app/Livewire/PointofSale/POS.php` — cart with budget meal bundles, multi-payment (Cash/GCash/Credit), DB transaction rollback

### Frontend Conventions

- Tailwind CSS + FluxUI (`flux/` components) + DaisyUI
- Livewire Volt used alongside standard Livewire classes
- **Android-safe dropdowns**: Use `<x-custom-select>` (Alpine.js) instead of native `<select>` for mobile-facing pages. Props: `wire-property`, `:current`, `:options` (array of `['value'=>,'label'=>]`), `placeholder`, `:error`
- DomPDF for PDF generation (`resources/views/pdf/`)
- Vite bundles assets; Flux requires `FLUX_USERNAME`/`FLUX_LICENSE_KEY` secrets for CI

### PWA + Offline Sync (Maintenance Checklists)

The maintenance checklist (`/Maintenance/checklist/check`) is a PWA. Service worker at `public/sw.js`; manifest at `public/manifest.json`. Registered in `resources/views/partials/head.blade.php`.

When offline, proof photos are queued to **IndexedDB** (`nlah-checklist` / `pending` store) inside `resources/views/components/checklist-proof-camera-modal.blade.php`. On reconnect the queue is flushed via `POST /api/maintenance/checklist/sync` → `ChecklistSyncController`. The SW skips `/livewire/` and the sync endpoint (never cached).

### Authentication

- Laravel Fortify with **username-based login** (not email)
- Email verification required before first login; two-factor auth supported
- Config: `config/fortify.php`

### CI/CD

GitHub Actions on push to `develop`/`main`: lint (`.github/workflows/lint.yml`) and tests on PHP 8.4 + 8.5 (`.github/workflows/tests.yml`).
