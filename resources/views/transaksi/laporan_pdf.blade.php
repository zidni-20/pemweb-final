<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0;
            color: #666;
        }

        .filters {
            background: #f5f5f5;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 10px;
        }

        .filters span {
            margin-right: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
            font-size: 10px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge-dipinjam {
            background: #ffc107;
            color: #000;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .badge-dikembalikan {
            background: #198754;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .badge-terlambat {
            background: #dc3545;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .summary {
            margin-top: 15px;
            border-top: 2px solid #333;
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI PERPUSTAKAAN</h1>
        <p>Sistem Manajemen Perpustakaan</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="filters">
        <strong>Filter:</strong>
        <span>Periode: {{ $filters['tanggal_dari'] ?? 'Semua' }} s/d {{ $filters['tanggal_sampai'] ?? 'Semua' }}</span>
        <span>Status: {{ $filters['status'] ?? 'Semua' }}</span>
        <span>Anggota: {{ $filters['anggota'] ?? 'Semua' }}</span>
    </div>

    <table>
        <thead>
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
                    <td>{{ $transaksi->kode_transaksi }}</td>
                    <td>{{ $transaksi->anggota->nama }}</td>
                    <td>{{ $transaksi->buku->judul }}</td>
                    <td>{{ $transaksi->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $transaksi->tanggal_kembali->format('d/m/Y') }}</td>
                    <td>{{ $transaksi->tanggal_dikembalikan ? $transaksi->tanggal_dikembalikan->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        @if($transaksi->status == 'Dipinjam')
                            <span class="badge-dipinjam">Dipinjam</span>
                            @if(now() > $transaksi->tanggal_kembali)
                                <span class="badge-terlambat">Terlambat</span>
                            @endif
                        @else
                            <span class="badge-dikembalikan">Dikembalikan</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($transaksi->denda > 0)
                            <span class="text-danger fw-bold">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                        @else
                            Rp 0
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table style="width: 300px; margin-left: auto; border: none;">
            <tr style="border: none;">
                <td style="border: none; font-weight: bold;">Total Transaksi</td>
                <td style="border: none; text-align: right;">{{ $totalTransaksi }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; font-weight: bold;">Total Denda</td>
                <td style="border: none; text-align: right; color: #dc3545; font-weight: bold;">
                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Sistem Perpustakaan - Laporan digenerate secara otomatis
    </div>
</body>

</html>