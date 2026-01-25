<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminKatalogController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::with([
            'vendor.vendorProfile'
        ])->where('status', 'verified');

        // ================= FILTER =================
        if ($request->tipe_produk) {
            $q->where('tipe_produk', $request->tipe_produk);
        }

        if ($request->kategori) {
            $q->where('kategori', $request->kategori);
        }

        if ($request->tkdn_sertif !== null) {
            $q->where('is_tkdn_sertifikat', $request->tkdn_sertif);
        }

        if ($request->asal) {
            $q->where(
                'is_dalam_negeri',
                $request->asal === 'dalam' ? 1 : 0
            );
        }

        if ($request->umk !== null) {
            $q->where('is_umk', $request->umk);
        }

        if ($request->konsolidasi !== null) {
            $q->where('is_konsolidasi', $request->konsolidasi);
        }

        if ($request->min) {
            $q->where('price', '>=', $request->min);
        }

        if ($request->max) {
            $q->where('price', '<=', $request->max);
        }

        $products = $q->latest()->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => (float) $p->price,
                'tipe_produk' => $p->tipe_produk,
                'kategori' => $p->kategori,

                // ✅ PHOTO DARI JSON
                'photo' => is_array($p->photos) && count($p->photos) > 0
                    ? asset('storage/' . $p->photos[0])
                    : null,

                'alamat_vendor' =>
                    $p->vendor->vendorProfile->alamat
                    ?? 'Lokasi tidak tersedia',

                'tkdn' => $p->tkdn,
                'is_umk' => $p->is_umk,
                'is_dalam_negeri' => $p->is_dalam_negeri,
            ];
        });

        return response()->json([
            'data' => $products
        ]);
    }

    public function show($id)
    {
        // ❌ HAPUS with('photos')
        $product = Product::with([
            'vendor.vendorProfile'
        ])->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'brand' => $product->brand,
                'model' => $product->model,
                'sku' => $product->sku,
                'warna' => $product->warna,
                'kategori' => $product->kategori,
                'tkdn' => $product->tkdn,
                'is_umk' => $product->is_umk,
                'is_dalam_negeri' => $product->is_dalam_negeri,

                // ✅ PHOTO DARI JSON
                'photos' => $product->photos ?? [],

                'vendor' => [
                    'name' => $product->vendor?->name,
                    'vendor_profile' => $product->vendor?->vendorProfile,
                ]
            ]
        ]);
    }
}
