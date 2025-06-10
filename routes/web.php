<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // License Code Management
    Route::get('/license/generate', function () { return view('license.generate'); })->name('license.generate');
    Route::get('/license/list', function () { return view('license.list'); })->name('license.list');

    // Trial Code Management
    Route::get('/trial/generate', function () { return view('trial.generate'); })->name('trial.generate');
    Route::get('/trial/list', function () { return view('trial.list'); })->name('trial.list');

    // Agent Management
    Route::get('/agent/list', function () { return view('agent.list'); })->name('agent.list');
    Route::get('/agent/create', function () { return view('agent.create'); })->name('agent.create');

    // Other menus
    Route::get('/hotcoin/transaction', function () { return view('hotcoin.transaction'); })->name('hotcoin.transaction');
    Route::get('/all-agents/list', function () { return view('all-agents.list'); })->name('all-agents.list');
    Route::get('/password/change', function () { return view('password.change'); })->name('password.change');
    Route::get('/costing', function () { return view('costing.index'); })->name('costing.index');
});

require __DIR__.'/auth.php';
