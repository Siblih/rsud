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
        $nomorPO = 'PO-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);
        return view('admin.po.create', compact('kontrak','nomorPO'));
    }

    // Store PO + items + generate PDF otomatis
    // Store PO + items + generate PDF
public function store(Request $request, $kontrak_id)
{
    $kontrak = Kontrak::findOrFail($kontrak_id);

    // Validasi
    $request->validate([
        'tanggal_po' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.nama_item' => 'required|string',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.harga' => 'required|numeric|min:0',
    ]);

    // Nomor PO otomatis
    $nomorPO = 'PO-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

    // Simpan PO
    $po = PurchaseOrder::create([
        'nomor_po' => $nomorPO,
        'kontrak_id' => $kontrak->id,
        'vendor_id' => $kontrak->vendor_id,
        'tanggal_po' => $request->tanggal_po,
        'status' => 'pending',
    ]);

    // Simpan items
    $total = 0;
    foreach ($request->items as $item) {
        $poItem = PoItem::create([
            'po_id' => $po->id,
            'nama_item' => $item['nama_item'],
            'spesifikasi' => $item['spesifikasi'] ?? null,
            'qty' => (int)$item['qty'],
            'harga' => (float)$item['harga'],
            'total' => (int)$item['qty'] * (float)$item['harga'],
        ]);
        $total += $poItem->total;
    }

    // Update total PO
    $po->total = $total;
    $po->save();

    // 🔹 Load relasi sebelum generate PDF
    $po = PurchaseOrder::with(['items','vendor','kontrak'])->find($po->id);

    // Generate PDF
    $pdf = PDF::loadView('pdf.po', compact('po'))->setPaper('a4', 'portrait');
    $fileName = 'po/PO-'.$po->nomor_po.'.pdf';
    Storage::disk('public')->put($fileName, $pdf->output());

    $po->file_pdf = $fileName;
    $po->save();

    return redirect()->route('admin.po.show', $po->id)
                     ->with('success', 'PO berhasil dibuat dan PDF otomatis digenerate.');
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
        ]);

        $po->tanggal_po = $request->tanggal_po;
        $po->save();

        $total = 0;
        foreach($request->items as $item){
            if(!empty($item['id'])){
                $pitem = PoItem::find($item['id']);
                if($pitem){
                    $pitem->nama_item = $item['nama_item'];
                    $pitem->spesifikasi = $item['spesifikasi'] ?? null;
                    $pitem->qty = (int)$item['qty'];
                    $pitem->harga = (float)$item['harga'];
                    $pitem->total = $pitem->qty * $pitem->harga;
                    $pitem->save();
                    $total += $pitem->total;
                }
            } else {
                $pitem = PoItem::create([
                    'po_id' => $po->id,
                    'nama_item' => $item['nama_item'],
                    'spesifikasi' => $item['spesifikasi'] ?? null,
                    'qty' => (int)$item['qty'],
                    'harga' => (float)$item['harga'],
                    'total' => (int)$item['qty'] * (float)$item['harga'],
                ]);
                $total += $pitem->total;
            }
        }

        $po->total = $total;
        $po->save();

        // Update revision history
        $history = $po->revision_history ? json_decode($po->revision_history,true) : [];
        $history[] = [
            'user_id' => Auth::id(),
            'action' => 'updated',
            'timestamp' => now()->toDateTimeString(),
            'note' => 'Admin updated PO header/items'
        ];
        $po->revision_history = json_encode($history);
        $po->save();

        return redirect()->route('admin.po.show', $po->id)->with('success','PO updated');
    }

    // Generate draft PDF (optional)
    public function generatePdf($id)
    {
        $po = PurchaseOrder::with(['kontrak','vendor','items'])->findOrFail($id);
        $pdf = PDF::loadView('pdf.po', compact('po'))->setPaper('a4','portrait');

        $filename = "po/PO-{$po->id}-{$po->nomor_po}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        $po->file_pdf = $filename;
        $po->save();

        return redirect()->route('admin.po.show', $po->id)->with('success','PDF generated');
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
