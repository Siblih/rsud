<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Pengadaan;
use App\Models\Penawaran;
use App\Models\PenawaranItem;

class KatalogController extends Controller
{
    public function index(Request $r)
    {
        $products = Product::query()->where('status', 'verified');

        if ($r->tipe_produk) {
            $products->where('tipe_produk', $r->tipe_produk);
        }

        if ($r->kategori) {
            $products->where('kategori', $r->kategori);
        }

        if ($r->tkdn_sertif !== null && $r->tkdn_sertif !== '') {
            $products->where('is_tkdn_sertifikat', $r->tkdn_sertif);
        }

        if ($r->asal == 'dalam') {
            $products->where('is_dalam_negeri', 1);
        } elseif ($r->asal == 'impor') {
            $products->where('is_dalam_negeri', 0);
        }

        if ($r->umk !== null && $r->umk !== '') {
            $products->where('is_umk', $r->umk);
        }

        if ($r->konsolidasi !== null && $r->konsolidasi !== '') {
            $products->where('is_konsolidasi', $r->konsolidasi);
        }

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
private function hargaSingkat($harga)
    {
        if ($harga >= 1_000_000_000_000) return number_format($harga / 1_000_000_000_000, 2) . 'T';
        if ($harga >= 1_000_000_000)     return number_format($harga / 1_000_000_000, 2) . 'M';
        if ($harga >= 1_000_000)         return number_format($harga / 1_000_000, 2) . 'jt';
        if ($harga >= 1_000)             return number_format($harga / 1_000, 2) . 'rb';
        return number_format($harga, 0);
    }

    /**
     * BELI LANGSUNG (INTI SISTEM KAMU)
     */
    public function beliLangsung(Request $request, Product $product, Pengadaan $pengadaan)
    {
        // 1. Cegah pilih ulang
        if ($pengadaan->katalog_product_id) {
            return back()->with('error', 'Produk sudah dipilih');
        }

        // 2. Tempel produk ke pengadaan
        $pengadaan->update([
            'katalog_product_id' => $product->id,
            'vendor_id'          => $product->vendor_id,
            'metode_pengadaan'   => 'langsung',
        ]);

        // 3. Ambil qty (default 1)
        $qty = $request->qty ?? 1;

        // 4. Hitung harga
        $harga = $product->price * $qty;

        // ⚠️ Cek overflow BIGINT MySQL
        if ($harga > 9223372036854775807) {
            return back()->with('error', 'Harga terlalu besar untuk sistem');
        }

        // 5. Buat / ambil penawaran
        $penawaran = Penawaran::firstOrCreate(
            [
                'pengadaan_id' => $pengadaan->id,
                'vendor_id'    => $product->vendor_id,
            ],
            [
                'status' => 'pending',
                'harga'  => $harga
            ]
        );

        // 6. Simpan item penawaran
        PenawaranItem::create([
            'penawaran_id' => $penawaran->id,
            'product_id'   => $product->id,
            'qty'          => $qty,
            'harga'        => $product->price,        // harga satuan
            'total'        => $harga,                 // subtotal
        ]);

        return redirect()
            ->route('admin.penawaran.show', $pengadaan->id)
            ->with('success', 'Produk berhasil dipilih dari katalog');
    }



    /**
     * DETAIL VENDOR
     */
    public function vendorDetail($vendorId)
    {
        $vendor = User::with('vendorProfile')->findOrFail($vendorId);

        $products = Product::where('vendor_id', $vendorId)
            ->where('status', 'verified')
            ->get();

        return view('admin.katalog.vendor-detail', compact('vendor', 'products'));
    }

    public function vendorShow($id)
    {
        $vendor = User::with('vendorProfile')->findOrFail($id);

        $products = Product::where('vendor_id', $id)
            ->where('status', 'verified')
            ->get();

        return view('admin.katalog.show', compact('vendor', 'products'));
    }
}
