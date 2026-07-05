<x-app-layout>
    <x-slot name="title">Detail Transaksi</x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-eye"></i>
                        Detail Transaksi - {{ $transaksi->kode_transaksi }}
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- WARNING: Terlambat (Tugas 3 - Reminder) --}}
                    @if($transaksi->status == 'Dipinjam' && $transaksi->terlambat > 0)
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-triangle-fill"></i> Peringatan Keterlambatan!
                            </h5>
                            <p class="mb-1">
                                Buku ini sudah <strong>terlambat {{ $transaksi->terlambat }} hari</strong> dari batas
                                pengembalian
                                (<strong>{{ $transaksi->tanggal_kembali->format('d M Y') }}</strong>).
                            </p>
                            <hr>
                            <p class="mb-0">
                                <i class="bi bi-cash-coin"></i> Estimasi denda saat ini:
                                <strong class="text-danger fs-5">Rp
                                    {{ number_format($transaksi->terlambat * 5000, 0, ',', '.') }}</strong>
                                <small class="text-muted">(Rp 5.000 x {{ $transaksi->terlambat }} hari)</small>
                            </p>
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Kode Transaksi</th>
                            <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                        </tr>
                        <tr>
                            <th>Anggota</th>
                            <td>{{ $transaksi->anggota->nama }} ({{ $transaksi->anggota->kode_anggota }})</td>
                        </tr>
                        <tr>
                            <th>Buku</th>
                            <td>{{ $transaksi->buku->judul }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Harus Kembali</th>
                            <td>
                                {{ $transaksi->tanggal_kembali->format('d M Y') }}
                                @if($transaksi->status == 'Dipinjam' && now() > $transaksi->tanggal_kembali)
                                    <span class="badge bg-danger ms-2">
                                        <i class="bi bi-clock"></i> Sudah Lewat!
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Dikembalikan</th>
                            <td>
                                @if($transaksi->tanggal_dikembalikan)
                                    {{ $transaksi->tanggal_dikembalikan->format('d M Y') }}
                                @else
                                    <span class="text-muted">Belum dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($transaksi->status == 'Dipinjam')
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @if($transaksi->terlambat > 0)
                                        <span class="badge bg-danger ms-1">
                                            Terlambat {{ $transaksi->terlambat }} hari
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-success">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Durasi Peminjaman</th>
                            <td>{{ $transaksi->durasi_peminjaman }} hari</td>
                        </tr>
                        <tr>
                            <th>Keterlambatan</th>
                            <td>
                                @if($transaksi->terlambat > 0)
                                    <span class="text-danger fw-bold">{{ $transaksi->terlambat }} hari</span>
                                @else
                                    <span class="text-success">Tidak terlambat</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Denda</th>
                            <td>
                                @if($transaksi->status == 'Dikembalikan' && $transaksi->denda > 0)
                                    <span class="text-danger fw-bold fs-5">
                                        Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                                    </span>
                                    <small class="text-muted d-block">Denda keterlambatan yang harus dibayar</small>
                                @elseif($transaksi->status == 'Dipinjam' && $transaksi->terlambat > 0)
                                    <span class="text-warning fw-bold fs-5">
                                        Rp {{ number_format($transaksi->terlambat * 5000, 0, ',', '.') }}
                                    </span>
                                    <small class="text-muted d-block">(Estimasi — denda dihitung saat pengembalian)</small>
                                @else
                                    <span class="text-success">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $transaksi->keterangan ?? '-' }}</td>
                        </tr>
                    </table>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>

                        @if($transaksi->status === 'Dipinjam')
                            <div>
                                <button type="button" class="btn btn-success" id="btn-kembalikan">
                                    <i class="bi bi-arrow-return-left"></i> Kembalikan Buku
                                </button>
                                <form id="form-kembalikan" action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('PUT')
                                </form>
                            </div>
                        @else
                            @if($transaksi->tanggal_dikembalikan <= $transaksi->tanggal_kembali)
                                <div class="alert alert-success mb-0 py-2">
                                    <i class="bi bi-check-circle"></i> Dikembalikan tepat waktu pada
                                    {{ $transaksi->tanggal_dikembalikan->format('d M Y') }}
                                </div>
                            @else
                                <div class="alert alert-warning mb-0 py-2">
                                    <i class="bi bi-exclamation-triangle"></i> Terlambat dikembalikan!
                                    Denda: Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <x-slot name="scripts">
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            document.getElementById('btn-kembalikan')?.addEventListener('click', function() {
                                Swal.fire({
                                    title: 'Konfirmasi Pengembalian',
                                    text: 'Apakah Anda yakin ingin mengembalikan buku ini?',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#198754',
                                    confirmButtonText: 'Ya, Kembalikan!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('form-kembalikan').submit();
                                    }
                                });
                            });
                        </script>
                    </x-slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>