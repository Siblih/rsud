<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorPurchaseOrderController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        $poList = PurchaseOrder::where('vendor_id', $vendorId)->with('kontrak')->latest()->get();
        return view('vendor.po.index', compact('poList'));
    }

    public function show($id)
    {
        $po = PurchaseOrder::with('items','kontrak')->findOrFail($id);
        // security: ensure vendor owns it
        if ($po->vendor_id !== Auth::id()) abort(403);
        return view('vendor.po.show', compact('po'));
    }

    // sign endpoint: receives base64 image from client
    public function sign(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        if ($po->vendor_id !== Auth::id()) abort(403);

        $request->validate([
            'signature' => 'required|string' // data:image/png;base64,....
        ]);

        $data = $request->input('signature');
        // strip metadata
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // png, jpg, etc
            $data = base64_decode($data);
            if ($data === false) {
                return response()->json(['error'=>'Invalid signature data'], 422);
            }
        } else {
            return response()->json(['error'=>'Invalid signature format'], 422);
        }

        $filename = "signatures/po-{$po->id}-vendor-".Auth::id().".png";
        Storage::disk('public')->put($filename, $data);

        $po->vendor_signature_path = $filename;
        $po->signed_by_vendor = Auth::id();
        $po->signed_at = now();
        $po->status = 'dikirim'; // vendor has signed & will send back
        $po->save();

        // append revision history
        $history = $po->revision_history ? json_decode($po->revision_history, true) : [];
        $history[] = [
            'user_id' => Auth::id(),
            'action' => 'vendor_signed',
            'timestamp' => now()->toDateTimeString(),
            'note' => 'Vendor signed PO'
        ];
        $po->revision_history = json_encode($history);
        $po->save();

        return response()->json(['success'=>true,'message'=>'Signed saved']);
    }
}
