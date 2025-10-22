<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Tamu as TamuController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('halaman1');
});

// form submit -> handled by Tamu controller
Route::post('/submit-visit', [TamuController::class, 'store'])->name('submit.visit');

// Halaman terima kasih sudah mengisi form
Route::get('/halaman2', function () {return view('halaman2');})->name('halaman2');

// admin dashboard (protected)
Route::get('/admin', [TamuController::class, 'index'])->middleware('auth')->name('admin.dashboard');
Route::get('/login', function () {return view('admin1');})->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// delete single tamu (protected)
Route::delete('/tamu/{id}', [TamuController::class, 'destroy'])->middleware('auth')->name('tamu.destroy');

// bulk delete tamu (expects 'ids' array) (protected)
Route::post('/tamu/bulk-delete', [TamuController::class, 'bulkDestroy'])->middleware('auth')->name('tamu.bulkDestroy');

// update keterangan (admin only)
Route::post('/tamu/{id}/keterangan', [TamuController::class, 'updateKeterangan'])->middleware('auth')->name('tamu.updateKeterangan');

// Rekapan exports (protected)
Route::get('/rekapan/export/excel', [\App\Http\Controllers\RekapanController::class, 'exportExcel'])->middleware('auth')->name('rekapan.export.excel');
Route::get('/rekapan/export/pdf', [\App\Http\Controllers\RekapanController::class, 'exportPdf'])->middleware('auth')->name('rekapan.export.pdf');