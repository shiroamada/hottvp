# Hot TV - Code Redemption & Agent System

This project is a Laravel-based application designed for managing code redemptions and an agent system. It utilizes the Keenthemes Metronic Tailwind CSS theme (Demo 6) for its frontend.

## Project Overview

*   **Framework:** Laravel 12
*   **Frontend Theme:** Keenthemes Metronic (Tailwind CSS 4.0 - Demo 6)
*   **Database:** SQLite (for local development, consider PostgreSQL/MySQL for production)
*   **Core Features:**
    *   Agent Dashboard: Displays key metrics like HOTCOIN balance, code generation statistics, and downline agent performance.
    *   Activation Code Generation: Allows agents to generate different types of activation codes based on predefined presets, deducting costs from their HOTCOIN balance.
    *   Agent Hierarchy: Supports upline and downline agent relationships.
    *   User Authentication: Laravel Breeze (Blade + Alpine.js stack) for secure login for agents.
    *   (Planned) License Code Management, Trial Code Management, Agent Listing, etc.

## Key Technologies & Structure

*   **Backend:**
    *   Laravel Eloquent ORM for database interaction.
    *   Laravel Controllers for handling business logic.
    *   Laravel Blade templating engine for views.
    *   Migrations for database schema management.
*   **Frontend:**
    *   Tailwind CSS 4.0 (integrated with Keenthemes Metronic).
    *   JavaScript for interactive elements (as provided by Keenthemes).
    *   Blade views located in `resources/views/`.
        *   Main Layout: `resources/views/layouts/master.blade.php`
        *   Sidebar Partial: `resources/views/layouts/partials/_sidebar.blade.php`
        *   Dashboard View: [resources/views/dashboard/index.blade.php](cci:7://file:///c:/Users/Administrator/Documents/GitHub/hottvp/resources/views/dashboard/index.blade.php:0:0-0:0)
*   **Assets:**
    *   Keenthemes core CSS and JS are imported into `resources/css/app.css` and `resources/js/app.js` respectively. These are then processed and bundled by Vite.
    *   Static assets from Keenthemes (e.g., images, fonts, icons) are typically placed in `public/assets/media/` or structured within `resources/metronic/` and referenced appropriately.
    *   The original Keenthemes demo files are located in the [demo/](cci:7://file:///C:/Users/Administrator/Documents/GitHub/hottvp/demo:0:0-0:0) directory in the project root for reference.

## Setup & Installation (General Steps)

1.  Clone the repository.
2.  Install PHP dependencies: `composer install`
3.  Install NPM dependencies (if any, for frontend asset building): `npm install && npm run dev`
4.  Copy `.env.example` to `.env` and configure environment variables (database, app URL, etc.).
5.  Generate application key: `php artisan key:generate`
6.  Run database migrations: `php artisan migrate`
7.  (Optional) Seed database: `php artisan db:seed`
8.  Serve the application: `php artisan serve`

## Important Directories

*   `app/Http/Controllers/`: Contains application controllers.
    *   [DashboardController.php](cci:7://file:///c:/Users/Administrator/Documents/GitHub/hottvp/app/Http/Controllers/DashboardController.php:0:0-0:0): Handles logic for the agent dashboard.
*   `app/Models/`: Contains Eloquent models.
    *   `User.php` (extended for agent features)
    *   `ActivationCodePreset.php`
    *   `ActivationCode.php`
    *   `HotcoinTransaction.php`
    *   `AgentMonthlyProfit.php`
*   `database/migrations/`: Contains database migration files.
*   `resources/views/`: Contains Blade template files.
*   [routes/web.php](cci:7://file:///c:/Users/Administrator/Documents/GitHub/hottvp/routes/web.php:0:0-0:0): Defines web routes.
*   `public/`: Publicly accessible files (CSS, JS, images).
    *   `public/assets/`: May contain static media (images, fonts). Core Keenthemes styles/scripts are bundled by Vite from `resources`.
    *   `resources/metronic/`: Contains Keenthemes source assets (CSS, JS, vendors) for import into `app.css` and `app.js`.
*   [demo/](cci:7://file:///C:/Users/Administrator/Documents/GitHub/hottvp/demo:0:0-0:0): Original Keenthemes demo files.

## Development Notes

*   Ensure PHP is correctly configured in your system's PATH environment variable.
*   The application uses custom Keenicon classes for icons (e.g., `ki-filled ki-wallet`). Refer to Keenthemes documentation for available icons.
*   The application uses Laravel Breeze for authentication and authorization.
*   All pages should extend the `resources/views/layouts/master.blade.php` layout for a consistent look and feel.
