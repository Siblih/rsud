<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use Illuminate\Http\Request;

class KontrakController extends Controller
{
    public function index(Request $request)
    {
        // Filter status
        $status = $request->status;

        $query = Kontrak::with('pengadaan');

        if ($status) {
            $query->where('status', $status);
        }

        $kontraks = $query->latest()->get();

        return view('admin.kontrak.index', compact('kontraks', 'status'));
    }

    public function show($id)
    {
        $kontrak = Kontrak::with('pengadaan')->findOrFail($id);
        return view('admin.kontrak.show', compact('kontrak'));
    }
}


