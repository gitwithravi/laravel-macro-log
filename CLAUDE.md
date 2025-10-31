# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application using the Jetstream starter kit with the Inertia.js stack and Vue 3 frontend. The application includes user authentication, profile management, and API token management powered by Laravel Sanctum.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vue 3 + Inertia.js
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **Authentication**: Laravel Jetstream with Sanctum
- **Testing**: PHPUnit
- **Database**: SQLite (development), configurable for production

## Development Commands

### Initial Setup
```bash
composer setup
```
This runs the full setup: installs dependencies, creates .env, generates app key, runs migrations, and builds frontend assets.

### Start Development Server
```bash
composer dev
```
This starts all development services concurrently:
- PHP development server (port 8000)
- Queue worker
- Log viewer (Laravel Pail)
- Vite dev server (for hot module replacement)

Alternatively, run services individually:
```bash
php artisan serve          # Start backend server
npm run dev               # Start Vite dev server
php artisan queue:listen  # Start queue worker
php artisan pail          # View logs
```

### Testing
```bash
composer test              # Run all tests
php artisan test           # Run all tests (alternative)
php artisan test --filter TestName  # Run specific test
```

Tests use SQLite in-memory database (configured in phpunit.xml).

### Build for Production
```bash
npm run build             # Build frontend assets
```

### Code Quality
```bash
vendor/bin/pint           # Run Laravel Pint (code formatter)
```

## Architecture Overview

### Frontend Architecture (Inertia.js + Vue 3)

The frontend uses Inertia.js as a bridge between Laravel and Vue, enabling SPA-like behavior without a separate API layer.

**Key Components:**
- `resources/js/app.js` - Application entry point, configures Inertia and Vue
- `resources/js/Pages/` - Page components (each corresponds to a route)
- `resources/js/Components/` - Reusable Vue components
- `resources/js/Layouts/` - Layout components

**Routing:**
Routes are defined in Laravel (`routes/web.php`) but rendered as Vue components using Inertia's `render()` method. The `ZiggyVue` plugin makes Laravel routes available in JavaScript.

**Page Resolution:**
Pages are automatically resolved from `resources/js/Pages/` using Vite's glob import pattern. File structure maps to Inertia page names (e.g., `Profile/Show.vue` → `Profile/Show`).

### Backend Architecture

**Authentication & User Management:**
- Jetstream provides authentication scaffolding using Laravel Fortify
- User actions are handled by classes in `app/Actions/Fortify/` and `app/Actions/Jetstream/`
- Authentication uses Sanctum with session-based auth for web (configured in `config/jetstream.php`)
- Available Jetstream features: Account deletion (profile photos, API tokens, and teams are disabled)

**Key Directories:**
- `app/Actions/` - Action classes for user-related operations (password reset, user creation, profile updates, etc.)
- `app/Http/Controllers/` - HTTP controllers (minimal, as Jetstream handles most auth flows)
- `app/Http/Middleware/` - Custom middleware
- `app/Models/` - Eloquent models
- `app/Providers/` - Service providers

**Routes:**
- `routes/web.php` - Web routes (Inertia pages)
- `routes/api.php` - API routes (if using API tokens)
- `routes/console.php` - Artisan commands

### Database

**Migrations:**
Located in `database/migrations/`. Jetstream migrations are included for users, password resets, sessions, and personal access tokens.

**Running Migrations:**
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Drop all tables and re-run migrations
php artisan migrate:fresh --seed # Re-run migrations with seeders
```

### Inertia.js Specifics

**Passing Data to Pages:**
Use `Inertia::render()` in controllers/routes:
```php
return Inertia::render('Dashboard', [
    'data' => $data
]);
```

**Forms and CSRF:**
Inertia automatically handles CSRF tokens. Use Inertia's form helpers in Vue for proper validation error handling.

**Shared Data:**
Global shared data can be configured in `app/Http/Middleware/HandleInertiaRequests.php` (if created).

## Configuration Notes

### Jetstream Configuration
The app uses Jetstream's Inertia stack (configured in `config/jetstream.php`):
- Stack: `inertia`
- Guard: `sanctum`
- Middleware: `web`
- Enabled features: Account deletion
- Disabled features: Profile photos, API tokens, teams, terms and privacy policy

### Frontend Build (Vite)
Vite is configured to:
- Use `resources/js/app.js` as entry point
- Support Vue 3 single-file components
- Enable hot module replacement during development
- Transform asset URLs in Vue templates

## Testing Structure

Tests are organized in `tests/`:
- `tests/Feature/` - Feature tests (HTTP tests, integration tests)
- `tests/Unit/` - Unit tests
- `tests/TestCase.php` - Base test case class

Jetstream includes authentication and API token tests out of the box.

## Important Notes

### Migrations
Always run migrations before testing or using the application. The initial setup includes migrations for Jetstream's user management features.

### Environment Configuration
The `.env` file must exist and have `APP_KEY` set. The setup script handles this automatically.

### Queue System
If using queued jobs, ensure the queue worker is running (`php artisan queue:listen` or use `composer dev`).
