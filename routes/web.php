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


//TO DO remove in production
Route::get('/debug-phpinfo', function () {
    phpinfo();
});

require __DIR__.'/auth.php';