# Hot TV - Code Redemption & Agent System

This project is a Laravel-based application designed for managing code redemptions and an agent system. It utilizes the Keenthemes Metronic Tailwind CSS theme (Demo 6) for its frontend. 

## Project Overview

*   **Framework:** Laravel 12
*   **Frontend Theme:** Keenthemes Metronic (Tailwind CSS 4.0 - Demo 6), Alpine.JS v3 for js event, e.g ajax
*   **Database:** SQLite (for local development, consider PostgreSQL/MySQL for production) NOTE CHANGE TO MYSQL
    **Support i18n 3 languages:** English (en), Chinese(zh_CN), Bahasa Melayu(ms). In laravel 12 the path is root\lang not inside resources
*   **Core Features:**
    *   Agent Dashboard: Displays key metrics like HOTCOIN balance, code generation statistics, and downline agent performance.
    *   Activation Code Generation: Allows agents to generate different types of activation codes based on predefined presets, deducting costs from their HOTCOIN balance. **(Implemented using `assorts` and `auth_codes` tables, with profit distribution to upline agents.)**
    *   Agent Hierarchy: Supports upline and downline agent relationships.
    *   User Authentication: Laravel Breeze (Blade + Alpine.js stack) for secure login for agents.
    *   **Costing Management: Implemented to allow agents to view and update pricing for different license code types and agent levels. Uses `assort_levels`, `defined_retail`, and `huobis` tables.**
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
        *   Dashboard View: `resources/views/dashboard/index.blade.php`
*   **Assets:**
    *   Keenthemes core CSS and JS are imported into `resources/css/app.css` and `resources/js/app.js` respectively. These are then processed and bundled by Vite.
    *   Static assets from Keenthemes (e.g., images, fonts, icons) are typically placed in `public/assets/media/` or structured within `resources/metronic/` and referenced appropriately.
    *   The original Keenthemes demo files are located in the `demo/` directory in the project root for reference.

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
    *   `DashboardController.php`: Handles logic for the agent dashboard.
    *   `NewLicenseCodeController.php`: Handles activation code generation.
    *   `CostingController.php`: Manages costing configurations.
*   `app/Models/`: Contains Eloquent models.
    *   `User.php` (extended for agent features)
    *   `Admin/AdminUser.php`: The primary user model for the admin panel.
    *   `Assort.php`: Model for managing code types/packages.
    *   `ActivationCodePreset.php` (Note: This was part of initial setup, but `assorts` table is now used for code types)
    *   `ActivationCode.php` (Note: This was part of initial setup, but `auth_codes` table is now used for generated codes)
    *   `HotcoinTransaction.php`
    *   `AgentMonthlyProfit.php`
*   `database/migrations/`: Contains database migration files.
*   `resources/views/`: Contains Blade template files.
*   `routes/web.php`: Defines web routes.
*   `public/`: Publicly accessible files (CSS, JS, images).
    *   `public/assets/`: May contain static media (images, fonts). Core Keenthemes styles/scripts are bundled by Vite from `resources`.
    *   `resources/metronic/`: Contains Keenthemes source assets (CSS, JS, vendors) for import into `app.css` and `app.js`.
*   `demo/`: Original Keenthemes demo files.
*   `old_project_reference/`: Contains the Laravel 6.18.12 project files for reference during migration.

## Development Notes

*   Ensure PHP is correctly configured in your system's PATH environment variable.
*   The application uses custom Keenicon classes for icons (e.g., `ki-filled ki-wallet`). Refer to Keenthemes documentation for available icons.
*   The application uses Laravel Breeze for authentication and authorization.
*   All pages should extend the `resources/views/layouts/master.blade.php` layout for a consistent look and feel.

## GEMINI READ HERE IMPORTANT
* NOW THE WAYS TO PROCEED THIS MIGRATION PROJECT, FIRST COPY THE WHOLE CONTENT OF THE CONTROLLERS AND ONLY THEN TO CREATE NEEDED REPOSITORY OR MODELS FILE AND THEN MIGRATE THE SYNTAX FROM LARAVEL 6 TO LARAVEL 12 IF NEEDED. main goal is to migrate the business logic from the project completely, and if you want to do a different or create different thing always ask me first