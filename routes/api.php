<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::get('/test', function () {
    return response()->json([
        'message' => 'API Laravel siap dipanggil Flutter 🚀'
    ]);
    Route::post('/login', [AuthController::class, 'login']);

});
