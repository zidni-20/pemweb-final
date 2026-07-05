<x-app-layout>
    <x-slot name="title">Laporan Transaksi</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="bi bi-file-earmark-bar-graph"></i>
            Laporan Transaksi
        </h1>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transaksi.laporan') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                    <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari"
                        value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                    <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
                        value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>
                            Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="anggota_id" class="form-label">Anggota</label>
                    <select class="form-select" id="anggota_id" name="anggota_id">
                        <option value="">Semua Anggota</option>
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('transaksi.laporan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h2 class="text-primary">{{ $totalTransaksi }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Denda</h6>
                    <h2 class="text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Export PDF Button --}}
    <div class="mb-3">
        <a href="{{ route('transaksi.exportPdf', request()->query()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>

    {{-- Tabel Hasil --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Tgl Dikembalikan</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                                <td>{{ $transaksi->anggota->nama }}</td>
                                <td>{{ $transaksi->buku->judul }}</td>
                                <td>{{ $transaksi->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $transaksi->tanggal_kembali->format('d/m/Y') }}</td>
                                <td>
                                    {{ $transaksi->tanggal_dikembalikan ? $transaksi->tanggal_dikembalikan->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    @if($transaksi->status == 'Dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @if(now() > $transaksi->tanggal_kembali)
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaksi->denda > 0)
                                        <span class="text-danger fw-bold">Rp
                                            {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                                    @else
                                        Rp 0
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data transaksi yang sesuai filter
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>