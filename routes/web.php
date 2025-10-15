<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Tamu as TamuController;

Route::get('/', function () {
    return view('halaman1');
});

// form submit -> handled by Tamu controller
Route::post('/submit-visit', [TamuController::class, 'store'])->name('submit.visit');

Route::get('/halaman2', function () {
    return view('halaman2');
})->name('halaman2');

Route::get('/admin', [TamuController::class, 'index'])->name('admin.dashboard');