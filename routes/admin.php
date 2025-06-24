<?php
// Reference: old_project_reference/routes/admin.php line 52
// Old: Route::get('/admin_users', 'AdminUserController@index')->name('adminUser.index');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;

//Route::middleware(['auth', 'admin.controller', 'admin.utility'])
Route::middleware(['auth.admin', 'admin.controller', 'admin.utility'])
    ->group(function () {
        // Admin users management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        
        Route::get('/dashboard', [
            \App\Http\Controllers\Admin\DashboardController::class, 'index'
        ])->name('dashboard');

        // Add more routes as needed, referencing the old routes
        // Route::get('/users/all', [AdminUserController::class, 'all'])->name('users.all');
        // Route::get('/users/logoff', [AdminUserController::class, 'logoff'])->name('users.logoff');
        // ...etc
    }); 

include __DIR__.'/admin-auth.php'; // Include the admin authentication routes