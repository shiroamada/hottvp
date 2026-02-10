# Laravel 6.18.12 to Laravel 12 Migration Guide
Use context7 mcp for Laravel12 documentation.

## Reference Structure Setup

For an organized migration process, place your old Laravel 6.18.12 project files in the `/old_project_reference` directory. This will allow you to easily reference the old code while migrating to the new Laravel 12 structure.

## Do not change any logic, please migrate follow exactly the business logic, can start with old function even with error no worries, can fix error, it is more crucial to remain the same business logic. This is extremely important note!

The old project using parent controller , but the new migrate move all the parent controller to middleware.

Directory structure for reference files:
```
old_project_reference/
├── app/
│   ├── Http/
│   │   └── Controllers/     # Place old controllers here
│   └── Models/              # Place old models here (or just 'app/' for Laravel 6)
├── database/
│   └── migrations/          # Place old migrations here
├── routes/                  # Place old route files here
└── resources/
    └── views/               # Place old view files here
```

Copy your old project files into these directories for easy reference during the migration process.

## Current Migration Status

As of the latest update, the following components have been migrated:

1. **Admin User Management**:
   - Essential views migrated: add.blade.php, ajax_info.blade.php, recharge.blade.php, check.blade.php
   - Routes configured in routes/admin.php with proper middleware
   - AdminUtilityMiddleware created and registered in bootstrap/app.php

2. **Route Naming Convention**:
   - Updated all route references to use 'admin.' prefix (e.g., route('admin.users.index'))
   - This is automatically applied to all routes in admin.php thanks to the configuration in bootstrap/app.php

3. **Middleware Structure**:
   - Middleware now registered in bootstrap/app.php instead of Kernel.php
   - Added proper PHP 8.3 type declarations to middleware methods

4. **Next Steps**:
   - Create AdminUserController with proper method implementations
   - Migrate the AdminUser model from old project
   - Add necessary database migrations
   - Complete remaining views for admin user management

## Important note for Laravel 12, no more Kernel.php
 ** Laravel 12 use bootstrap/app.php for replacement of Kernel.php`
 Middleware all save into bootstrap/app.php

## Route Naming Conventions in Laravel 12

In Laravel 12, route naming follows a more structured approach, especially for admin routes:

1. **Route Prefixing**: In `bootstrap/app.php`, admin routes are defined with a prefix:
   ```php
   ->withRouting(
       // ...
       then: function () {
           Route::middleware('web')
               ->prefix('admin')
               ->name('admin.')  // This adds 'admin.' prefix to all route names
               ->namespace('App\Http\Controllers\Admin')
               ->group(base_path('routes/admin.php'));
       }
   )
   ```

2. **Route Names**: Because of this prefix, all routes defined in `routes/admin.php` will automatically have `admin.` prepended to their names:
   - A route defined as `->name('users.index')` will be accessible as `route('admin.users.index')`
   - Make sure to use this naming convention in your views and controllers

3. **Updating Views**: When migrating views, update all route references:
   ```php
   // Old (Laravel 6)
   {{ route('users.index') }}
   
   // New (Laravel 12)
   {{ route('admin.users.index') }}
   ```

## Middleware Changes in Laravel 12

Laravel 12 has completely changed how middleware is registered:

1. **No Kernel.php**: Laravel 12 no longer uses `app/Http/Kernel.php` for middleware registration

2. **Using bootstrap/app.php**: All middleware is now registered in `bootstrap/app.php`:
   ```php
   ->withMiddleware(function (Middleware $middleware) {
       // Add middleware to groups
       $middleware->appendToGroup('web', [
           \App\Http\Middleware\SetLocale::class,
           \App\Http\Middleware\AdminControllerMiddleware::class,
       ]);
       
       // Register middleware aliases
       $middleware->alias([
           'auth.admin' => \App\Http\Middleware\AdminAuthenticate::class,
           'admin.controller' => \App\Http\Middleware\AdminControllerMiddleware::class,
           'admin.utility' => \App\Http\Middleware\AdminUtilityMiddleware::class,
       ]);
   })
   ```

3. **Middleware Naming**: Use the suffix `Middleware` for all middleware classes:
   - Old: `AdminUtility.php`
   - New: `AdminUtilityMiddleware.php`

4. **Type Declarations**: Add proper type declarations to middleware methods:
   ```php
   // Old (Laravel 6)
   public function handle($request, Closure $next)
   
   // New (Laravel 12)
   public function handle(Request $request, Closure $next): Response
   ```

## How to Use the Reference Structure

1. **Copy files from your old Laravel 6.18.12 project**:
   ```powershell
   # Example commands to copy files from your old project
   # Adjust paths to match your actual old project location
   Copy-Item -Path "C:\path\to\old_project\app\Http\Controllers\*" -Destination "old_project_reference\app\Http\Controllers\" -Recurse
   Copy-Item -Path "C:\path\to\old_project\app\*" -Destination "old_project_reference\app\Models\" -Recurse -Include "*.php" -Exclude "*Controller.php"
   Copy-Item -Path "C:\path\to\old_project\database\migrations\*" -Destination "old_project_reference\database\migrations\" -Recurse
   Copy-Item -Path "C:\path\to\old_project\routes\*" -Destination "old_project_reference\routes\" -Recurse
   Copy-Item -Path "C:\path\to\old_project\resources\views\*" -Destination "old_project_reference\resources\views\" -Recurse
   ```

2. **Reference while migrating**:
   When migrating a specific component, first check the corresponding file in the reference directory to understand its structure and functionality, then adapt it to Laravel 12 standards in your new project structure.

## Code Adaptation Guidelines

When moving code from Laravel 6.18.12 to Laravel 12, be aware of these common adaptation needs:

### Controllers

1. **Namespace Changes**:
   ```php
   // Laravel 6
   namespace App\Http\Controllers;
   
   // Laravel 12
   namespace App\Http\Controllers;
   // No change, but imports may need updating
   ```

2. **Request Validation**:
   ```php
   // Laravel 6
   public function store(Request $request)
   {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
       ]);
       // ...
   }
   
   // Laravel 12 (preferred approach)
   public function store(StoreUserRequest $request)
   {
       // Validation handled in the form request class
       $validated = $request->validated();
       // ...
   }
   ```

### Models

1. **Namespace Changes**:
   ```php
   // Laravel 6
   namespace App;
   
   // Laravel 12
   namespace App\Models;
   ```

2. **Mass Assignment Protection**:
   ```php
   // Laravel 6
   protected $fillable = ['name', 'email'];
   // or
   protected $guarded = ['id'];
   
   // Laravel 12
   // Same approach, but be aware of new model property types
   protected $fillable = ['name', 'email'];
   // or
   protected $guarded = ['id'];
   ```

### Blade Templates

1. **Component Syntax**:
   ```php
   // Laravel 6
   @include('components.alert', ['type' => 'danger'])
   
   // Laravel 12
   <x-alert type="danger" />
   ```

2. **Authentication Directives**:
   ```php
   // Laravel 6
   @auth
       // The user is authenticated
   @endauth
   
   // Laravel 12
   // Same syntax, but may interact with new auth system
   @auth
       // The user is authenticated
   @endauth
   ```

### Route Definitions

1. **Route Model Binding**:
   ```php
   // Laravel 6
   Route::get('/users/{user}', 'UserController@show');
   
   // Laravel 12
   Route::get('/users/{user}', [UserController::class, 'show']);
   ```

2. **Controller References**:
   ```php
   // Laravel 6
   Route::get('/users', 'UserController@index');
   
   // Laravel 12
   use App\Http\Controllers\UserController;
   Route::get('/users', [UserController::class, 'index']);
   ```

### Some changes of the model name or filename
Admin/Model/Equipment.php ->  AssortLevel.php
app/functions.php -> app/helpers/helper.php

## Migration Progress Tracker

| Step | Status | Notes |
|------|--------|-------|
| ✅ Setup new Laravel 12 project | Completed | Fresh installation with Keentheme already integrated |
| ✅ Install required packages | Completed | Core packages installed via composer.json |
| ✅ Setup authentication system | Completed | Using Laravel Breeze |
| ✅ Setup basic routing structure | Completed | Basic auth and web routes set up |
| ✅ Setup middleware structure | Completed | Middleware registered in bootstrap/app.php |
| ✅ Setup admin views structure | In Progress | Essential views migrated, more to come |
| ✅ Migrate database structure | Pending | Need to copy and update migrations |
| ✅ Migrate models | Pending | Need to update models with new Laravel 12 features |
| ✅ Migrate controllers | In Progress | Some controllers already created, need to migrate logic |
| ✅ Migrate validation rules | Pending | Check for deprecated validation methods |
| ✅ Test authentication system | Pending | Ensure proper functionality |
| ✅ Test business logic | Pending | Verify core functionality works as expected |

## Detailed Migration Steps

### 1. Database Migration

1. **Copy migration files**:
   - Copy all migration files from old project to `database/migrations/` directory
   - Update migration files to use new Laravel 12 schema syntax if needed
   - Remove any timestamps in filenames and ensure proper ordering

2. **Update Models**:
   - Copy models from old project to `app/Models/` directory (note: Laravel 12 uses Models subdirectory)
   - Update namespace from `App\Model` to `App\Models` for all model classes
   - Update model relationships and property definitions
   - Add PHP 8.3 type declarations to methods
   - Check for and update any deprecated methods or properties

3. **Run migrations**:
   ```bash
   php artisan migrate
   ```

### 2. Controller Migration

1. **Copy controllers**:
   - Copy controller files from old project to `app/Http/Controllers/`
   - Update namespaces and import statements
   - Several controllers already migrated: ProfileController, AgentController, TrialCodeController, LicenseCodeController, CostingController
   - Update controller methods to use new Laravel 12 features and syntax
   - Add PHP 8.3 type declarations to methods

2. **Update request validation**:
   - Laravel 12 uses form request validation differently than Laravel 6
   - Move validation logic to dedicated Form Request classes in `app/Http/Requests/`

### 3. Middleware Migration

1. **Copy custom middleware**:
   - Copy any custom middleware from old project to `app/Http/Middleware/`
   - Update middleware to use Laravel 12 syntax and features
   - Add PHP 8.3 type declarations to methods
   - Register middleware in `bootstrap/app.php` (not in `app/Http/Kernel.php`)

### 4. View Migration

1. **Adapt views to Keentheme**:
   - Identify core templates and layouts in old project
   - Create new layouts using Keentheme components
   - Adapt old views to use new Keentheme layout and components
   - Update asset paths and dependencies
   - Update route names to use the 'admin.' prefix where appropriate

2. **Update Blade syntax**:
   - Check for deprecated Blade directives and update to Laravel 12 syntax
   - Implement new Blade components system for reusable UI elements

### 5. Route Migration

1. **Complete route migration**:
   - Basic routes structure already migrated to `routes/web.php` and `routes/auth.php`
   - Add any missing routes from old project
   - Update route middleware and group definitions
   - Remember that routes in `routes/admin.php` are automatically prefixed with 'admin.' in the name

### 6. Authentication System

1. **Laravel Breeze already installed**:
   - Review authentication logic from old project
   - Adapt custom authentication functionality to work with Breeze
   - Update user model and authentication controllers if needed

### 7. Service Providers

1. **Migrate service providers**:
   - Copy custom service providers from old project to `app/Providers/`
   - Update service provider methods and bindings
   - Register providers in `config/app.php`

### 8. API Migration (if applicable)

1. **Update API controllers and resources**:
   - Copy API controllers to `app/Http/Controllers/Api/`
   - Update resource classes to use Laravel 12 resource collection features
   - Update API authentication if needed

### 9. Configuration Files

1. **Update configuration**:
   - Compare and merge configuration files from old project
   - Pay special attention to `config/app.php`, `config/auth.php`, and any custom config files

### 10. Testing

1. **Migrate tests**:
   - Copy and update test files to use Laravel 12 testing features
   - Run tests to ensure functionality works as expected:
   ```bash
   php artisan test
   ```

### 11. Assets and Frontend

1. **Update frontend assets**:
   - Laravel 12 uses Vite instead of Mix for asset compilation
   - Update JS and CSS imports to work with Vite
   - Integrate old custom styles with Keenthemes

## Breaking Changes to Watch For

1. **PHP 8.3 Requirement**:
   - Laravel 12 requires PHP 8.3 or higher (check for PHP 7.x specific code)
   - Add type declarations to methods and properties
   - Update to use PHP 8.3 features where appropriate

2. **Route Binding**:
   - Route model binding behavior has changed in Laravel 12

3. **Validation Rules**:
   - Some validation rules have been updated or deprecated

4. **Authentication**:
   - Authentication system has been updated with new features and methods

5. **Eloquent Changes**:
   - Several Eloquent methods have been updated or deprecated

6. **Queue System**:
   - Queue system has been updated with new features

7. **Blade Changes**:
   - Blade component system has been completely revamped

8. **Middleware Registration**:
   - Middleware is now registered in `bootstrap/app.php` instead of `app/Http/Kernel.php`

9. **Model Namespace**:
   - Models are now in `App\Models` namespace (with 's') instead of `App\Model` (without 's')

10. **Route Naming**:
    - Admin routes are automatically prefixed with 'admin.' in the route name

## Final Checklist

- [ ] All migrations run successfully
- [ ] All models updated and working
- [ ] All controllers migrated and functional
- [ ] Views adapted to Keentheme and working
- [ ] Authentication system tested and working
- [ ] All tests passing
- [ ] No deprecated method calls or features
- [ ] Application runs without errors or warnings

## Module Feature Test Progress

This section tracks the progress of creating feature tests for each admin module.

Note 
Standard the use of uses(RefreshDatabase::class); The conflict arises if any file uses a different combination of traits with Tests\TestCase. For instance, uses(RefreshDatabase::class) alone is incompatible with uses(TestCase::class, RefreshDatabase::class).
| Module | Status | Notes |
|---|---|---|
| Dashboard | ✅ Done | `DashboardTest.php` |
| License & Trial Codes | ✅ Done | `NewLicenseCodeControllerTrialTest.php`,`NewLicenseCodeControllerTest.php`| 
| Pre-Generated Codes | ✅ Done | `PreGeneratedCodeTest.php` |
| GetApiByBatch Helper | ✅ Done | `GetApiByBatchTest.php` |
| Assorts | ⬜ skipped | | Incomplete module in original project, no need to test, only with controller but no view file or used in current state
| Levels | ⬜ skipped | | Incomplete module in original project, no need to test, only with controller but no view file or used in current state
| Channels | ⬜ Pending | | Incomplete module in original project, no need to test, only with controller but no view file or used in current state
| Categories | ⬜ Pending | |Incomplete module in original project, no need to test, only with controller but no view file or used in current state
| Configs | ⬜ Pending | |Incomplete module in original project, no need to test, only with controller but no view file or used in current state
| Cancels | ⬜ Pending | | Incomplete module in original project, no need to test, only with controller but no view file or used in current state
| Equipments (costing)| ✅ Done | | 
| Huobis (Hotcoin) | ⬜ Pending | |
| Admin User Management | ⬜ Pending | (Very large module) |

## Useful Commands During Migration

```bash
# Generate new model with migration
php artisan make:model ModelName -m

# Generate new controller
php artisan make:controller ControllerName

# Clear application cache
php artisan cache:clear

# Clear route cache
php artisan route:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Run tests
php artisan artisan test
```

## Resources

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Keenthemes Documentation](https://preview.keenthemes.com/html/metronic/docs/)

# Migration & Development Log

This document tracks the migration process from the old Laravel project and logs key development decisions.

## July 25, 2025 - HOTCOIN Generation Fix & License List

### Issues Addressed
1.  **Incorrect Cost Calculation:** The "need HOTCOIN" field on the license generation page was not correctly calculating the total cost (`cost * quantity`).
2.  **Incorrect Dropdown Prices:** The "type" dropdown for license generation was showing incorrect prices (defaulting to 0) for some license types.
3.  **Client-Side Scripting:** The JavaScript responsible for the cost calculation was not firing correctly due to theme script interference and caching issues.
4.  **Input Validation:** The quantity input allowed zero and negative values.

### Solution Implemented
1.  **Controller Logic (`NewLicenseCodeController@create`):** Modified the controller to correctly fetch user-specific prices for all `assorts`, ensuring the data passed to the view was accurate.
2.  **External JavaScript (`license-generator.js`):**
    *   Moved the calculation logic from an inline script to a dedicated file (`resources/js/license-generator.js`) to ensure proper handling by Vite.
    *   Updated `vite.config.js` to include this new script in the build process.
    *   Corrected the Blade template (`generate.blade.php`) to use `@push('scripts')` instead of `@section('scripts')`, allowing the script to be loaded correctly within the master layout.
    *   Added validation to prevent quantities less than 1.
3.  **License List Page (`/license/list`):**
    *   Created a new route, controller method (`NewLicenseCodeCodeController@index`), and `AuthCode` model.
    *   Developed a new view (`license/list.blade.php`) that displays a paginated list of generated codes, styled according to the project's Keenthemes design.
    *   Added the necessary language strings to `lang/en/messages.php` for the new page.

### Key Questions & Notes for Future Sessions:
*   **Default Pricing:** What is the correct fallback price if an agent does not have a specific price for an `assort` in the `assort_levels` table?
*   **`ActivationCodePreset` vs. `assorts`:** The `README.md` mentions that `assorts` has replaced `ActivationCodePreset`. Confirm if any legacy code still uses the old model.
*   **`ActivationCode` vs. `auth_codes`:** Similarly, confirm if the migration from `ActivationCode` to `auth_codes` is complete.

---

## July 26, 2025 - License List Enhancements & Feature Parity

### Implemented Features
1.  **Search and Filtering:**
    *   Added a comprehensive search and filter form to the `license/list` page, allowing users to filter by code, status, type, and date range.
    *   The `NewLicenseCodeController@index` method was updated to process these filters and return the correct results.
2.  **CSV Export:**
    *   Implemented an `export` method and route (`/license/export`) to allow users to download the filtered list of license codes as a CSV file.
3.  **Date Range Picker:**
    *   Diagnosed and resolved issues with the JavaScript date range picker.
    *   Installed `bootstrap-daterangepicker` and `moment.js` via npm.
    *   Configured `vite.config.js`, `app.js`, `app.css`, and `bootstrap.js` to correctly bundle and initialize the library, making it globally available to avoid conflicts.
4.  **Expiry Date Calculation:**
    *   The `NewLicenseCodeController@store` method now calculates and saves the `expire_at` date for each new code based on the `duration` field in the `assorts` table.
    *   The `license/list` view was updated to display this expiry date.
5.  **Feature Parity with `AuthCodeController`:**
    *   **Update Remarks:** Implemented a modal dialog on the list page to allow users to update the remarks for each code. This involved debugging the Keenthemes JavaScript to manually control the modal's visibility and z-index.
    *   **Recursive Profit Distribution:** Replaced the simple, single-level profit distribution with a recursive function (`distributeProfit`) that correctly calculates and distributes profit to the entire upline hierarchy.
    *   **View/Export Last Batch:** Implemented the `detail` and `down` methods, along with their corresponding routes and views, allowing users to view and export the most recently generated batch of codes.
    *   **Filter Trial Codes:** Ensured that all queries in the `NewLicenseCodeController` for the license list page specifically exclude trial codes (where `is_try != 1`).

---

## July 26, 2025 - Trial Code Management Migration

### Implemented Features
1.  **Controller and Routes:**
    *   Created a dedicated `TrialCodeController` to handle all logic related to trial codes.
    *   Defined routes for `trial.list`, `trial.generate`, `trial.store`, and `trial.export`.
2.  **List View:**
    *   Created the `trial/list.blade.php` view, which displays a paginated and filterable list of trial codes (where `is_try == 2`).
3.  **Generation Logic:**
    *   Created the `trial/generate.blade.php` view.
    *   Implemented the `create` and `store` methods in the `TrialCodeController`.
    *   The `store` method now correctly checks the `try_num` on the `admin_users` table and decrements it upon successful generation, mirroring the old project's business logic.
4.  **Translations:**
    *   Added all necessary language strings for the new trial code pages to `lang/en/messages.php`.

### Notes for Gemini (Next Session)
*   **User Table:** All user-related data, including agent information and trial code limits (`try_num`), is located in the `admin_users` table. The `users` table is for the base Laravel auth and is not used for agents.
*   **Next Steps:** The next logical step is to complete the Trial Code Management section by implementing the **CSV export** functionality. The `export` route is defined, but the method in `TrialCodeController` needs to be created. It should function similarly to the license code export, but filtered for trial codes (`is_try == 2`).

---

## July 28, 2025 - NewLicenseCodeController Migration & UI Fixes (Continued)

**Overall Goal:** Continue the migration of `AuthCodeController` logic to `NewLicenseCodeController` and resolve related UI/functional issues.

**Key Files Modified:**

*   `app/Http/Controllers/NewLicenseCodeController.php`
*   `app/Helpers/helper.php`
*   `app/Http/Requests/Admin/AuthCodeRequest.php`
*   `app/Models/TryCode.php`
*   `app/Models/HotcoinTransaction.php`
*   `app/Exports/LicenseCodesExport.php` (new file)
*   `app/Exports/LastBatchExport.php` (new file)
*   `app/Exports/TrialCodesExport.php` (new file)
*   `resources/views/license/list.blade.php`
*   `resources/js/bootstrap.js`
*   `lang/en/messages.php`
*   `package.json`

**Detailed Jobs Done:**

1.  **Helper Functions:**
    *   Implemented `getApiByBatch()` helper in `app/Helpers/helper.php` to generate multiple codes using `createCode()`.

2.  **`NewLicenseCodeController` Updates:**
    *   **`store` Method:**
        *   Removed inline `Request::validate()` as validation is now handled by `AuthCodeRequest`.
        *   Updated logic to fetch `equipment` from `AssortLevel` (instead of `Equipment`) for non-`level_id == 8` users, aligning with `migrate.md`.
        *   Integrated `getApiByBatch()` for batch code generation.
        *   Ensured `profit` is calculated and included in the `auth_codes` insert array for both single and batch generation.
        *   Modified return responses to be consistent JSON arrays for errors and a `redirect()` to `license.list` on success.
    *   **`hold` Method:**
        *   Updated method signature to use `AuthCodeRequest $request` for validation.
    *   **`update` Method:**
        *   Changed `remark` validation length to `max:128` characters to match Laravel 6 behavior.
        *   Modified return response to be a consistent JSON array.
    *   **`export`, `down`, `tryExport` Methods:**
        *   Refactored to use `Maatwebsite/Excel` for exports.
        *   Created dedicated export classes: `App\Exports\LicenseCodesExport`, `App\Exports\LastBatchExport`, and `App\Exports\TrialCodesExport`.
        *   Removed `PHPExcel` references and `exit;` calls.
        *   Added `use` statements for the new export classes.
        *   Standardized date filter parameter name from `date_range` to `created_at` in these methods for consistency with `list`.
    *   **`index` Method:**
        *   Added form field repopulation logic (attaching `auth_code`, `status`, `assort_id`, `date_range` to `$codes`).
        *   Implemented the super-admin bypass (`user_id != 1`) for filtering codes.

3.  **Model Updates:**
    *   Created `app/Models/TryCode.php` and explicitly set `protected $table = 'try_codes';`.
    *   Modified `app/Models/HotcoinTransaction.php` to explicitly set `protected $table = 'huobis';`.

4.  **Frontend (`resources/views/license/list.blade.php`):**
    *   Updated "Export Excel" button to directly link to the `license.export` route.
    *   Added "View Last Batch" and "Export Last Batch" buttons with corresponding routes.
    *   Updated JavaScript for "Update Remarks" modal dismissal to use `bootstrap.Modal.getInstance().hide()` for more reliable interaction.
    *   Added `toastr` library: installed via npm (`package.json`) and exposed globally in `resources/js/bootstrap.js`.
    *   Added new translation keys (`messages.license_list.view_last_batch`, `messages.license_list.export_last_batch`) to `lang/en/messages.php`.

**Outstanding Issues / Next Steps for Next Session:**

1.  **"Update Remarks" Modal Dismissal (Still Problematic):**
    *   The modal for updating remarks in `resources/views/license/list.blade.php` is still not dismissing correctly after a successful AJAX update, despite attempts to use `bootstrap.Modal.getInstance().hide()`.
    *   **Action for next session:** Further investigation is needed into Keenthemes' specific JavaScript for modal handling. It's possible their implementation completely overrides standard Bootstrap behavior, or requires a different API call (e.g., a custom `KTModal` object or event). Debugging the Keenthemes JavaScript in the browser's developer tools will be crucial.

2.  **General Modernization (Future Task):**
    *   While the old logic is replicated, some aspects (like the general approach to
    some UI interactions) could be further modernized to align with current Laravel 12 best practices. This is a lower priority than functional fixes.

---

## February 5, 2026 - MetVBox Partner API Integration

**Overall Goal:** Replace the old supplier API with MetVBox Partner API for code generation while maintaining all existing business logic (pricing, commissions, profit distribution).

**Context:** The system originally generated codes via external supplier API. User replaced supplier and implemented pre-generated codes as interim solution. Now integrated MetVBox Partner API to automate code generation directly.

**Key Files Created/Modified:**

*   `config/services.php` - Added MetVBox configuration
*   `.env.example` - Added METVBOX_PARTNER_TOKEN and METVBOX_BASE_URL
*   `app/Services/MetVBoxService.php` (NEW) - MetVBox API wrapper service
*   `app/Helpers/helper.php` - Updated `getApiByBatch()` to use MetVBox API
*   `app/Console/Commands/TestMetVBoxIntegration.php` (NEW) - CLI test command

**Detailed Implementation:**

1.  **MetVBoxService Class:**
    *   Encapsulates all MetVBox API interactions using Guzzle HTTP client
    *   Methods implemented:
        *   `generateCode()` - Generate activation codes with configurable validity period
        *   `listCodes()` - List codes with status filters (active, used, revoked, expired)
        *   `checkCodeStatus()` - Check individual code status
        *   `revokeCode()` - Revoke a code
        *   `testConnection()` - Test API connectivity
    *   Comprehensive logging for debugging
    *   Handles MetVBox response format: `{ success, data: { codes: [...], points_deducted, balance_after } }`

2.  **Integration with Code Generation (`getApiByBatch()`):**
    *   Replaced old supplier API call with `MetVBoxService::generateCode()`
    *   Changed vendor identifier from `'wowtv'` to `'metvbox'`
    *   Updated pre-generated code queries to filter by `'metvbox'` vendor
    *   Maintains 3-retry logic on API failure
    *   Normalized response parsing to extract codes from nested `data.codes` structure
    *   Logs MetVBox metadata (points_deducted, balance_after) for auditing

3.  **Test Command (`metvbox:test`):**
    *   Usage: `php artisan metvbox:test --quantity=5 --days=1`
    *   Tests API connectivity
    *   Generates test codes
    *   Displays full API response and extracted codes

**Business Logic Preservation:**

*   ✅ No changes to pricing/cost calculations
*   ✅ No changes to user balance deduction
*   ✅ No changes to profit distribution (recursive upline logic)
*   ✅ No changes to commission calculations
*   ✅ Codes marked with vendor='metvbox' for tracking

**Test Results:**

Successfully generated 5 codes with 1-day validity via `php artisan metvbox:test --quantity=5 --days=1`:

```json
{
    "success": true,
    "data": [
        "0101989298062262",
        "1583084604829504",
        "7908536611809785",
        "2429433146080569",
        "3990567530555998"
    ],
    "metadata": {
        "points_deducted": 5,
        "balance_after": 872
    }
}
```

All codes successfully extracted and formatted.

**Outstanding Tasks / Next Steps:**

1.  **Commission Verification (CRITICAL):**
    *   Need to verify that commissions are charged correctly when codes are generated via MetVBox API
    *   Ensure that the balance deduction and upline profit distribution work as expected
    *   **Status:** User will check if the commission is charged correctly
    *   **Action for next session:** Review transaction logs and verify profit distribution to all upline agents

2.  **Optional Enhancements (Future):**
    *   Create admin page to view/list/revoke codes via MetVBox API
    *   Create sync command to periodically list codes from MetVBox for auditing
    *   Add MetVBox balance monitoring dashboard

**Configuration Required:**

Add to `.env` file:
```
METVBOX_PARTNER_TOKEN=a97e84972b0b1f17fcaab1484f6ef0e1
METVBOX_BASE_URL=https://ta.metvbox.com
```

**Notes:**

*   Pre-generated codes feature remains available as fallback if MetVBox API fails
*   All existing code generation workflows automatically use MetVBox now
*   No UI changes required - system works transparently via API layer

---

## February 9, 2026 - MetVBox Code Status Auto-Refresh (Cron Job)

**Overall Goal:** Automatically refresh all code statuses from MetVBox API on a scheduled basis.

**Context:** Instead of manually refreshing code statuses, set up a cron job to refresh all MetVBox codes every hour.

**Key Files Created/Modified:**

*   `app/Console/Commands/RefreshMetVBoxCodeStatus.php` (NEW) - Artisan command for refreshing all code statuses
*   `bootstrap/app.php` - Added scheduler configuration

**Implementation:**

1.  **Created Artisan Command (`RefreshMetVBoxCodeStatus.php`):**
    *   Command signature: `metvbox:refresh-all-codes`
    *   Fetches all codes with vendor='metvbox'
    *   Processes codes in batches of 50 to avoid memory issues
    *   Maps MetVBox status to numeric status (0=inactive, 1=active, 2=revoked)
    *   Converts ISO 8601 dates to MySQL format
    *   Logs results and displays colored output
    *   Option: `--limit` (default: 100) to limit batch size if needed

2.  **Scheduler Configuration (`bootstrap/app.php`):**
    *   Added `withSchedule()` closure
    *   Runs command every hour with `->hourly()`
    *   Prevents overlapping executions with `->withoutOverlapping()`
    *   Logs success/failure via `->onSuccess()` and `->onFailure()`

**Features:**

✅ Automatically refreshes all MetVBox code statuses every hour
✅ Batch processing (50 codes per batch) for memory efficiency
✅ Handles failures gracefully with try-catch
✅ Detailed logging for debugging
✅ Colored console output (green for success, red for errors)
✅ Prevents overlapping executions
✅ Logs all results for audit trail

**Manual Usage:**

```bash
# Run command manually to test
php artisan metvbox:refresh-all-codes

# Run with custom batch size
php artisan metvbox:refresh-all-codes --limit=200

# Run in background (non-blocking)
php artisan metvbox:refresh-all-codes > /dev/null 2>&1 &
```

**Cron Setup Instructions:**

In production, you need to set up a cron job to run Laravel's scheduler every minute. Add this to your crontab:

```bash
# Edit crontab
crontab -e

# Add this line:
* * * * * cd /Users/bw/Sites/hottvp && php artisan schedule:run >> /dev/null 2>&1
```

This runs the scheduler every minute, which then triggers our `metvbox:refresh-all-codes` command every hour.

**Alternative: Using Supervisor:**

If you're using Supervisor to manage long-running processes:

```ini
[program:laravel-scheduler]
process_name=%(program_name)s_%(process_num)02d
command=php /Users/bw/Sites/hottvp/artisan schedule:run
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/laravel-scheduler.log
```

**Monitoring the Scheduler:**

Check the logs to see when the command runs:

```bash
# View recent logs
tail -f storage/logs/laravel.log | grep -i "metvbox"

# View logs for specific date
grep "metvbox" storage/logs/laravel-2026-02-09.log
```

**What Gets Updated:**

For each code in the database:
- Calls MetVBox API to get current status
- Updates `status` field (0=inactive, 1=active, 2=revoked)
- Updates `expire_at` field with real expiry date from MetVBox
- Logs each update in application logs

**Output Example:**

```
🔄 Starting MetVBox code status refresh...
Processing code: 1654909507579761...
✓ Updated: 1654909507579761 - Status: active
Processing code: 3317136996222050...
✓ Updated: 3317136996222050 - Status: inactive
...
✅ Refresh completed!
Total Codes: 245
Updated: 243
Failed: 2
```

**Status Mapping:**

| Numeric | MetVBox API | Display |
|---|---|---|
| 0 | inactive | Inactive (Yellow badge) |
| 1 | active | Active (Green badge) |
| 2 | revoked | Revoked (Red badge) |

**Outstanding Tasks / Next Steps:**

1. **Set up cron job on production server** - Add the crontab entry
2. **Monitor first runs** - Check logs to ensure scheduler is working
3. **Optional: Adjust frequency** - Change `.hourly()` to `.everyThirtyMinutes()` or `.daily()` as needed
4. **Optional: Add notifications** - Set up email/Slack alerts for failures

**Notes:**

*   Command is idempotent (safe to run multiple times)
*   Failed codes are logged but don't stop the process
*   Scheduler runs in UTC by default (configure in `config/app.php` if needed)
*   Each code is processed individually to handle failures gracefully
*   Batch size prevents database locks and memory issues with large datasets

---

## February 9, 2026 - Production Fix & Sync Command Implementation

**Overall Goal:** Fix production error where refresh command referenced non-existent vendor column, and implement code sync functionality from MetVBox API to database.

**Key Files Created/Modified:**

*   `app/Console/Commands/RefreshMetVBoxCodeStatus.php` - Updated with production fixes
*   `app/Console/Commands/SyncMetVBoxCodesToDatabase.php` (NEW) - New sync command
*   `migrate2.md` - This documentation

**Issues Addressed:**

1.  **Production Error - Missing vendor Column:**
    *   Error: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'vendor' in 'WHERE'`
    *   Root Cause: Previous session modified RefreshMetVBoxCodeStatus to filter by `where('vendor', 'metvbox')` but vendor column was never added to auth_codes table
    *   Discovery: Vendor info only exists in `remark` field as metadata, no separate column
    *   Solution: Removed vendor filter from refresh command - now refreshes all codes created after 2026

2.  **Server Load Optimization:**
    *   Added filter to only refresh codes with NULL expire_at (unused codes)
    *   First refresh populates real expiry date from MetVBox API
    *   Subsequent runs skip already-refreshed codes
    *   Result: Significantly reduces API calls while database query cost remains minimal

**Detailed Implementation:**

1.  **Updated RefreshMetVBoxCodeStatus Command:**
    *   Filter 1: `where('created_at', '>=', '2026-01-01 00:00:00')` - Only refresh 2026+ codes
    *   Filter 2: `whereNull('expire_at')` - Only refresh codes without expiry date set
    *   Purpose: Prevents redundant API calls, reduces server load
    *   Still processes all codes in batches of 50

2.  **Created SyncMetVBoxCodesToDatabase Command:**
    *   Signature: `metvbox:sync-codes {--limit=100} {--status=}`
    *   Fetches all codes from MetVBox API with pagination
    *   For each API code:
        *   Checks if it already exists in database by auth_code
        *   Inserts new codes with:
            *   `user_id = 1` (system admin - not generated in production)
            *   `profit = 0` (no commission deducted)
            *   Status mapped from MetVBox: 0=inactive, 1=active, 2=revoked
            *   Expiry date parsed from API and converted to MySQL format
            *   assort_id matched from duration (valid_days)
    *   Remark: "Not generated in {$currentEnv}, in other environment. Synced from MetVBox API. Status: X, Valid Days: Y"
    *   Features:
        *   Dynamic environment detection via `app()->environment()`
        *   Batch processing (50 codes per API page)
        *   Comprehensive error handling and logging
        *   Colored console output with progress indicators
        *   Summary statistics (total, inserted, duplicates, failed)

**Usage:**

```bash
# Sync all available codes from MetVBox API
php artisan metvbox:sync-codes

# Sync only active codes
php artisan metvbox:sync-codes --status=active

# Adjust batch size
php artisan metvbox:sync-codes --limit=200

# Refresh code statuses (production)
php artisan metvbox:refresh-all-codes
```

**Business Logic Preservation:**

*   ✅ Synced codes don't affect accounting (user_id=1, profit=0)
*   ✅ Transaction history stays clean (marked clearly in remark)
*   ✅ No balance deductions or commission calculations
*   ✅ Can query staging/testing codes: `AuthCode::where('user_id', 1)->where('remark', 'LIKE', '%staging or testing%')`
*   ✅ Perfect for syncing test codes from staging to production for testing purposes

**Key Questions Answered:**

1.  **Why remove vendor column filter?** - Column never existed in production database. Vendor info stored as metadata in remark field.
2.  **Why add expire_at filter?** - Reduces API load by skipping already-refreshed codes. First refresh gets real expiry from API.
3.  **Why assign to user_id=1?** - Prevents accounting impact while tracking non-production codes clearly.
4.  **Why dynamic environment detection?** - Remark automatically shows which environment code was synced from (staging, testing, development, etc.)

**Outstanding Tasks:**

1. **Set up scheduler for sync command (Optional)** - Can add to bootstrap/app.php if you want automatic daily syncs
2. **Monitor first refresh runs** - Verify the hour cron job is working correctly with new filters
3. **Test sync functionality** - Run `php artisan metvbox:sync-codes` manually first to verify output

---

## February 10, 2026 - Enhanced Sync Command & Scheduling Discussion

**Overall Goal:** Refine sync command to properly categorize 1-day trial codes and clarify scheduling approaches.

**Key Implementation Details:**

1.  **Assort ID Mapping (Dynamic):**
    *   MetVBox API provides `valid_days` field
    *   Sync command queries: `Assort::where('duration', $validDays)->first()`
    *   Matches MetVBox `valid_days` with database `duration` column
    *   Automatically finds correct `assort_id` regardless of ID mapping
    *   Example: MetVBox `valid_days=1` → Queries duration=1 → Gets assort_id=5

2.  **Trial Code Detection:**
    *   When syncing 1-day codes: `$isTry = ($validDays == 1) ? 2 : 1`
    *   1-day codes get `is_try = 2` (marked as trial/test code)
    *   All other durations get `is_try = 1` (regular code)
    *   Properly categorizes test codes in the system

**Scheduling Approaches Discussed:**

**Option 1: Direct Cron Jobs (Recommended for 2+ commands)**
```bash
# Add to production crontab
0 * * * * cd /Users/bw/Sites/hottvp && php artisan metvbox:refresh-all-codes >> /dev/null 2>&1
0 2 * * * cd /Users/bw/Sites/hottvp && php artisan metvbox:sync-codes >> /dev/null 2>&1
```
- Pros: Simple, direct, no overhead
- Cons: Multiple cron entries to manage

**Option 2: Laravel Scheduler (Recommended for many tasks)**
```php
// In bootstrap/app.php
->withSchedule(function ($schedule) {
    $schedule->command('metvbox:refresh-all-codes')->hourly()->withoutOverlapping();
    $schedule->command('metvbox:sync-codes')->daily()->withoutOverlapping();
})
```
Requires single cron: `* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1`

**Recommendation:** Use direct cron jobs since you only have 2 commands. Less complexity.

**Final Sync Command Behavior:**

```bash
php artisan metvbox:sync-codes
```

For each code from MetVBox API:
1. Check if exists in database by `auth_code`
2. If new:
   - Map `valid_days` → `assort_id` via duration lookup
   - Set `is_try = 2` if 1-day code, else `is_try = 1`
   - Set `user_id = 1` (system admin)
   - Mark in remark with environment name
   - Parse expiry date from ISO 8601 to MySQL format
3. If duplicate: Skip and count
4. Return summary stats

This properly categorizes test/staging codes while maintaining accounting integrity.

---

## February 10, 2026 - Remark Template Simplification (Approach C)

**Overall Goal:** Simplify user-generated code remarks while protecting technical details from non-admin users.

**Implementation:**

1.  **Simplified User-Generated Remarks:**
    *   Modified `NewLicenseCodeController::save()` method (two locations)
    *   Old format: `"Type: 1days, Vendor: metvbox, Source: API - {user_input}"`
    *   New format: `"{user_input}"` (or empty string if no input)
    *   Removed all technical metadata from user-generated codes
    *   Benefits: Clean remarks, user-friendly display

2.  **Synced Codes Remain Technical:**
    *   SyncMetVBoxCodesToDatabase command keeps full technical remark
    *   Format: `"Not generated in {$currentEnv}, in other environment. Synced from MetVBox API. Status: X, Valid Days: Y"`
    *   Only synced codes contain "Synced from MetVBox" marker

3.  **Permission-Based Display Control:**
    *   Added access control in `resources/views/license/list.blade.php`
    *   Logic: `str_contains($code->remark, 'Synced from MetVBox')`
    *   Display rules:
        *   **Admin (level_id == 0):** See full remark (user input or technical details)
        *   **Non-admin:** See user input remarks normally, but synced code remarks show as "Synced test code"
    *   Update button also restricted: Non-admins cannot edit synced code remarks

**Code Changes:**

**In NewLicenseCodeController::save():**
- Line 415: Changed from complex template to `$data['remark'] ? $data['remark'] : ''`
- Line 450: Same change for batch generation
- Result: User input stored directly, no technical prefix

**In license/list.blade.php:**
- Added PHP block (lines 115-123) to determine display remark based on permissions
- Updated remark display (lines 125-138) to show filtered version
- Added permission check (line 161) to hide edit button for non-admins on synced codes

**Behavior:**

| Scenario | User sees | Admin sees |
|----------|-----------|-----------|
| User-generated with remark | "Custom remark text" | "Custom remark text" |
| User-generated empty | "" | "" |
| Synced code | "Synced test code" | "Not generated in staging, in other environment. Synced from MetVBox API. Status: active, Valid Days: 1" |

**Benefits:**

✅ Simplified user-generated remarks (no technical clutter)
✅ Full technical audit trail for admins
✅ Non-admins see clean, professional remarks
✅ Synced codes clearly marked and protected
✅ No database migration needed
✅ Easy to maintain and understand

---

## February 10, 2026 - Artisan Command Integration for Refresh Buttons

**Overall Goal:** Replace page-specific refresh logic with artisan command execution for both license and trial code pages.

**Key Modifications:**

1.  **New Routes Added:**
    *   `POST /admin/license/refresh-all-artisan` → NewLicenseCodeController@refreshAllArtisan
    *   `POST /admin/trial/refresh-all-artisan` → TrialCodeController@refreshAllArtisan

2.  **New Controller Methods:**
    *   `NewLicenseCodeController::refreshAllArtisan()` - Executes `php artisan metvbox:refresh-all-codes`
    *   `TrialCodeController::refreshAllArtisan()` - Executes `php artisan metvbox:refresh-all-codes`
    *   Both use `Artisan::call('metvbox:refresh-all-codes')` to run the same command as the cron job
    *   Returns JSON response with success/failure status and message

3.  **Updated Views:**
    *   `/admin/license/list` - Replaced page-specific refresh with artisan command call
    *   `/admin/try/list` - Added refresh button and artisan command call

4.  **UI/UX Changes:**
    *   Refresh button now shows "Refreshing all..." with loading spinner
    *   Calls `/admin/license/refresh-all-artisan` or `/admin/trial/refresh-all-artisan`
    *   No longer passes code IDs (processes all codes like cron job)
    *   Shows toastr notification on success/failure
    *   Reloads page to show updated statuses

**Benefits:**

✅ Refresh button now runs the same logic as cron job
✅ Processes all codes (not just page codes)
✅ Consistent behavior across production and manual refresh
✅ Both pages (license and trial) have refresh button
✅ Simple JSON API for future integrations

**Before vs After:**

**Before:**
- License list: Refresh button → refreshes only codes on current page
- Trial list: No refresh button
- Logic: Loop through page code IDs and refresh individually

**After:**
- License list: Refresh button → executes `php artisan metvbox:refresh-all-codes`
- Trial list: Refresh button → executes `php artisan metvbox:refresh-all-codes`
- Logic: Same as hourly cron job (processes all 2026+ codes with expire_at = NULL)

---

## February 10, 2026 - CSRF Token (419) Error Handling

**Overall Goal:** Redirect 419 Token Mismatch errors to login page instead of showing error page.

**Problem:** Users get 419 errors when session expires (especially after logout), causing confusion.

**Solution:** Added exception handler in `bootstrap/app.php` to redirect 419 errors to login page.

**Implementation:**

In `bootstrap/app.php` `withExceptions()` closure:
```php
$exceptions->render(function (Throwable $e, $request) {
    if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $e->getStatusCode() === 419) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Token expired. Please refresh and try again.'], 419);
        }

        // Redirect to appropriate login based on admin or web user
        if ($request->is('admin/*')) {
            return redirect()->route('admin.login')->with('error', 'Your session has expired. Please login again.');
        } else {
            return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
        }
    }
});
```

**Behavior:**

- **Web request with 419 error**: Redirects to login page with message
- **API request with 419 error**: Returns JSON response
- **Admin routes**: Redirects to `admin.login`
- **Web routes**: Redirects to `login`
- **User experience**: Clear message instead of error page

**Benefits:**

✅ User sees friendly redirect instead of 419 error page
✅ Automatically redirects to correct login (admin or web)
✅ API requests still get JSON response
✅ Session expiry handled gracefully
✅ No confusing error messages for end users
