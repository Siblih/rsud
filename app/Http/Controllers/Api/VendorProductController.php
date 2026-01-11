<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;


class VendorProductController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // dari sanctum token

        $products = $user->products()
            ->select(
                'id',
                'name',
                'description',
                'price',
                'unit',
                'status',
                'photos'
            )
            ->latest()
            ->get()
            ->map(function ($p) {
                $p->photo_url = (!empty($p->photos) && isset($p->photos[0]))
                    ? asset('storage/' . $p->photos[0])
                    : null;
                return $p;
            });

        return response()->json([
            'data' => $products
        ]);
    }
    public function store(Request $request)
    {
        $product = $request->user()->products()->create(
            $request->only('name','description','price','unit')
        );

        return response()->json(['message'=>'Produk ditambahkan']);
    }

  public function show(Request $request, $id)
{
    $product = $request->user()
        ->products()
        ->where('id', $id)
        ->first();

    if (!$product) {
        return response()->json([
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'data' => $product
    ]);
}



    public function update(Request $request, $id)
{
    $product = auth()->user()->products()->findOrFail($id);

    $product->update([
        'tipe_produk' => $request->tipe_produk,
        'kategori' => $request->kategori,
        'name' => $request->name,
        'sku' => $request->sku,
        'description' => $request->description,
        'price' => $request->price,
        'unit' => $request->unit,
        'stock' => $request->stock,
        'tkdn' => $request->tkdn,
        'izin_edar' => $request->izin_edar,
        'lead_time_days' => $request->lead_time_days,

        'is_dalam_negeri' => $request->is_dalam_negeri,
        'is_umk' => $request->is_umk,
        'is_konsolidasi' => $request->is_konsolidasi,
        'is_tkdn_sertifikat' => $request->is_tkdn_sertifikat,

        'izin_bpom' => $request->izin_bpom,
        'sertifikat_cpob' => $request->sertifikat_cpob,

        'no_akd' => $request->no_akd,
        'no_akl' => $request->no_akl,
        'no_pkrt' => $request->no_pkrt,

        'jenis_jasa' => $request->jenis_jasa,
        'jenis_digital' => $request->jenis_digital,
    ]);

    return response()->json([
        'message' => 'Produk berhasil diperbarui'
    ]);
}


    public function destroy($id)
    {
        auth()->user()->products()->findOrFail($id)->delete();
        return response()->json(['message'=>'Produk dihapus']);
    }

}
