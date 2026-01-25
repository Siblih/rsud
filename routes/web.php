<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\Vendor\VendorDocumentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorPengadaanController;
use App\Http\Controllers\Vendor\VendorNotifikasiController;
use App\Http\Controllers\Vendor\VendorRiwayatController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Admin\KontrakController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Vendor\VendorPurchaseOrderController;
use App\Http\Controllers\Vendor\VendorKontrakController;
use App\Http\Controllers\Vendor\PaymentController;
use App\Http\Controllers\Admin\AdminPenawaranController;

use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PengadaanController as AdminPengadaanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LogActivityController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\KatalogController;
use App\Http\Controllers\Admin\AdminBastController;




// =======================
// HALAMAN AWAL
// =======================
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aplikasi Pengadaan RSUD Bangil
| Role: admin, unit, vendor, evaluator
| Catatan:
| - Hanya VENDOR yang bisa register mandiri.
| - Akun Unit, Evaluator, dan Admin dibuat oleh Admin.
|
*/

// =======================
// AUTH
// =======================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =======================
// DASHBOARD BERDASARKAN ROLE
// =======================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ======================
        // VERIFIKASI VENDOR
        // ======================
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi');  
        Route::get('/verifikasi/{id}', [VerifikasiController::class, 'detail'])->name('verifikasi.detail');
        Route::post('/verifikasi/{id}/setujui', [VerifikasiController::class, 'setujui'])->name('verifikasi.setujui');
        Route::post('/verifikasi/{id}/tolak', [VerifikasiController::class, 'tolak'])->name('verifikasi.tolak');

        Route::post('/pengadaan/{id}/metode',
    [AdminPengadaanController::class, 'setMetode']
)->name('pengadaan.setMetode');

        // Data Vendor (dashboard shortcut)
Route::get('/admin/vendor/data-vendor', [VendorController::class, 'dataVendor'])
    ->name('vendor.data_vendor');
   Route::get('/vendor/detail/{id}', [VendorController::class, 'detail'])
    ->name('vendor.detail');

Route::get('/admin/vendor/{id}/documents', 
    [VendorController::class, 'documents']
)->name('vendor.documents');
Route::get('/bast/{id}', [AdminBastController::class, 'show'])
    ->name('bast.show');

Route::post('/bast/{id}', [AdminBastController::class, 'upload'])
    ->name('bast.upload');



Route::post('/admin/pengadaan/{id}/update-status', [PengadaanController::class, 'updateStatus'])->name('admin.pengadaan.updateStatus');

// 📋 List pengadaan yang punya penawaran
    Route::get('/penawaran', [AdminPenawaranController::class, 'index'])
        ->name('penawaran.index');

    // 🔍 Detail penawaran per pengadaan
    Route::get('/penawaran/{pengadaan}', [AdminPenawaranController::class, 'show'])
        ->name('penawaran.show');

    // 🏆 Pilih pemenang
    Route::post(
    '/penawaran/{id}/pemenang',
    [AdminPengadaanController::class, 'setPemenang']
)->name('penawaran.setPemenang');



        // ======================
        // VERIFIKASI PRODUK
        // ======================
       Route::get('/verifikasi/produk', [VerifikasiController::class, 'produkList'])
            ->name('verifikasi.produk');
        Route::get('/verifikasi/produk/{id}', [VerifikasiController::class, 'produkDetail'])
            ->name('verifikasi.produk.detail');
        Route::post('/verifikasi/produk/{id}/setujui', [VerifikasiController::class, 'approveProduk'])
            ->name('verifikasi.produk.setujui');
        Route::post('/verifikasi/produk/{id}/tolak', [VerifikasiController::class, 'rejectProduk'])
            ->name('verifikasi.produk.tolak');

Route::post('/admin/pengadaan/{id}/status', 
    [AdminPengadaanController::class, 'updateStatus']
)->name('admin.pengadaan.updateStatus');
Route::get('kontrak/create/{pengadaan}', [KontrakController::class, 'create'])
        ->name('kontrak.create');

    Route::post('kontrak', [KontrakController::class, 'store'])
        ->name('kontrak.store');


        // ======================
        // VERIFIKASI PENGADAAN UNIT
        // ======================
        Route::get('/pengadaan', [AdminPengadaanController::class, 'index'])->name('pengadaan');
        Route::get('/pengadaan/{id}', [AdminPengadaanController::class, 'show'])->name('pengadaan.show');
        Route::post('/pengadaan/{id}/status', [AdminPengadaanController::class, 'updateStatus'])->name('pengadaan.updateStatus');


        // ======================
        // MENU LAIN ADMIN
        // ======================
       Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
       Route::get('/katalog/{id}', [KatalogController::class, 'detail'])
    ->name('katalog.detail');
Route::post('/katalog/{product}/beli-langsung/{pengadaan}',
            [KatalogController::class, 'beliLangsung']
        )->name('katalog.beliLangsung');

Route::get('/katalog/{id}/vendor', [KatalogController::class, 'vendorShow'])
    ->name('katalog.show');
Route::get('/kontrak', [KontrakController::class, 'index'])->name('kontrak.index');
    Route::get('/kontrak/{id}', [KontrakController::class, 'show'])->name('kontrak.show');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/log', [LogController::class, 'index'])->name('log');
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
      // ======================
// PURCHASE ORDER (ADMIN)
// ======================
Route::prefix('po')->name('po.')->group(function () {

    // List semua PO
    Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');

    // Form create PO berdasarkan kontrak
    Route::get('/create/{kontrak_id}', [PurchaseOrderController::class, 'create'])->name('create');

    // Simpan PO baru
    Route::post('/store/{kontrak_id}', [PurchaseOrderController::class, 'store'])->name('store');

    // Detail PO
    Route::get('/{id}', [PurchaseOrderController::class, 'show'])->name('show');

    // Edit PO
    Route::get('/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');

    // Update PO
    Route::put('/{id}', [PurchaseOrderController::class, 'update'])->name('update');
});
Route::get('/po/{id}/generate-pdf', [PurchaseOrderController::class, 'generatePdf'])->name('po.generate-pdf');
Route::post('/po/{id}/generate-signed-pdf', [PurchaseOrderController::class, 'generateSignedPdf'])->name('po.generateSignedPdf');


    });



Route::middleware(['auth', 'role:unit'])->group(function() {
    // Dashboard Unit
    Route::get('/unit/dashboard', [UnitController::class, 'dashboard'])->name('unit.dashboard');

    // Route pengadaan unit
    Route::prefix('unit/pengadaan')->name('unit.pengadaan.')->group(function() {
        Route::get('/', [PengadaanController::class, 'index'])->name('index');
        Route::get('/create', [PengadaanController::class, 'create'])->name('create');
        Route::post('/', [PengadaanController::class, 'store'])->name('store');
        Route::get('/{pengadaan}/edit', [PengadaanController::class, 'edit'])->name('edit');
        Route::put('/{pengadaan}', [PengadaanController::class, 'update'])->name('update');
        Route::delete('/{pengadaan}', [PengadaanController::class, 'destroy'])->name('destroy');
    });
});



Route::middleware(['auth', 'role:vendor'])->group(function () {

    // 🏠 Dashboard
    Route::get('/vendor/dashboard', fn() => view('vendor.dashboard'))->name('vendor.dashboard');

    // 🏢 Profil Vendor
    Route::get('/vendor/profile', [VendorProfileController::class, 'show'])->name('vendor.profile.show');
    Route::get('/vendor/profile/edit', [VendorProfileController::class, 'edit'])->name('vendor.profile.edit');
    Route::post('/vendor/profile/update', [VendorProfileController::class, 'update'])->name('vendor.profile.update');

    // 📦 Produk Saya
    Route::get('/vendor/produk', [VendorProductController::class, 'index'])->name('vendor.produk');
    Route::get('/vendor/produk/create', [VendorProductController::class, 'create'])->name('vendor.produk.create');
    Route::post('/vendor/produk', [VendorProductController::class, 'store'])->name('vendor.produk.store');
    Route::get('/vendor/produk/{id}', [VendorProductController::class, 'show'])->name('vendor.produk.show');
    Route::get('/vendor/produk/{id}/edit', [VendorProductController::class, 'edit'])->name('vendor.produk.edit');
    Route::put('/vendor/produk/{id}', [VendorProductController::class, 'update'])->name('vendor.produk.update');
    Route::delete('/vendor/produk/{id}', [VendorProductController::class, 'destroy'])->name('vendor.produk.destroy');


    // 💼 Pengadaan / Kompetisi
    Route::get('/vendor/pengadaan', [VendorPengadaanController::class, 'index'])->name('vendor.pengadaan');
    Route::get('/vendor/pengadaan/{id}', [VendorPengadaanController::class, 'show'])->name('vendor.pengadaan.show');
    Route::post('/vendor/pengadaan/{id}/penawaran', [VendorPengadaanController::class, 'submitPenawaran'])->name('vendor.pengadaan.penawaran');

   // 📜 Kontrak & Pembayaran
Route::get('/vendor/kontrak', [VendorKontrakController::class, 'index'])
    ->name('vendor.kontrak');

Route::get('/vendor/kontrak/{id}', [VendorKontrakController::class, 'show'])
    ->name('vendor.kontrak.show');

// 📤 Halaman Form Upload Dokumen Pembayaran
Route::get('/vendor/kontrak/{id}/upload', [VendorKontrakController::class, 'uploadForm'])
    ->name('vendor.kontrak.upload.form');

// 📥 Proses Upload Dokumen Pembayaran
Route::post('/vendor/kontrak/{id}/upload', [VendorKontrakController::class, 'upload'])
    ->name('vendor.kontrak.upload');



    Route::get('/vendor/po', [VendorPurchaseOrderController::class, 'index'])->name('vendor.po.index');
Route::get('/vendor/po/{id}', [VendorPurchaseOrderController::class, 'show'])->name('vendor.po.show');
Route::post('/vendor/po/{id}/sign', [VendorPurchaseOrderController::class, 'sign'])->name('vendor.po.sign');



    

    // 📂 Dokumen
    Route::get('/vendor/documents/index', [VendorDocumentController::class, 'index'])->name('vendor.documents.index');
    Route::get('/vendor/documents/upload', [VendorDocumentController::class, 'create'])->name('vendor.documents.create');
    Route::post('/vendor/documents/upload', [VendorDocumentController::class, 'store'])->name('vendor.documents.store');

    // ⚙️ Pengaturan Akun
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update-password', [SettingsController::class, 'updatePassword'])->name('vendor.update-password');
});



