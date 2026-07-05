<x-app-layout>
    <x-slot name="title">{{ $anggota->nama }}</x-slot>
    <div class="row">
        <div class="col-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
                    <li class="breadcrumb-item active">{{ $anggota->nama }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person"></i>
                        Detail Anggota
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if ($anggota->jenis_kelamin == 'Laki-laki')
                            <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                        @else
                            <i class="bi bi-person-circle text-danger" style="font-size: 5rem;"></i>
                        @endif
                        <h3 class="mt-2">{{ $anggota->nama }}</h3>
                        @if ($anggota->status == 'Aktif')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Anggota Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle"></i> Nonaktif
                            </span>
                        @endif
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <td width="200" class="fw-bold">
                                <i class="bi bi-upc text-success"></i> Kode Anggota
                            </td>
                            <td>: <code>{{ $anggota->kode_anggota }}</code></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="bi bi-envelope text-success"></i> Email
                            </td>
                            <td>: {{ $anggota->email }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="bi bi-telephone text-success"></i> Telepon
                            </td>
                            <td>: {{ $anggota->telepon }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="bi bi-geo-alt text-success"></i> Alamat
                            </td>
                            <td>: {{ $anggota->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="bi bi-calendar text-success"></i> Tanggal Lahir
                            </td>
                            <td>: {{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d F Y') }} ({{ $anggota->umur }} tahun)</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="bi bi-gender-ambiguous text-success"></i> Jenis Kelamin
                            </td>
                            <td>: {{ $anggota->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="bi bi-briefcase text-success"></i> Pekerjaan
                            </td>
                            <td>: {{ $anggota->pekerjaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <i class="bi bi-calendar-check text-success"></i> Tanggal Daftar
                            </td>
                            <td>: {{ \Carbon\Carbon::parse($anggota->tanggal_daftar)->format('d F Y') }} ({{ $anggota->lama_anggota }} hari)
                            </td>
                        </tr>
                    </table>

                    <hr>
                    <div class="row text-muted small">
                        <div class="col-md-6">
                            <i class="bi bi-clock"></i>
                            Ditambahkan: {{ $anggota->created_at->format('d M Y H:i') }}
                        </div>
                        <div class="col-md-6 text-end">
                            <i class="bi bi-clock-history"></i>
                            Terakhir Update: {{ $anggota->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-gear"></i> Aksi
                    </h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Anggota
                    </a>
                    <a href="{{ route('anggota.index') }}" class="btn btn-outline-success">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <hr>
                    <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Hapus Anggota
                        </button>
                    </form>
                </div>
            </div>

            {{-- Statistik Peminjaman --}}
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Statistik Peminjaman
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="mb-0 text-primary">{{ $statsAnggota['total_pinjam'] }}</h4>
                                <small class="text-muted">Total Pinjam</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="mb-0 text-warning">{{ $statsAnggota['sedang_pinjam'] }}</h4>
                                <small class="text-muted">Sedang Dipinjam</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="mb-0 text-success">{{ $statsAnggota['dikembalikan'] }}</h4>
                                <small class="text-muted">Dikembalikan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="mb-0 text-danger">Rp {{ number_format($statsAnggota['total_denda'], 0, ',', '.') }}</h4>
                                <small class="text-muted">Total Denda</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Peminjaman --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Riwayat Peminjaman
                    </h5>
                    {{-- Filter Status --}}
                    <form action="{{ route('anggota.show', $anggota->id) }}" method="GET" class="d-flex gap-2">
                        <select name="status_filter" class="form-select form-select-sm" style="width: 180px;" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ request('status_filter') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ request('status_filter') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    @if($transaksis->count() > 0)
                        {{-- Timeline View --}}
                        <div class="timeline">
                            @foreach($transaksis as $trx)
                                <div class="d-flex mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="me-3 text-center" style="min-width: 60px;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto
                                            {{ $trx->status == 'Dipinjam' ? 'bg-warning' : 'bg-success' }}"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi {{ $trx->status == 'Dipinjam' ? 'bi-journal-arrow-up' : 'bi-journal-check' }} text-white"></i>
                                        </div>
                                        <small class="text-muted">{{ $trx->tanggal_pinjam->format('d M') }}</small>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('transaksi.show', $trx->id) }}" class="text-decoration-none">
                                                        {{ $trx->buku->judul }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    <code>{{ $trx->kode_transaksi }}</code> •
                                                    Pinjam: {{ $trx->tanggal_pinjam->format('d M Y') }} •
                                                    Kembali: {{ $trx->tanggal_kembali->format('d M Y') }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                @if($trx->status == 'Dipinjam')
                                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                                    @if($trx->terlambat > 0)
                                                        <br><span class="badge bg-danger mt-1">
                                                            <i class="bi bi-exclamation-triangle"></i>
                                                            Terlambat {{ $trx->terlambat }} hari
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($trx->status == 'Dikembalikan')
                                            <div class="mt-1">
                                                <small>
                                                    Dikembalikan: {{ $trx->tanggal_dikembalikan ? $trx->tanggal_dikembalikan->format('d M Y') : '-' }}
                                                    @if($trx->denda > 0)
                                                        • <span class="text-danger fw-bold">Denda: Rp {{ number_format($trx->denda, 0, ',', '.') }}</span>
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">Belum ada riwayat peminjaman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>