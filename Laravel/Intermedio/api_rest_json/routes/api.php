<?php

use App\Http\Controllers\Api\ProductoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('productos', ProductoController::class);
});
