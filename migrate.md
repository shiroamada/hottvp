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

## Migration Progress Tracker

| Step | Status | Notes |
|------|--------|-------|
| ✅ Setup new Laravel 12 project | Completed | Fresh installation with Keentheme already integrated |
| ✅ Install required packages | Completed | Core packages installed via composer.json |
| ✅ Setup authentication system | Completed | Using Laravel Breeze |
| ✅ Setup basic routing structure | Completed | Basic auth and web routes set up |
| ✅ Setup middleware structure | Completed | Middleware registered in bootstrap/app.php |
| ✅ Setup admin views structure | In Progress | Essential views migrated, more to come |
| ⬜ Migrate database structure | Pending | Need to copy and update migrations |
| ⬜ Migrate models | Pending | Need to update models with new Laravel 12 features |
| ⬜ Migrate controllers | In Progress | Some controllers already created, need to migrate logic |
| ⬜ Migrate validation rules | Pending | Check for deprecated validation methods |
| ⬜ Test authentication system | Pending | Ensure proper functionality |
| ⬜ Test business logic | Pending | Verify core functionality works as expected |

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
   - Integrate old custom styles with Keentheme

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
php artisan test
```

## Resources

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Keentheme Documentation](https://preview.keenthemes.com/html/metronic/docs/)
