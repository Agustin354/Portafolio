<?php

use App\Http\Controllers\FacturaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/pedidos/{pedido}/factura',         [FacturaController::class, 'descargar'])->name('facturas.descargar');
    Route::get('/pedidos/{pedido}/factura/preview', [FacturaController::class, 'preview'])->name('facturas.preview');
});
