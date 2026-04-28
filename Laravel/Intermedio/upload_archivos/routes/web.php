<?php

use App\Http\Controllers\ArchivoController;
use Illuminate\Support\Facades\Route;

Route::prefix('archivos')->middleware('auth')->group(function () {
    Route::get('/',          [ArchivoController::class, 'index'])->name('archivos.index');
    Route::post('/',         [ArchivoController::class, 'store'])->name('archivos.store');
    Route::delete('/{nombre}', [ArchivoController::class, 'destroy'])->name('archivos.destroy');
});
