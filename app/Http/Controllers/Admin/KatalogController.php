<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $r)
    {
        $products = Product::query()
            ->where('status', 'verified');

        // FILTER TIPE PRODUK
        if ($r->tipe_produk) {
            $products->where('tipe_produk', $r->tipe_produk);
        }

        // FILTER KATEGORI
        if ($r->kategori) {
            $products->where('kategori', $r->kategori);
        }

        // FILTER TKDN SERTIFIKAT
        if ($r->tkdn_sertif !== null && $r->tkdn_sertif !== '') {
            $products->where('is_tkdn_sertifikat', $r->tkdn_sertif);
        }

        // FILTER ASAL PRODUK
        if ($r->asal == 'dalam') {
            $products->where('is_dalam_negeri', 1);
        } elseif ($r->asal == 'impor') {
            $products->where('is_dalam_negeri', 0);
        }

        // FILTER UMK
        if ($r->umk !== null && $r->umk !== '') {
            $products->where('is_umk', $r->umk);
        }

        // FILTER KONSOLIDASI
        if ($r->konsolidasi !== null && $r->konsolidasi !== '') {
            $products->where('is_konsolidasi', $r->konsolidasi);
        }

        // FILTER HARGA
        if ($r->min) {
            $products->where('price', '>=', $r->min);
        }

        if ($r->max) {
            $products->where('price', '<=', $r->max);
        }

        $products = $products->latest()->get();

        return view('admin.katalog.index', compact('products'));
    }

    /**
     * DETAIL PRODUK
     */
    public function detail($id)
    {
        $product = Product::with(['vendor.vendorProfile'])->findOrFail($id);

        return view('admin.katalog.detail', compact('product'));
    }


    /**
     * DETAIL VENDOR (IDENTITAS + PRODUK VENDOR)
     */
    public function vendorDetail($vendorId)
    {
        // data vendor (user + vendor profile)
        $vendor = User::with('vendorProfile')->findOrFail($vendorId);

        // ambil semua produk vendor ini
        $products = Product::where('vendor_id', $vendorId)
            ->where('status', 'verified')
            ->get();

        return view('admin.katalog.vendor-detail', compact('vendor', 'products'));
    }
    public function vendorShow($id)
{
    // id = user_id vendor
    $vendor = \App\Models\User::with('vendorProfile')->findOrFail($id);

    // Ambil semua produk milik vendor
    $products = Product::where('vendor_id', $id)
        ->where('status', 'verified')
        ->get();

    return view('admin.katalog.show', compact('vendor', 'products'));
}


}
