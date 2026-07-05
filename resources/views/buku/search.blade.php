<x-app-layout>
    <x-slot name="title">Pencarian & Filter Buku</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="bi bi-search"></i>
            Pencarian & Filter Buku
        </h1>
    </div>

    {{-- Search Form --}}
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-funnel"></i>
                Form Pencarian & Filter Advanced
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('buku.search') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="keyword" class="form-label">
                        <i class="bi bi-type"></i> Kata Kunci
                    </label>
                    <input type="text" class="form-control" id="keyword" name="keyword"
                        placeholder="Judul, Pengarang, Penerbit" value="{{ $keyword }}">
                    <small class="text-muted d-block mt-1">
                        Cari berdasarkan judul, pengarang, atau penerbit
                    </small>
                </div>

                <div class="col-md-2">
                    <label for="kategori" class="form-label">
                        <i class="bi bi-tag"></i> Kategori
                    </label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ $kategori === $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="tahun" class="form-label">
                        <i class="bi bi-calendar"></i> Tahun Terbit
                    </label>
                    <select class="form-select" id="tahun" name="tahun">
                        <option value="">-- Semua Tahun --</option>
                        @foreach ($tahunList as $thn)
                            <option value="{{ $thn }}" {{ $tahun === (string) $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="ketersediaan" class="form-label">
                        <i class="bi bi-inbox"></i> Ketersediaan
                    </label>
                    <select class="form-select" id="ketersediaan" name="ketersediaan">
                        <option value="">-- Semua --</option>
                        <option value="tersedia" {{ $ketersediaan === 'tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="habis" {{ $ketersediaan === 'habis' ? 'selected' : '' }}>
                            Habis
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-50">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('buku.search') }}" class="btn btn-secondary w-50">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Total Hasil</h6>
                            <h2>{{ $totalBuku }}</h2>
                        </div>
                        <i class="bi bi-book-fill text-success" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Tersedia</h6>
                            <h2>{{ $bukuTersedia }}</h2>
                        </div>
                        <i class="bi bi-check-circle-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Habis</h6>
                            <h2>{{ $bukuHabis }}</h2>
                        </div>
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Results --}}
    <div class="row">
        @forelse ($bukus as $buku)
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <x-buku-card-component :buku="$buku" :show-actions="true" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <strong>Tidak ada hasil pencarian</strong>
                    <p class="mb-0">Silakan ubah kriteria pencarian Anda</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Buku
        </a>
    </div>
</x-app-layout>