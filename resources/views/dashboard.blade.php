<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="container-fluid py-4">
        <h2 class="mb-4">Dashboard Perpustakaan</h2>

        {{-- Statistics Cards --}}
        <div class="row g-3 mb-4">
            @foreach([
                ['Total Buku', $stats['total_buku'], 'bi-book', 'primary'],
                ['Anggota Aktif', $stats['total_anggota'], 'bi-people', 'success'],
                ['Sedang Dipinjam', $stats['sedang_dipinjam'], 'bi-journal-arrow-up', 'info'],
                ['Terlambat', $stats['terlambat'], 'bi-exclamation-triangle', 'danger'],
                ['Transaksi Hari Ini', $stats['transaksi_hari_ini'], 'bi-calendar-check', 'warning'],
                ['Buku Tersedia', $stats['buku_tersedia'], 'bi-bookshelf', 'secondary'],
                ['Total Transaksi', $stats['total_transaksi'], 'bi-receipt', 'dark'],
                ['Denda Bulan Ini', 'Rp ' . number_format($stats['denda_bulan_ini'], 0, ',', '.'), 'bi-cash', 'danger'],
            ] as [$label, $value, $icon, $color])
            <div class="col-xl-3 col-md-6">
                <div class="card border-{{ $color }} h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi {{ $icon }} fs-1 text-{{ $color }} me-3"></i>
                        <div>
                            <h6 class="text-muted mb-1">{{ $label }}</h6>
                            <h4 class="mb-0">{{ $value }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Row 1: Line Chart + Donut Status --}}
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-graph-up"></i> Trend Peminjaman 6 Bulan Terakhir</div>
                    <div class="card-body">
                        <canvas id="chartTransaksi" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-pie-chart"></i> Status Transaksi</div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartStatus" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Pie Kategori + Bar Top 10 --}}
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-tags"></i> Distribusi Kategori Buku</div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartKategori" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header"><i class="bi bi-bar-chart"></i> Top 10 Buku Terpopuler</div>
                    <div class="card-body">
                        <canvas id="chartTop10" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="card">
            <div class="card-header">Transaksi Terbaru</div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th><th>Anggota</th><th>Buku</th>
                            <th>Tgl Pinjam</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransaksi as $trx)
                        <tr>
                            <td>{{ $trx->kode_transaksi }}</td>
                            <td>{{ $trx->anggota->nama }}</td>
                            <td>{{ $trx->buku->judul }}</td>
                            <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $trx->status === 'Dipinjam' ? 'warning' : 'success' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const chartColors = [
            '#0d6efd','#198754','#ffc107','#dc3545','#6f42c1',
            '#0dcaf0','#fd7e14','#20c997','#d63384','#6610f2'
        ];

        // 1. Line chart — Trend Peminjaman 6 bulan terakhir
        new Chart(document.getElementById('chartTransaksi'), {
            type: 'line',
            data: {
                labels: @json($chartData->pluck('bulan')),
                datasets: [
                    { label: 'Peminjaman', data: @json($chartData->pluck('pinjam')),
                      borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.1)', fill: true, tension: 0.3 },
                    { label: 'Pengembalian', data: @json($chartData->pluck('kembali')),
                      borderColor: '#198754', backgroundColor: 'rgba(25,135,84,0.1)', fill: true, tension: 0.3 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });

        // 2. Donut chart — Status Transaksi
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Dipinjam (Tepat Waktu)', 'Terlambat', 'Dikembalikan'],
                datasets: [{
                    data: [{{ $statusChart['dipinjam'] }}, {{ $statusChart['terlambat'] }}, {{ $statusChart['dikembalikan'] }}],
                    backgroundColor: ['#ffc107', '#dc3545', '#198754']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                cutout: '60%'
            }
        });

        // 3. Pie chart — Kategori Buku
        new Chart(document.getElementById('chartKategori'), {
            type: 'pie',
            data: {
                labels: @json($kategoriChart->pluck('kategori')),
                datasets: [{
                    data: @json($kategoriChart->pluck('total')),
                    backgroundColor: chartColors.slice(0, {{ $kategoriChart->count() }})
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // 4. Bar chart — Top 10 Buku Terpopuler
        new Chart(document.getElementById('chartTop10'), {
            type: 'bar',
            data: {
                labels: @json($bukuTop10->pluck('judul')->map(fn($j) => \Illuminate\Support\Str::limit($j, 20))),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($bukuTop10->pluck('transaksis_count')),
                    backgroundColor: chartColors.slice(0, {{ $bukuTop10->count() }})
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        </script>
    </x-slot>
</x-app-layout>