<?php

use App\Http\Controllers\ProyectoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('proyectos.index'));
Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
Route::get('/proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('proyectos.show');
Route::get('/sobre-mi', fn() => view('paginas.sobre_mi'))->name('sobre_mi');

Route::middleware('auth')->group(function () {
    Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
});
