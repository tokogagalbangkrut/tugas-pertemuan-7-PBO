<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KeranjangController;

Route::redirect('/', '/buku')->name('home');

Route::get('/katalog', [BukuController::class, 'catalog'])->name('buku.catalog');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
Route::post('/keranjang/{kode}', [KeranjangController::class, 'store'])->name('keranjang.store');
Route::patch('/keranjang/{kode}', [KeranjangController::class, 'update'])->name('keranjang.update');
Route::delete('/keranjang/{kode}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');

// Route modul Buku
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');
Route::get('/buku/{kode}/edit', [BukuController::class, 'edit'])->name('buku.edit');
Route::get('/buku/{kode}', [BukuController::class, 'show'])->name('buku.show');
Route::match(['put', 'patch'], '/buku/{kode}', [BukuController::class, 'update'])->name('buku.update');
Route::delete('/buku/{kode}', [BukuController::class, 'destroy'])->name('buku.destroy');