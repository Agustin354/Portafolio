<?php

use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TareaController::class, 'index'])->name('tareas.index');
Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
Route::patch('/tareas/{tarea}/completar', [TareaController::class, 'completar'])->name('tareas.completar');
Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');
