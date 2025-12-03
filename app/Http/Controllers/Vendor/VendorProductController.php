<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorProductController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        $products = Product::where('vendor_id', $vendorId)->latest()->paginate(12);

        return view('vendor.produk.index', compact('products'));
    }

    public function create()
    {
        return view('vendor.produk.create');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'tipe_produk' => 'required|in:barang,jasa,digital',

        // BARANG
        'kategori' => 'required_if:tipe_produk,barang|string|in:umum,obat,alkes',
        'is_dalam_negeri' => 'nullable',
        'is_umk' => 'nullable',
        'is_konsolidasi' => 'nullable',
        'is_tkdn_sertifikat' => 'nullable',

        // OBAT
        'izin_bpom' => 'nullable|string',
        'sertifikat_cpob' => 'nullable|mimes:pdf|max:5120',
        'surat_distributor' => 'nullable|mimes:pdf|max:5120',

        // ALKES
        'no_akl' => 'nullable|string',
        'no_akd' => 'nullable|string',
        'no_pkrt' => 'nullable|string',
        'dokumen_tkdn' => 'nullable|mimes:pdf|max:5120',
        'dokumen_garansi' => 'nullable|mimes:pdf|max:5120',
        'dokumen_uji_coba' => 'nullable|mimes:pdf|max:5120',

        // UMUM
        'surat_penunjukan' => 'nullable|mimes:pdf|max:5120',

        // JASA
        'jenis_jasa' => 'required_if:tipe_produk,jasa|string|nullable',

        // DIGITAL
        'jenis_digital' => 'required_if:tipe_produk,digital|string|nullable',

        // UMUM FIELD
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'unit' => 'nullable|string|max:50',
        'stock' => 'nullable|integer|min:0',
        'tkdn' => 'nullable|integer|min:0|max:100',
        'izin_edar' => 'nullable|string|max:200',
        'lead_time_days' => 'nullable|integer|min:0',
        'brochure' => 'nullable|mimes:pdf|max:5120',
        'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    ]);

    // Checkbox convert
    $data['is_dalam_negeri'] = $request->has('is_dalam_negeri') ? 1 : 0;
    $data['is_umk'] = $request->has('is_umk') ? 1 : 0;
    $data['is_konsolidasi'] = $request->has('is_konsolidasi') ? 1 : 0;
    $data['is_tkdn_sertifikat'] = $request->has('is_tkdn_sertifikat') ? 1 : 0;

    $data['vendor_id'] = Auth::id();
    $data['status'] = 'pending';

    // SKU
    $lastNumber = Product::where('vendor_id', Auth::id())->count() + 1;
    $data['sku'] = 'V' . Auth::id() . '-PRD-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

    // Upload dokumen generik
    foreach (['sertifikat_cpob','surat_distributor','dokumen_tkdn','dokumen_garansi','dokumen_uji_coba','surat_penunjukan'] as $f) {
        if ($request->hasFile($f)) {
            $data[$f] = $request->file($f)->store('products/docs', 'public');
        }
    }

    // Brosur
    if ($request->hasFile('brochure')) {
        $data['brochure'] = $request->file('brochure')->store('products/brochures', 'public');
    }

    // Photos multiple
    $photos = [];
    if ($request->hasFile('photos')) {
        foreach ($request->photos as $file) {
            $photos[] = $file->store('products/photos', 'public');
        }
        $data['photos'] = $photos;
    }

    Product::create($data);

    return redirect()->route('vendor.produk')->with('success', 'Produk berhasil ditambahkan');
}


    public function show($id)
    {
        $vendorId = Auth::id();
        $product = Product::where('vendor_id', $vendorId)->findOrFail($id);

        return view('vendor.produk.show', compact('product'));
    }

    public function edit($id)
    {
        $vendorId = Auth::id();
        $product = Product::where('vendor_id', $vendorId)->findOrFail($id);

        return view('vendor.produk.edit', compact('product'));
    }

    public function update(Request $request, $id)
{
    $product = Product::where('vendor_id', Auth::id())->findOrFail($id);

    $data = $request->validate([
        'tipe_produk' => 'required|in:barang,jasa,digital',

        // BARANG
        'kategori' => 'required_if:tipe_produk,barang|string|in:umum,obat,alkes',
        'is_dalam_negeri' => 'nullable',
        'is_umk' => 'nullable',
        'is_konsolidasi' => 'nullable',
        'is_tkdn_sertifikat' => 'nullable',

        // OBAT
        'izin_bpom' => 'nullable|string',
        'sertifikat_cpob' => 'nullable|mimes:pdf|max:5120',
        'surat_distributor' => 'nullable|mimes:pdf|max:5120',

        // ALKES
        'no_akl' => 'nullable|string',
        'no_akd' => 'nullable|string',
        'no_pkrt' => 'nullable|string',
        'dokumen_tkdn' => 'nullable|mimes:pdf|max:5120',
        'dokumen_garansi' => 'nullable|mimes:pdf|max:5120',
        'dokumen_uji_coba' => 'nullable|mimes:pdf|max:5120',

        // UMUM
        'surat_penunjukan' => 'nullable|mimes:pdf|max:5120',

        // JASA
        'jenis_jasa' => 'required_if:tipe_produk,jasa|string|nullable',

        // DIGITAL
        'jenis_digital' => 'required_if:tipe_produk,digital|string|nullable',

        // GLOBAL
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'unit' => 'nullable|string|max:50',
        'stock' => 'nullable|integer|min:0',
        'tkdn' => 'nullable|integer|min:0|max:100',
        'izin_edar' => 'nullable|string|max:200',
        'lead_time_days' => 'nullable|integer|min:0',
        'brochure' => 'nullable|mimes:pdf|max:5120',
        'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    ]);

    // Checkbox convert
    $data['is_dalam_negeri'] = $request->has('is_dalam_negeri') ? 1 : 0;
    $data['is_umk'] = $request->has('is_umk') ? 1 : 0;
    $data['is_konsolidasi'] = $request->has('is_konsolidasi') ? 1 : 0;
    $data['is_tkdn_sertifikat'] = $request->has('is_tkdn_sertifikat') ? 1 : 0;

    // Replace dokumen generic
    foreach (['sertifikat_cpob','surat_distributor','dokumen_tkdn','dokumen_garansi','dokumen_uji_coba','surat_penunjukan'] as $f) {
        if ($request->hasFile($f)) {
            if ($product->$f) Storage::disk('public')->delete($product->$f);
            $data[$f] = $request->file($f)->store('products/docs', 'public');
        }
    }

    // Replace brosur
    if ($request->hasFile('brochure')) {
        if ($product->brochure) Storage::disk('public')->delete($product->brochure);
        $data['brochure'] = $request->file('brochure')->store('products/brochures', 'public');
    }

    // Replace Photos
    if ($request->hasFile('photos')) {
        $photos = $product->photos ?? [];
        foreach ($request->photos as $file) {
            $photos[] = $file->store('products/photos', 'public');
        }
        $data['photos'] = $photos;
    }

    // reset status
    $data['status'] = 'pending';

    $product->update($data);

    return redirect()->route('vendor.produk')
        ->with('success', 'Produk berhasil diperbarui dan dikirim ke admin');
}


    public function destroy($id)
    {
        $vendorId = Auth::id();
        $product = Product::where('vendor_id', $vendorId)->findOrFail($id);

        // Hapus brosur
        if ($product->brochure) {
            Storage::disk('public')->delete($product->brochure);
        }

        // Hapus foto
        if (!empty($product->photos)) {
            foreach ($product->photos as $p) {
                Storage::disk('public')->delete($p);
            }
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
