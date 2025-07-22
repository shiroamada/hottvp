<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrialCodeController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\LicenseCodeController;
use App\Http\Controllers\CostingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\NewLicenseCodeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, config('app.supported_locales'))) {
        Session::put('locale', $locale);
    }
    return Redirect::back();
})->name('language.switch');

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/license/list', [LicenseCodeController::class, 'index'])->name('license.list');
    Route::get('/trial/list', [TrialCodeController::class, 'index'])->name('trial.list');

    // Agent Management
    Route::prefix('agents')->name('agent.')->group(function () {
        Route::get('/', [AgentController::class, 'list'])->name('list');
        Route::get('/create', [AgentController::class, 'create'])->name('create');
    });

    

    // License Code Management
    Route::get('/license/generate', [NewLicenseCodeController::class, 'create'])->name('license.generate');
    Route::post('/license/generate', [NewLicenseCodeController::class, 'store'])->name('license.store');

    // Trial Code Management
    Route::get('/trial/generate', function () { return view('trial.generate'); })->name('trial.generate');

    // Agent Management
    Route::get('/agent/list', function () { return view('agent.list'); })->name('agent.list');
    Route::get('/agent/create', function () { return view('agent.create'); })->name('agent.create');

    // Other menus
    Route::get('/hotcoin/transaction', function () { return view('hotcoin.transaction'); })->name('hotcoin.transaction');
    Route::get('/all-agents/list', function () { return view('all-agents.list'); })->name('all-agents.list');
    Route::get('/password/change', function () { return view('password.change'); })->name('password.change');
    Route::get('/costing', [CostingController::class, 'index'])->name('costing.index');
    Route::post('/costing/update', [CostingController::class, 'update'])->name('costing.update');
});

require __DIR__.'/auth.php';
