<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CampaignController;
use App\Http\Controllers\Api\V1\CampaignDetailController;
use App\Http\Controllers\Api\V1\CampaignHistoriqueController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CrossProductController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\EpinglesController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PosCatalogController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RdvController;
use App\Http\Controllers\Api\V1\SmsCrossSellingController;
use App\Http\Controllers\Api\V1\StoreController;
use Illuminate\Support\Facades\Route;

Route::post('/payments/cmi/callback', [PaymentController::class, 'cmiCallback']);

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

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{customer}', [CustomerController::class, 'show']);
    Route::put('/customers/{customer}', [CustomerController::class, 'update']);
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::get('/campaign-details/filter-defaults', [CampaignDetailController::class, 'filterDefaults']);
    Route::get('/campaign-details', [CampaignDetailController::class, 'index']);
    Route::post('/campaign-details', [CampaignDetailController::class, 'store']);
    Route::get('/campaign-details/{campaignDetail}', [CampaignDetailController::class, 'show']);
    Route::put('/campaign-details/{campaignDetail}', [CampaignDetailController::class, 'update']);
    Route::delete('/campaign-details/{campaignDetail}', [CampaignDetailController::class, 'destroy']);
    Route::get('/campaign-historiques', [CampaignHistoriqueController::class, 'index']);

    Route::get('/cross-products', [CrossProductController::class, 'index']);
    Route::post('/cross-products', [CrossProductController::class, 'store']);
    Route::get('/cross-products/{crossProduct}', [CrossProductController::class, 'show']);
    Route::put('/cross-products/{crossProduct}', [CrossProductController::class, 'update']);
    Route::delete('/cross-products/{crossProduct}', [CrossProductController::class, 'destroy']);
    Route::get('/sms-cross-selling', [SmsCrossSellingController::class, 'index']);

    Route::get('/epingles', [EpinglesController::class, 'index']);
    Route::get('/rdvs', [RdvController::class, 'index']);
    Route::post('/rdvs', [RdvController::class, 'store']);
    Route::get('/rdvs/{rdv}', [RdvController::class, 'show']);
    Route::put('/rdvs/{rdv}', [RdvController::class, 'update']);
    Route::delete('/rdvs/{rdv}', [RdvController::class, 'destroy']);

    Route::get('/payments/preview', [PaymentController::class, 'preview']);
    Route::post('/payments/checkout', [PaymentController::class, 'checkout']);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{paymentHistory}', [PaymentController::class, 'show']);

    Route::get('/pos/catalog', [PosCatalogController::class, 'show']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print']);
    Route::post('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel']);
});
