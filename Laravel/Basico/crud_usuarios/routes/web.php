<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('usuarios.index'));
Route::resource('usuarios', UsuarioController::class);
