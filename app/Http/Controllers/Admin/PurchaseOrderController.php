<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PoItem;
use App\Models\Kontrak;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    // List PO
    public function index()
    {
        $poList = PurchaseOrder::with(['vendor','kontrak'])->latest()->get();
        return view('admin.po.index', compact('poList'));
    }

    // Show PO
    public function show($id)
    {
        $po = PurchaseOrder::with(['items','vendor','kontrak'])->findOrFail($id);
        return view('admin.po.show', compact('po'));
    }

    // Create PO
    public function create($kontrak_id)
{
    $kontrak = Kontrak::findOrFail($kontrak_id);

    if ($kontrak->status != 'aktif') {
        return redirect()->back()->withErrors('PO hanya bisa dibuat dari kontrak aktif.');
    }

    $nomorPO = 'PO-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);
    return view('admin.po.create', compact('kontrak','nomorPO'));
}


    // Store PO + items + generate PDF otomatis
    // Store PO + items + generate PDF
public function store(Request $request, $kontrak_id)
{
    $kontrak = Kontrak::findOrFail($kontrak_id);

    $request->validate([
        'tanggal_po' => 'required|date',
    ]);

    $nomorPO = 'PO-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

    $po = PurchaseOrder::create([
        'nomor_po'   => $nomorPO,
        'kontrak_id' => $kontrak->id,
        'vendor_id'  => $kontrak->vendor_id,
        'tanggal_po' => $request->tanggal_po,
        'status'     => 'draft', // ⬅️ penting
        'total'      => 0,
    ]);

    return redirect()
        ->route('admin.po.edit', $po->id)
        ->with('success', 'PO berhasil dibuat, silakan isi item');
}



    // Edit PO
    public function edit($id)
    {
        $po = PurchaseOrder::with('items','kontrak','vendor')->findOrFail($id);
        return view('admin.po.edit', compact('po'));
    }

    // Update PO + items
   public function update(Request $request, $id)
{
    $po = PurchaseOrder::with('items')->findOrFail($id);

    $request->validate([
        'tanggal_po' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.nama_item' => 'required|string',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.harga' => 'required|numeric|min:0',
        'items.*.spesifikasi' => 'nullable|string',
    'items.*.satuan' => 'nullable|string',
    ]);

    $po->update([
        'tanggal_po' => $request->tanggal_po,
    ]);

    $total = 0;

    foreach ($request->items as $item) {
        if (!empty($item['id'])) {
            $poItem = PoItem::find($item['id']);
            $poItem->update([
                'nama_item' => $item['nama_item'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'total' => $item['qty'] * $item['harga'],
                'spesifikasi' => $item['spesifikasi'] ?? null,
    'satuan'      => $item['satuan'] ?? null,

            ]);
        } else {
            $poItem = PoItem::create([
                'po_id' => $po->id,
                'nama_item' => $item['nama_item'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'total' => $item['qty'] * $item['harga'],
                'spesifikasi' => $item['spesifikasi'] ?? null,
'satuan' => $item['satuan'] ?? null,

            ]);
        }

        $total += $poItem->total;
    }

    $po->update(['total' => $total]);
    // HAPUS PDF LAMA JIKA ADA
if ($po->file_po && Storage::disk('public')->exists($po->file_po)) {
    Storage::disk('public')->delete($po->file_po);
}

// reset status pdf
$po->update([
    'file_po' => null,
    'status' => 'draft'
]);


    return redirect()
        ->route('admin.po.show', $po->id)
        ->with('success', 'PO berhasil diperbarui');
}


    // Generate draft PDF (optional)
    public function generatePdf($id)
{
    $po = PurchaseOrder::with(['items','vendor','kontrak'])->findOrFail($id);

    if ($po->items->count() == 0) {
        return back()->withErrors('Item PO masih kosong');
    }

    $pdf = Pdf::loadView('admin.po.pdf', compact('po'))
        ->setPaper('A4', 'portrait');

    $fileName = 'po/PO-' . $po->nomor_po . '.pdf';
    Storage::disk('public')->put($fileName, $pdf->output());

    $po->update([
        'file_po' => $fileName,
        'status' => 'approved'
    ]);

    return redirect()
        ->route('admin.po.show', $po->id)
        ->with('success', 'PDF PO berhasil digenerate');
}


    // Generate final signed PDF
    public function generateSignedPdf(Request $request, $id)
    {
        $po = PurchaseOrder::with(['kontrak','vendor','items'])->findOrFail($id);

        if(!$po->vendor_signature_path || !Storage::disk('public')->exists($po->vendor_signature_path)){
            return redirect()->back()->withErrors('Vendor signature not found');
        }

        $sigPath = storage_path('app/public/'.$po->vendor_signature_path);
        $sigData = file_get_contents($sigPath);
        $sigBase64 = 'data:image/png;base64,'.base64_encode($sigData);

        $html = view('pdf.po_signed', compact('po','sigBase64'))->render();

        $signedFilename = "po/signed/PO-{$po->id}-{$po->nomor_po}-signed.pdf";
        Storage::disk('public')->put($signedFilename, $pdf->output());

        $po->file_pdf = $signedFilename;
        $po->status = 'signed';
        $po->save();

        $kontrak = $po->kontrak;
        $kontrak->po_signed = $signedFilename;
        $kontrak->save();

        return redirect()->route('admin.po.show', $po->id)->with('success','Signed PDF generated');
    }
}
