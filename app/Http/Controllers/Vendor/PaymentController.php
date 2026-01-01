<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Kontrak $kontrak)
    {
        // pastikan kontrak milik vendor yg login
        if ($kontrak->vendor_id !== Auth::id()) {
            abort(403);
        }

        return view('vendor.payment.create', compact('kontrak'));
    }

    public function store(Request $request, Kontrak $kontrak)
    {
        if ($kontrak->vendor_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'invoice'           => 'required|file|mimes:pdf,jpg,png|max:5120',
            'bast'              => 'required|file|mimes:pdf,jpg,png|max:5120',
            'faktur_pajak'      => 'required|file|mimes:pdf,jpg,png|max:5120',
            'surat_permohonan'  => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $payment = new Payment();
        $payment->kontrak_id = $kontrak->id;
        $payment->nominal = $kontrak->nilai_kontrak;
        $payment->status = 'submitted';
        $payment->submitted_at = now();

        foreach ($data as $key => $file) {
            $payment->$key = $file->store('payments', 'public');
        }

        $payment->save();

        return redirect()
            ->route('vendor.kontrak.show', $kontrak->id)
            ->with('success', 'Pembayaran berhasil diajukan');
    }
}
