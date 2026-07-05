<x-app-layout>
    <x-slot name="title">Daftar Transaksi</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="bi bi-arrow-left-right"></i>
            Daftar Transaksi Peminjaman
        </h1>
        <div>
            <a href="{{ route('transaksi.laporan') }}" class="btn btn-info text-white">
                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
            </a>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Pinjam Buku
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Transaksi</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('transaksi.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Anggota</label>
                        <select name="anggota_id" class="form-select form-select-sm">
                            <option value="">Semua Anggota</option>
                            @foreach($anggotas as $a)
                                <option value="{{ $a->id }}" {{ request('anggota_id') == $a->id ? 'selected' : '' }}>
                                    {{ $a->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h2>{{ $transaksis->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Sedang Dipinjam</h6>
                    <h2>{{ $transaksis->where('status', 'Dipinjam')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Sudah Dikembalikan</h6>
                    <h2>{{ $transaksis->where('status', 'Dikembalikan')->count() }}</h2>
                </div>
            </div>
        </div>
        {{-- Card Terlambat --}}
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-muted">Terlambat</h6>
                    <h2 class="text-danger">
                        {{ $transaksis->where('status', 'Dipinjam')->filter(function ($t) {
    return now() > $t->tanggal_kembali;
})->count() }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                                <td>{{ $transaksi->anggota->nama }}</td>
                                <td>{{ $transaksi->buku->judul }}</td>
                                <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                                <td>{{ $transaksi->tanggal_kembali->format('d M Y') }}</td>
                                <td>
                                    @if($transaksi->status == 'Dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                        {{-- Badge Terlambat --}}
                                        @if($transaksi->terlambat > 0)
                                            <br>
                                            <span class="badge bg-danger mt-1">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                Terlambat {{ $transaksi->terlambat }} hari
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                        @if($transaksi->denda > 0)
                                            <br>
                                            <small class="text-danger">
                                                Denda: Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                                            </small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                        class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>