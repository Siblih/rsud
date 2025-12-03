<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogActivity; // buat model log aktivitas jika belum ada
use App\Models\User;
use App\Models\Pengadaan;



class LogController extends Controller
{
    public function index()
    {
        $logs = LogActivity::latest()->get();
        return view('admin.log.dashboard', compact('logs'));
    }
}
