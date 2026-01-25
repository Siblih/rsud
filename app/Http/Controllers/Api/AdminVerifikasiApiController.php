<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class AdminVerifikasiApiController extends Controller
{
    // ===== VENDOR =====
    public function vendors()
{
    $vendors = User::where('role', 'vendor')
        ->whereHas('vendorProfile', fn($q) => $q->where('verification_status', 'pending'))
        ->with([
            'vendorProfile',
            'vendorProfile.vendorDocuments'
        ])
        ->get();

    return response()->json(['data' => $vendors]);
}


    public function vendorDetail($id)
{
    $vendor = User::with([
        'vendorProfile',
        'vendorProfile.vendorDocuments'
    ])->findOrFail($id);

    return response()->json(['data' => $vendor]);
}


    public function approveVendor($id)
    {
        $vendor = User::with('vendorProfile')->findOrFail($id);
        $vendor->vendorProfile->verification_status = 'verified';
        $vendor->vendorProfile->save();

        return response()->json(['message' => 'Vendor berhasil diverifikasi']);
    }

    public function rejectVendor($id)
    {
        $vendor = User::with('vendorProfile')->findOrFail($id);
        $vendor->vendorProfile->verification_status = 'rejected';
        $vendor->vendorProfile->save();

        return response()->json(['message' => 'Vendor ditolak']);
    }

    // ===== PRODUK =====
    public function produk()
{
    $produk = Product::where('status', 'pending')
        ->with('vendor')
        ->latest()
        ->get();

    return response()->json(['data' => $produk]);
}


   public function produkDetail($id)
{
    $product = Product::with('vendor')->find($id);

    if (!$product) {
        return response()->json([
            'message' => 'Produk tidak ditemukan',
            'product_id' => $id
        ], 404);
    }

    return response()->json([
        'data' => $product
    ]);
}


public function show($id)
{
    $product = Product::with('vendor')->findOrFail($id);

    return response()->json([
        'data' => $product
    ]);
}


    public function approveProduk($id)
    {
        $produk = Product::findOrFail($id);
        $produk->status = 'verified';
        $produk->save();

        return response()->json(['message' => 'Produk berhasil diverifikasi']);
    }

    public function rejectProduk($id)
    {
        $produk = Product::findOrFail($id);
        $produk->status = 'rejected';
        $produk->save();

        return response()->json(['message' => 'Produk ditolak']);
    }
}
