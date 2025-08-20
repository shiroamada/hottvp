<?php

// Reference: old_project_reference/routes/admin.php line 52
// Old: Route::get('/admin_users', 'AdminUserController@index')->name('adminUser.index');
// Note: In Laravel 12, routes defined here will automatically have 'admin.' prefix in their names
// So we need to override this behavior to maintain the original route names

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Start with the most essential routes for admin users
Route::middleware(['auth.admin', 'admin.controller', 'admin.utility'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Essential Admin User Management Routes (Core CRUD)
        // Using original route names without 'admin.' prefix to maintain exact business logic
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users/save', [AdminUserController::class, 'save'])->name('users.save');
        Route::get('/users/edit/{id}', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/update/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/delete/{id}', [AdminUserController::class, 'delete'])->name('users.delete');

        // Ajax calls for users management
        Route::get('/users/info', [AdminUserController::class, 'info'])->name('users.info');
        Route::get('/users/detail', [AdminUserController::class, 'detail'])->name('users.detail');

        // User details and balance management
        Route::get('/users/check/{id}', [AdminUserController::class, 'check'])->name('users.check');
        Route::get('/users/recharge/{id}', [AdminUserController::class, 'recharge'])->name('users.recharge');
        Route::post('/users/pay', [AdminUserController::class, 'pay'])->name('users.pay');
        Route::get('/users/look/{id}', [AdminUserController::class, 'look'])->name('users.look');
        Route::get('/users/lower/{id}', [AdminUserController::class, 'lower'])->name('users.lower');

        // Additional routes from old project
        Route::get('/users/level/{id}', [AdminUserController::class, 'level'])->name('users.level');
        Route::post('/users/level_update', [AdminUserController::class, 'levelUpdate'])->name('users.level_update');
        Route::get('/users/cost/{id}', [AdminUserController::class, 'cost'])->name('users.cost');
        Route::post('/users/cost_update', [AdminUserController::class, 'costUpdate'])->name('users.cost_update');
        foreach (new DirectoryIterator(base_path('routes/auto')) as $f) {
            if ($f->isDot()) {
                continue;
            }
            $name = $f->getPathname();
            if ($f->isFile() && Str::endsWith($name, '.php')) {
                require $name;
            }
        }
    });

    
include __DIR__.'/admin-auth.php'; // Include the admin authentication routes
