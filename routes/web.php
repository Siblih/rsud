<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorProfileController;



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

// ✅ hanya vendor yang bisa daftar mandiri
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =======================
// DASHBOARD BERDASARKAN ROLE
// =======================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('dashboards.admin'))->name('admin.dashboard');
    // nanti di sini kita tambahkan route CRUD user (unit/evaluator/vendor)
});

Route::middleware(['auth', 'role:unit'])->group(function () {
    Route::get('/unit/dashboard', fn() => view('dashboards.unit'))->name('unit.dashboard');
});

Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', fn() => view('vendor.dashboard'))->name('vendor.dashboard');

});

Route::middleware(['auth', 'role:evaluator'])->group(function () {
    Route::get('/evaluator/dashboard', fn() => view('dashboards.evaluator'))->name('evaluator.dashboard');
});
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/profile/edit', [VendorProfileController::class, 'edit'])->name('vendor.profile.edit');
    Route::post('/vendor/profile/update', [VendorProfileController::class, 'update'])->name('vendor.profile.update');
});
