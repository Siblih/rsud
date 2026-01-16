<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class VendorProductController extends Controller
{
    // ================= LIST =================
    public function index(Request $request)
    {
        $products = $request->user()
            ->products()
            ->latest()
            ->get()
            ->map(function ($p) {
                $p->photo_url = !empty($p->photos)
                    ? asset('storage/' . $p->photos[0])
                    : null;
                return $p;
            });

        return response()->json([
            'data' => $products
        ]);
    }

    // ================= STORE =================
   public function store(Request $request)
{
    // 🔥 VALIDASI RINGAN (OPSIONAL TAPI DISARANKAN)
    $request->validate([
        'name' => 'required',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $product = $request->user()->products()->create(
        $request->except('images')
    );

    // 🔥 SIMPAN FOTO
    if ($request->hasFile('images')) {
        $paths = [];

        foreach ($request->file('images') as $img) {
            $paths[] = $img->store('products', 'public');
        }

        $product->photos = $paths;
        $product->save();
    }

    return response()->json([
        'success' => true,
        'data' => $product
    ]);
}

    // ================= DETAIL =================
    public function show(Request $request, $id)
    {
        $product = $request->user()
            ->products()
            ->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'data' => $product
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $product = $request->user()->products()->findOrFail($id);

        $product->update(
            $request->except('images')
        );

        // 🔥 GANTI FOTO JIKA ADA
        if ($request->hasFile('images')) {
            if (!empty($product->photos)) {
                foreach ($product->photos as $old) {
                    Storage::disk('public')->delete($old);
                }
            }

            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('products', 'public');
            }

            $product->photos = $paths;
            $product->save();
        }

        return response()->json([
            'message' => 'Produk berhasil diperbarui'
        ]);
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $product = auth()->user()->products()->findOrFail($id);

        if (!empty($product->photos)) {
            foreach ($product->photos as $p) {
                Storage::disk('public')->delete($p);
            }
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk dihapus'
        ]);
    }
}
