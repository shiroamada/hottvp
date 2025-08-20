<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NewLicenseCodeController;

Route::get('/try/list', [NewLicenseCodeController::class, 'list'])->name('try.list');
Route::get('/try/add', [NewLicenseCodeController::class, 'add'])->name('try.add');
Route::post('/try/hold', [NewLicenseCodeController::class, 'hold'])->name('try.hold');
Route::get('/try/export', [NewLicenseCodeController::class, 'tryExport'])->name('try.export');
Route::get('/try/records', [NewLicenseCodeController::class, 'records'])->name('try.records');
