<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // untuk mengambil data vendor/unit
use App\Models\Pengadaan;
use App\Models\LogActivity;
use App\Models\Product;


class VerifikasiController extends Controller
{
  public function index()
{
    // TAB VENDOR
    $users = User::where('role', 'vendor')
        ->whereHas('vendorProfile', fn($q) => $q->where('verification_status', 'pending'))
        ->with(['vendorProfile.vendorDocuments'])
        ->get();

    // TAB PRODUK
    $produk = Product::where('status', 'pending')
        ->with('vendor')   // ambil vendor name
        ->latest()
        ->get();

    return view('admin.verifikasi', compact('users', 'produk'));
}



public function detail($id)
{
    $user = User::with(['vendorProfile.vendorDocuments'])->findOrFail($id);
    return view('admin.verifikasi-detail', compact('user'));
}


public function setujui($id)
{
    $user = User::findOrFail($id);
    $user->vendorProfile->update(['verification_status' => 'verified']);
    return back()->with('success', 'Vendor berhasil disetujui.');
}

public function tolak($id)
{
    $user = User::findOrFail($id);
    $user->vendorProfile->update(['verification_status' => 'rejected']);
    return back()->with('error', 'Vendor telah ditolak.');
}
public function produk()
{
    $products = Product::where('status', 'pending')->latest()->get();
    return view('admin.verifikasi.produk', compact('products'));
}

public function setujuiProduk($id)
{
    Product::where('id', $id)->update(['status' => 'verified']);
    return back()->with('success', 'Produk berhasil diverifikasi!');
}

public function tolakProduk(Request $request, $id)
{
    Product::where('id', $id)->update([
        'status' => 'rejected',
        'reject_reason' => $request->reason
    ]);
    return back()->with('success', 'Produk ditolak.');
}
public function produkList()
{
    $produk = Product::where('status', 'pending')
        ->with(['vendor'])
        ->latest()
        ->get();

    return view('admin.verifikasi', [
        'users' => collect(), // kosong karena bukan tab vendor
        'produk' => $produk
    ]);
}

public function produkDetail($id)
{
    $product = Product::with('vendor')->findOrFail($id);
    return view('admin.verifikasi-produk-detail', compact('product'));
}


public function approveProduk($id)
{
    Product::where('id', $id)->update(['status' => 'verified']);
    return back()->with('success', 'Produk berhasil diverifikasi!');
}

public function rejectProduk(Request $request, $id)
{
    Product::where('id', $id)->update([
        'status' => 'rejected',
        'reject_reason' => $request->reason,
    ]);

    return back()->with('success', 'Produk telah ditolak.');
}


}
