<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Route modul Buku
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');