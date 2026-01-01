@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white px-6 py-8">

    {{-- Card utama --}}
    <div class="max-w-6xl mx-auto bg-white/10 backdrop-blur-lg border border-white/20 
                rounded-2xl shadow-xl p-6">

        <h1 class="text-xl font-semibold mb-6 flex items-center gap-2">
            🏆 Manajemen Penawaran
        </h1>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-white/20">
                    <tr>
                        <th class="p-3 text-left border border-white/10">Nama Pengadaan</th>
                        <th class="p-3 text-center border border-white/10">Jumlah Penawaran</th>
                        <th class="p-3 text-center border border-white/10">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengadaans as $p)
                        <tr class="hover:bg-white/10 transition">
                            <td class="p-3 border border-white/10">
                                {{ $p->nama_pengadaan }}
                            </td>
                            <td class="p-3 text-center border border-white/10">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                             bg-blue-500/20 text-blue-200">
                                    {{ $p->penawarans_count }}
                                </span>
                            </td>
                            <td class="p-3 text-center border border-white/10">
                                <a href="{{ route('admin.penawaran.show', $p->id) }}"
                                   class="inline-block bg-blue-600/80 hover:bg-blue-700 
                                          text-white text-xs px-4 py-2 rounded-full 
                                          font-semibold shadow transition">
                                    Lihat Penawaran
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-blue-200 italic">
                                📭 Belum ada penawaran masuk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
