<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Tamu as TamuController;

Route::get('/', function () {
    return view('halaman1');
});

// form submit -> handled by Tamu controller
Route::post('/submit-visit', [TamuController::class, 'store'])->name('submit.visit');

// Halaman terima kasih sudah mengisi form
Route::get('/halaman2', function () {return view('halaman2');})->name('halaman2');

// admin dashboard 
Route::get('/admin', [TamuController::class, 'index'])->name('admin.dashboard');

// delete single tamu
Route::delete('/tamu/{id}', [TamuController::class, 'destroy'])->name('tamu.destroy');

// bulk delete tamu (expects 'ids' array)
Route::post('/tamu/bulk-delete', [TamuController::class, 'bulkDestroy'])->name('tamu.bulkDestroy');