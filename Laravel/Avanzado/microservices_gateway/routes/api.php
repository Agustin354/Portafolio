<?php

use App\Http\Controllers\GatewayController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::any('/gateway/{servicio}/{path?}', [GatewayController::class, 'proxy'])
        ->where('path', '.*')
        ->name('gateway');
});
