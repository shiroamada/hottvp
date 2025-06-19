<?php
// Reference: old_project_reference/routes/admin.php line 52
// Old: Route::get('/admin_users', 'AdminUserController@index')->name('adminUser.index');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;

Route::middleware(['auth', 'admin.controller', 'admin.utility'])
    ->name('admin.')
    ->group(function () {
        // Admin users management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        
        // Add more routes as needed, referencing the old routes
        // Route::get('/users/all', [AdminUserController::class, 'all'])->name('users.all');
        // Route::get('/users/logoff', [AdminUserController::class, 'logoff'])->name('users.logoff');
        // ...etc
    }); 