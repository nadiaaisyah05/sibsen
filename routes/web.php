<?php

use App\Http\Controllers\LogbookEntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RekapUangSakuController;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LogbookEntryController::class, 'dashboard'])->name('dashboard');

    Route::get('/logbook', [LogbookEntryController::class, 'index'])->name('logbook.index');
    Route::post('/logbook/check-in', [LogbookEntryController::class, 'checkIn'])->name('logbook.checkin');
    Route::post('/logbook/check-out', [LogbookEntryController::class, 'checkOut'])->name('logbook.checkout');
    Route::post('/logbook/izin', [LogbookEntryController::class, 'izin'])->name('logbook.izin');
    Route::get('/logbook/{logbook}/edit', [LogbookEntryController::class, 'edit'])->name('logbook.edit');
    Route::put('/logbook/{logbook}', [LogbookEntryController::class, 'update'])->name('logbook.update');
    Route::delete('/logbook/{logbook}', [LogbookEntryController::class, 'destroy'])->name('logbook.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
        Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
        Route::patch('/absensi/{logbook}/validasi', [AbsensiController::class, 'validasi'])->name('absensi.validasi');
        Route::get('/absensi/{logbook}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('/absensi/{logbook}', [AbsensiController::class, 'update'])->name('absensi.update');
        Route::delete('/absensi/{logbook}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
        Route::get('/uang-saku', [RekapUangSakuController::class, 'index'])->name('uangsaku');
        Route::get('/uang-saku/export', [RekapUangSakuController::class, 'export'])->name('uangsaku.export');
        Route::patch('/uang-saku/settings', [RekapUangSakuController::class, 'updateSettings'])->name('uangsaku.settings');

        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';