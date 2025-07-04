# Laravel 6.18.12 to Laravel 12 Migration Guide

## Reference Structure Setup

For an organized migration process, place your old Laravel 6.18.12 project files in the `old_project_reference` directory. This will allow you to easily reference the old code while migrating to the new Laravel 12 structure.

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
| ⬜ Migrate database structure | Pending | Need to copy and update migrations |
| ⬜ Migrate models | Pending | Need to update models with new Laravel 12 features |
| ⬜ Migrate controllers | Pending | Some controllers already created, need to migrate logic |
| ⬜ Migrate views/blade templates | Pending | Need to adapt old templates to Keentheme structure |
| ⬜ Migrate middleware | Pending | Review and update as needed |
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
   - Update namespace from `App` to `App\Models` for all model classes
   - Update model relationships and property definitions
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

2. **Update request validation**:
   - Laravel 12 uses form request validation differently than Laravel 6
   - Move validation logic to dedicated Form Request classes in `app/Http/Requests/`

### 3. Middleware Migration

1. **Copy custom middleware**:
   - Copy any custom middleware from old project to `app/Http/Middleware/`
   - Update middleware to use Laravel 12 syntax and features
   - Register middleware in `app/Http/Kernel.php`

### 4. View Migration

1. **Adapt views to Keentheme**:
   - Identify core templates and layouts in old project
   - Create new layouts using Keentheme components
   - Adapt old views to use new Keentheme layout and components
   - Update asset paths and dependencies

2. **Update Blade syntax**:
   - Check for deprecated Blade directives and update to Laravel 12 syntax
   - Implement new Blade components system for reusable UI elements

### 5. Route Migration

1. **Complete route migration**:
   - Basic routes structure already migrated to `routes/web.php` and `routes/auth.php`
   - Add any missing routes from old project
   - Update route middleware and group definitions

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

1. **PHP 8.2 Requirement**:
   - Laravel 12 requires PHP 8.2 or higher (check for PHP 7.x specific code)

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
