<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengadaan;
use App\Models\LogActivity;


class DashboardController extends Controller
{
    public function index()
{
    $totalVendor = User::where('role', 'vendor')->count();
    $totalUnit = User::where('role', 'unit')->count();
    $totalPengadaan = Pengadaan::count();
    $menunggu = Pengadaan::where('status', 'menunggu')->count();
    $selesai = Pengadaan::where('status', 'selesai')->count();

    return view('admin.dashboard', compact(
        'totalVendor', 'totalUnit', 'totalPengadaan', 'menunggu', 'selesai'
    ));
}

}
