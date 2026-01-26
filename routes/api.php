<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VendorPengadaanController;
use App\Http\Controllers\Api\VendorProductController;
use App\Http\Controllers\Api\VendorProfileController;
use App\Http\Controllers\Api\VendorKontrakController;
use App\Http\Controllers\Api\VendorAccountController;
use App\Http\Controllers\Api\VendorDocumentController;
use App\Http\Controllers\Api\AdminVerifikasiApiController;
use App\Http\Controllers\Api\AdminKatalogController;
use App\Http\Controllers\Api\PengadaanAdminController;



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

    // ===== PROFILE =====
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
    Route::get('/vendor/pengadaan/{id}', [VendorPengadaanController::class, 'show']);
    Route::get('/vendor/kontrak', [VendorKontrakController::class, 'index']);
    Route::get('/vendor/kontrak/{id}', [VendorKontrakController::class, 'show']);
    Route::post('/vendor/kontrak/{id}/upload', [VendorKontrakController::class, 'upload']);
        Route::post(
        'vendor/pengadaan/{id}/penawaran',
        [VendorPengadaanController::class, 'storePenawaran']
    );


    Route::post('/vendor/account/password', [VendorAccountController::class, 'updatePassword']);
    Route::get('/vendor/documents', [VendorDocumentController::class, 'show']);
    
 Route::get('/katalog', [AdminKatalogController::class, 'index']);
        Route::get('/katalog/{id}', [AdminKatalogController::class, 'show']);
    // ===== ADMIN VERIFIKASI =====
    Route::prefix('admin/verifikasi')->group(function () {
        // Vendor
        Route::get('/vendors', [AdminVerifikasiApiController::class, 'vendors']); // list vendor menunggu verifikasi
        Route::get('/vendors/{id}', [AdminVerifikasiApiController::class, 'vendorDetail']); // detail vendor
        Route::post('/vendors/{id}/approve', [AdminVerifikasiApiController::class, 'approveVendor']);
        Route::post('/vendors/{id}/reject', [AdminVerifikasiApiController::class, 'rejectVendor']);

        // Produk
        Route::get('/produk', [AdminVerifikasiApiController::class, 'produk']); // list produk menunggu verifikasi
        Route::get('/produk/{id}', [AdminVerifikasiApiController::class, 'produkDetail']); // detail produk
        Route::post('/produk/{id}/approve', [AdminVerifikasiApiController::class, 'approveProduk']);
        Route::post('/produk/{id}/reject', [AdminVerifikasiApiController::class, 'rejectProduk']);

       Route::get('/pengadaan', [PengadaanAdminController::class, 'index']);
    Route::get('/pengadaan/{id}', [PengadaanAdminController::class, 'show']);
    Route::post('/pengadaan/{id}/update-status', [PengadaanAdminController::class, 'updateStatus']);


    });
});
