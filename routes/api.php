<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VendorPengadaanController;
use App\Http\Controllers\Api\VendorProductController;
use App\Http\Controllers\Api\VendorProfileController;


/*
|--------------------------------------------------------------------------
| PUBLIC (TIDAK PERLU TOKEN)
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json([
        'message' => 'API Laravel siap dipanggil Flutter 🚀'
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| PROTECTED (WAJIB TOKEN SANCTUM)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // PROFILE

    Route::get('/profile', [VendorProfileController::class, 'show']);
    Route::put('/profile', [VendorProfileController::class, 'update']);


    // ===== VENDOR PRODUCTS =====
    Route::get('/vendor/products', [VendorProductController::class, 'index']);
    Route::post('/vendor/products', [VendorProductController::class, 'store']);
    Route::get('/vendor/products/{id}', [VendorProductController::class, 'show']);
    Route::put('/vendor/products/{id}', [VendorProductController::class, 'update']);
    Route::delete('/vendor/products/{id}', [VendorProductController::class, 'destroy']);

    // ===== PENGADAAN =====
    Route::get('/vendor/pengadaan', [VendorPengadaanController::class, 'index']);
});

