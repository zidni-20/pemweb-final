<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Http\Requests\StoreAnggotaRequest;
use App\Http\Requests\UpdateAnggotaRequest;
use App\Exports\AnggotaExport;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaController extends Controller
{
    /**
     * Generate kode anggota otomatis.
     */
    private function generateKodeAnggota()
    {
        $tahun = date('Y');
        $lastAnggota = Anggota::whereYear('created_at', $tahun)
                              ->orderBy('kode_anggota', 'desc')
                              ->first();

        if ($lastAnggota) {
            $lastNumber = intval(substr($lastAnggota->kode_anggota, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'AGT-' . $tahun . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anggotas = Anggota::latest()->get();

        // Statistik
        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'Aktif')->count();
        $anggotaNonaktif = Anggota::where('status', 'Nonaktif')->count();

        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kodeAnggota = $this->generateKodeAnggota();
        return view('anggota.create', compact('kodeAnggota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnggotaRequest $request)
    {
        try {
            // Create anggota baru dengan validated data
            Anggota::create($request->validated());

            // Redirect dengan success message
            return redirect()->route('anggota.index')
                             ->with('success', 'Anggota berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menambahkan anggota: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $anggota = Anggota::findOrFail($id);

        // Query transaksi anggota
        $transaksiQuery = \App\Models\Transaksi::with('buku')
                            ->where('anggota_id', $id);

        // Filter by status jika ada
        if ($request->filled('status_filter')) {
            $transaksiQuery->where('status', $request->status_filter);
        }

        $transaksis = $transaksiQuery->latest('tanggal_pinjam')->get();

        // Statistik peminjaman
        $allTransaksi = \App\Models\Transaksi::where('anggota_id', $id)->get();
        $statsAnggota = [
            'total_pinjam'   => $allTransaksi->count(),
            'sedang_pinjam'  => $allTransaksi->where('status', 'Dipinjam')->count(),
            'dikembalikan'   => $allTransaksi->where('status', 'Dikembalikan')->count(),
            'total_denda'    => $allTransaksi->sum('denda'),
        ];

        return view('anggota.show', compact('anggota', 'transaksis', 'statsAnggota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnggotaRequest $request, string $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);

            // Update anggota dengan validated data
            $anggota->update($request->validated());

            // Redirect dengan success message
            return redirect()->route('anggota.show', $anggota->id)
                             ->with('success', 'Data anggota berhasil diupdate!');

        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal mengupdate anggota: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $namaAnggota = $anggota->nama;

            // Delete anggota
            $anggota->delete();

            // Redirect dengan success message
            return redirect()->route('anggota.index')
                             ->with('success', "Anggota '{$namaAnggota}' berhasil dihapus!");

        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                             ->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }

    /**
     * Export data anggota ke Excel.
     */
    public function export()
    {
        return Excel::download(new AnggotaExport, 'anggota_' . date('Y-m-d_His') . '.xlsx');
    }

    /**
     * Search dan filter advanced anggota.
     */
    public function search(Request $request)
    {
        $query = Anggota::query();

        if ($request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('telepon', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->pekerjaan) {
            $query->where('pekerjaan', $request->pekerjaan);
        }

        // Filter range umur
        if ($request->filled('umur_min')) {
            $maxDate = now()->subYears((int) $request->umur_min)->format('Y-m-d');
            $query->where('tanggal_lahir', '<=', $maxDate);
        }
        if ($request->filled('umur_max')) {
            $minDate = now()->subYears((int) $request->umur_max + 1)->addDay()->format('Y-m-d');
            $query->where('tanggal_lahir', '>=', $minDate);
        }

        $anggotas = $query->latest()->get();

        // Statistics
        $totalAnggota = $anggotas->count();
        $anggotaAktif = $anggotas->where('status', 'Aktif')->count();
        $anggotaNonaktif = $anggotas->where('status', 'Nonaktif')->count();

        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif'
        ));
    }
}
