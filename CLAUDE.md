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

Login blocks users where `is_active = false`. Users without a known position land on `users.waiting`.

### Data Model

- `users` — `employee_number`, `username`, `is_active`; **no role column** — position comes from `employment_details`
- `employees` — links `user_id` to `employment_details`
- `employment_details` — `position`, `hiring_date`, `department_id`; `hiring_date` drives VL/SL credit calculations
- `departments` — `name`, `code`, `dept_head_id`
- `leaves` — `dept_head_status` + `hr_status` for two-stage approval; `attachment` for medical certs; `reliever` field
- `records` — maintenance checklist entries joined via `location_area_parts → location_areas → locations`
- `medicines` — `quantity`, `reorder_level`, `status` (active/inactive)
- `sales` + `sales_items` — POS transactions; `customers` tracks credit `balance`/`charges`

**Key relationship chain:** `User → hasOne Employee → hasOne EmploymentDetail` (accessed as `$user->employmentDetail` via `hasOneThrough`). Department is reached via `$user->employmentDetail->department`.

### Key Livewire Components

- `app/Livewire/LeaveForm.php` — VL/SL credit calculation from `hiring_date`; supports AM/PM half-days; uses `<x-custom-select>` for Android-safe dropdowns
- `app/Livewire/HrLeaveManagement.php` — Two-stage approval: Department Head → HR
- `app/Livewire/DHead.php` — Department Head leave approval view
- `app/Livewire/MaintenanceDashboard.php` — KPI stats from `records` table (today/week/pending/flagged) filtered by `maintenance_name`
- `app/Livewire/DepartmentManagement.php` — Department CRUD; dept head select from active users
- `app/Livewire/HRCorner.php` — HR dashboard; department headcount via `employment_details.department_id` join
- `app/Livewire/DispenseMedicine.php` — Atomic DB transaction for stock decrement
- `app/Livewire/PointofSale/POS.php` — Cart with budget meal bundles, multi-payment (Cash/GCash/Credit), DB transaction rollback

### Frontend Conventions

- Tailwind CSS + FluxUI (`flux/` components) + DaisyUI
- Livewire Volt used alongside standard Livewire classes
- **Android-safe dropdowns**: Use `<x-custom-select>` (Alpine.js) instead of native `<select>` for mobile-facing pages. Props: `wire-property`, `:current`, `:options` (array of `['value'=>,'label'=>]`), `placeholder`, `:error`
- DomPDF for PDF generation (`resources/views/pdf/`)
- Vite bundles assets; Flux requires `FLUX_USERNAME`/`FLUX_LICENSE_KEY` secrets for CI

### Authentication

- Laravel Fortify with **username-based login** (not email)
- Email verification required before first login; two-factor auth supported
- Config: `config/fortify.php`

### CI/CD

GitHub Actions on push to `develop`/`main`: lint (`.github/workflows/lint.yml`) and tests on PHP 8.4 + 8.5 (`.github/workflows/tests.yml`).
