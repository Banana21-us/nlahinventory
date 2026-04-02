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

**NLAH (New Life Adventist Hospital) Management System** — a multi-module Laravel 12 + Livewire 4 app serving hospital staff across four main domains.

### Modules & Access Control

Role-based gates are defined in `app/Providers/AppServiceProvider.php`. Login redirects by role are in `app/Providers/FortifyServiceProvider.php`.

| Role | Gate | Module |
|------|------|--------|
| Staff | `access-medical` | Medical Mission (`/medmission`) |
| Maintenance | `access-maintenance` | Maintenance checklists |
| Inspector | `access-verify` | Verification |
| HR | `access-hr-only` | HR management (`/HR`) |
| Cashier | `access-cashier-only` | Point of Sale (`/pos`) |
| Developer | all | Full access |
| Disable | — | Blocked at login |

### Key Livewire Components

- `app/Livewire/DispenseMedicine.php` — Atomic DB transaction for stock decrement + dispensing record; validates stock before committing
- `app/Livewire/LeaveForm.php` — VL/SL credit calculation based on `employment_details.hiring_date`; supports AM/PM half-days
- `app/Livewire/HrLeaveManagement.php` — Two-stage approval: Department Head status → HR status
- `app/Livewire/PointofSale/POS.php` — Cart system with budget meal bundles, customer credit balances, multi-payment methods (Cash/GCash/Credit); uses DB transactions for stock rollback

### Authentication

- Laravel Fortify with **username-based login** (not email)
- Email verification required; two-factor auth supported
- Custom authenticator blocks users with `Disable` role
- Config: `config/fortify.php`

### Data Model Highlights

- `users` — `role` enum, `employee_number`, `username`
- `medicines` — `quantity`, `reorder_level`, `status` (active/inactive)
- `leaves` — `dept_head_status` + `hr_status` for two-stage approval; `attachment` for medical certs
- `sales` + `sales_items` — POS transactions; `customers` tracks credit `balance`/`charges`
- `employment_details` — `hiring_date` drives leave credit entitlement calculations

### Frontend

- Tailwind CSS + DaisyUI + FluxUI (`flux/` components)
- Livewire Volt used alongside standard Livewire classes
- DomPDF for PDF generation (`resources/views/pdf/`)
- Vite bundles assets; Flux requires `FLUX_USERNAME`/`FLUX_LICENSE_KEY` secrets for CI

### CI/CD

GitHub Actions run on push to `develop`/`main`: lint (`.github/workflows/lint.yml`) and tests on PHP 8.4 + 8.5 (`.github/workflows/tests.yml`).
