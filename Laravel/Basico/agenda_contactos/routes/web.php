<?php

use App\Http\Controllers\ContactoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('contactos.index'));
Route::resource('contactos', ContactoController::class);
