<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PosCatalogController;
use App\Http\Controllers\Api\V1\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'sherin-api',
        'version' => 'v1',
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:6,1');

    Route::middleware(['auth:sanctum', 'active'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::middleware(['auth:sanctum', 'active', 'role:admin'])->get('/admin/ping', function () {
    return response()->json(['status' => 'ok', 'area' => 'admin']);
});

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('/stores', [StoreController::class, 'index']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{store}', [StoreController::class, 'show']);
    Route::put('/stores/{store}', [StoreController::class, 'update']);
    Route::delete('/stores/{store}', [StoreController::class, 'destroy']);

    Route::get('/pos/catalog', [PosCatalogController::class, 'show']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print']);
    Route::post('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel']);
});
