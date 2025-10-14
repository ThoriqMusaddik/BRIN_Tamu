<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('halaman1');
});

// form submit -> redirect to thank you page
Route::post('/submit-visit', function (\Illuminate\Http\Request $request) {
    // simple handling: you can validate/save here
    // $data = $request->validate([ ... ]);
    return redirect()->route('halaman2');
})->name('submit.visit');

Route::get('/halaman2', function () {
    return view('adminDashboard');
})->name('halaman2');

