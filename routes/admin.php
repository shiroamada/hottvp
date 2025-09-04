<?php

// Reference: old_project_reference/routes/admin.php line 52
// Old: Route::get('/admin_users', 'AdminUserController@index')->name('adminUser.index');
// Note: In Laravel 12, routes defined here will automatically have 'admin.' prefix in their names
// So we need to override this behavior to maintain the original route names

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AssortController;
use App\Http\Controllers\Admin\CancelController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewLicenseCodeController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\HuobiController;
use App\Http\Controllers\Admin\LevelController;
use Illuminate\Support\Facades\Route;

// Start with the most essential routes for admin users
Route::middleware(['auth.admin', 'admin.controller', 'admin.utility'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        


        // Essential Admin User Management Routes (Core CRUD)
        // Using original route names without 'admin.' prefix to maintain exact business logic
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/visual/{id}', [AdminUserController::class, 'visual'])->name('users.visual');
        Route::get('/users/stepOne/{id}', [AdminUserController::class, 'stepOne'])->name('users.stepOne');
        Route::get('/users/stepTwo/{id}', [AdminUserController::class, 'stepTwo'])->name('users.stepTwo');
        Route::get('/users/examine/{id}', [AdminUserController::class, 'examine'])->name('users.examine');        
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users/save', [AdminUserController::class, 'save'])->name('users.save');
        Route::get('/users/edit/{id}', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/update/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::get('/users/delete/{id}', [AdminUserController::class, 'delete'])->name('users.delete');
        Route::get('/users/lower/{id}', [AdminUserController::class, 'lower'])->name('users.lower');
        Route::put('/users/remark/{id}', [AdminUserController::class, 'remark'])->name('users.remark');
        Route::put('/users/userUpdate', [AdminUserController::class, 'userUpdate'])->name('users.userUpdate');
        Route::get('/users/userInfo', [AdminUserController::class, 'userInfo'])->name('users.userInfo');
        Route::get('/users/userEdit', [AdminUserController::class, 'userEdit'])->name('users.userEdit');
        // Add new route for all users
        Route::get('/users/all', [AdminUserController::class, 'all'])->name('users.all');

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

        Route::get('/configs', 'ConfigController@index')->name('config.index');
        Route::get('/configs/list', 'ConfigController@list')->name('config.list');
        Route::get('/configs/create', 'ConfigController@create')->name('config.create');
        Route::post('/configs', 'ConfigController@save')->name('config.save');
        Route::get('/configs/{id}/edit', 'ConfigController@edit')->name('config.edit');
        Route::put('/configs/{id}', 'ConfigController@update')->name('config.update');
        Route::delete('/configs/{id}', 'ConfigController@delete')->name('config.delete');




        
        Route::get('/assorts', 'AssortController@index')->name('assort.index');
        Route::get('/assorts/create', 'AssortController@create')->name('assort.create');
        Route::post('/assorts', 'AssortController@save')->name('assort.save');
        Route::get('/assorts/{id}/edit', 'AssortController@edit')->name('assort.edit');
        Route::put('/assorts/{id}', 'AssortController@update')->name('assort.update');
        Route::get('/assorts/{id}/info', 'AssortController@info')->name('assort.info');
        Route::delete('/assorts/{id}', 'AssortController@delete')->name('assort.delete');

        Route::get('/cancels', 'CancelController@index')->name('cancel.index');
        Route::get('/cancels/{id}/check', 'CancelController@check')->name('cancel.check');
        Route::put('/cancels/{id}/{type}', 'CancelController@update')->name('cancel.update');
        Route::post('/cancels/cancel', 'CancelController@cancel')->name('cancel.cancel');
        Route::get('/cancels/{id}/look', 'CancelController@look')->name('cancel.look');

        Route::get('/categories', 'CategoryController@index')->name('category.index');
        Route::get('/categories/list', 'CategoryController@list')->name('category.list');
        Route::get('/categories/create', 'CategoryController@create')->name('category.create');
        Route::post('/categories', 'CategoryController@save')->name('category.save');
        Route::get('/categories/{id}/edit', 'CategoryController@edit')->name('category.edit');
        Route::put('/categories/{id}', 'CategoryController@update')->name('category.update');

        Route::get('/channels', 'ChannelController@index')->name('channel.index');
        Route::get('/channels/create', 'ChannelController@create')->name('channel.create');
        Route::post('/channels', 'ChannelController@save')->name('channel.save');
        Route::get('/channels/{id}/edit', 'ChannelController@edit')->name('channel.edit');
        Route::put('/channels/{id}', 'ChannelController@update')->name('channel.update');
        Route::get('/channels/{id}/info', 'ChannelController@info')->name('channel.info');

        Route::get('/codes', 'NewLicenseCodeController@index')->name('code.index');
        Route::get('/codes/create', 'NewLicenseCodeController@create')->name('code.create');
        Route::get('/codes/getApi', 'NewLicenseCodeController@getApi')->name('code.getApi');
        Route::post('/codes', 'NewLicenseCodeController@save')->name('code.save');
        Route::get('/codes/{id}/edit', 'NewLicenseCodeController@edit')->name('code.edit');
        Route::put('/codes/remark', 'NewLicenseCodeController@remark')->name('code.remark');
        Route::put('/codes/{id}', 'NewLicenseCodeController@update')->name('code.update');
        Route::get('/codes/{id}/info', 'NewLicenseCodeController@info')->name('code.info');
        Route::delete('/codes/{id}', 'NewLicenseCodeController@delete')->name('code.delete');
        Route::get('/codes/export', 'NewLicenseCodeController@export')->name('code.export');

        Route::get('/equipments', 'EquipmentController@index')->name('equipment.index');
        Route::get('/equipments/create', 'EquipmentController@create')->name('equipment.create');
        Route::post('/equipments', 'EquipmentController@save')->name('equipment.save');
        Route::post('/equipments/edit', 'EquipmentController@edit')->name('equipment.edit');

        Route::get('/huobis', 'HuobiController@index')->name('huobi.index');
        Route::get('/huobis/create', 'HuobiController@create')->name('huobi.create');
        Route::post('/huobis', 'HuobiController@save')->name('huobi.save');
        Route::get('/huobis/{id}/edit', 'HuobiController@edit')->name('huobi.edit');
        Route::put('/huobis/{id}', 'HuobiController@update')->name('huobi.update');
        Route::get('/huobis/{id}/info', 'HuobiController@info')->name('huobi.info');
        Route::delete('/huobis/{id}', 'HuobiController@delete')->name('huobi.delete');
        Route::get('/huobis/export', 'HuobiController@export')->name('huobi.export');

        Route::get('/levels', 'LevelController@index')->name('level.index');
        Route::get('/levels/create', 'LevelController@create')->name('level.create');
        Route::post('/levels', 'LevelController@save')->name('level.save');
        Route::get('/levels/{id}/edit', 'LevelController@edit')->name('level.edit');
        Route::put('/levels/{id}', 'LevelController@update')->name('level.update');
        Route::get('/levels/{id}/info', 'LevelController@info')->name('level.info');
        Route::delete('/levels/{id}', 'LevelController@delete')->name('level.delete');

        Route::get('/try/list', [NewLicenseCodeController::class, 'list'])->name('try.list');
        Route::get('/try/add', [NewLicenseCodeController::class, 'add'])->name('try.add');
        Route::post('/try/hold', [NewLicenseCodeController::class, 'hold'])->name('try.hold');
        Route::get('/try/export', [NewLicenseCodeController::class, 'tryExport'])->name('try.export');
        Route::get('/try/records', [NewLicenseCodeController::class, 'records'])->name('try.records');

    });

    
include __DIR__.'/admin-auth.php'; // Include the admin authentication routes
