<?php

use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('productos.index'));

Route::resource('productos', ProductoController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::resource('pedidos', PedidoController::class)->only(['index', 'store', 'show']);
});
