<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CostingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LicenseCodeController;
use App\Http\Controllers\Admin\NewLicenseCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrialCodeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, config('app.supported_locales'))) {
        Session::put('locale', $locale);
    }

    return Redirect::back();
})->name('language.switch');

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth:admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // This route seems to be unused, replaced by NewLicenseCodeController
    // Route::get('/license/list', [LicenseCodeController::class, 'index'])->name('license.list');
    
    // Agent Management
    Route::prefix('agents')->name('agent.')->group(function () {
        Route::get('/', [AgentController::class, 'list'])->name('list');
        Route::get('/create', [AgentController::class, 'create'])->name('create');
    });

    // License Code Management
    Route::get('/license/generate', [NewLicenseCodeController::class, 'create'])->name('license.generate');
    Route::post('/license/generate', [NewLicenseCodeController::class, 'store'])->name('license.store');
    Route::get('/license/list', [NewLicenseCodeController::class, 'index'])->name('license.list');
    Route::get('/license/export', [NewLicenseCodeController::class, 'export'])->name('license.export');
    
    Route::get('/license/detail', [NewLicenseCodeController::class, 'detail'])->name('license.detail');
    Route::get('/license/down', [NewLicenseCodeController::class, 'down'])->name('license.down');

    Route::post('/license/{code_id}/update', [NewLicenseCodeController::class, 'update'])->name('license.update');

    // Trial Code Management
    Route::get('/trial/list', [TrialCodeController::class, 'index'])->name('trial.list');
    Route::get('/trial/generate', [TrialCodeController::class, 'create'])->name('trial.generate');
    Route::post('/trial/generate', [TrialCodeController::class, 'store'])->name('trial.store');
    Route::get('/trial/export', [TrialCodeController::class, 'export'])->name('trial.export');

    // Agent Management
    // Agent Management
Route::get('/agent/list', function () {
    return view('agent.list');
})->name('agent.list.view');
    Route::get('/agent/create', function () {
        return view('agent.create');
    })->name('agent.create.view');

    // Other menus
    Route::get('/hotcoin/transaction', function () {
        return view('hotcoin.transaction');
    })->name('hotcoin.transaction');
    Route::get('/all-agents/list', function () {
        return view('all-agents.list');
    })->name('all-agents.list');
    Route::get('/password/change', function () {
        return view('password.change');
    })->name('password.change');
    Route::get('/costing', [CostingController::class, 'index'])->name('costing.index')->middleware('check.level');
    Route::post('/costing/update', [CostingController::class, 'update'])->name('costing.update')->middleware('check.level');
});
//TO DO remove in production
Route::get('/debug-phpinfo', function () {
    phpinfo();
});

require __DIR__.'/auth.php';