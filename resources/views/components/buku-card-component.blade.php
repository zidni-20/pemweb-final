<div class="card h-100 shadow-sm hover-card">
    {{-- Cover Icon --}}
    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="min-height: 200px;">
        <i class="bi bi-book" style="font-size: 4rem; color: #0d6efd;"></i>
    </div>

    <div class="card-body">
        {{-- Kategori Badge --}}
        <div class="mb-2">
            <span class="badge bg-info">{{ $buku->kategori }}</span>
        </div>

        {{-- Judul --}}
        <h5 class="card-title">
            <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none text-dark">
                {{ Str::limit($buku->judul, 30) }}
            </a>
        </h5>

        {{-- Pengarang --}}
        <p class="card-text text-muted small">
            <strong>Pengarang:</strong> {{ Str::limit($buku->pengarang, 25) }}
        </p>

        {{-- Harga --}}
        <h6 class="text-primary font-weight-bold">
            Rp {{ number_format($buku->harga, 0, ',', '.') }}
        </h6>

        {{-- Status Ketersediaan --}}
        <div class="mb-3">
            @if ($buku->stok > 0)
                <span class="badge bg-success">
                    <i class="bi bi-check-circle"></i> Tersedia
                </span>
                <small class="text-muted d-block mt-1">Stok: {{ $buku->stok }} buku</small>
            @else
                <span class="badge bg-danger">
                    <i class="bi bi-x-circle"></i> Habis
                </span>
            @endif
        </div>
    </div>

    {{-- Action Buttons --}}
    @if ($showActions)
        <div class="card-footer bg-white border-top">
            <div class="btn-group btn-group-sm w-100" role="group">
                <a href="{{ route('buku.show', $buku->id) }}" 
                   class="btn btn-outline-primary" 
                   title="Lihat Detail">
                    <i class="bi bi-eye"></i> Detail
                </a>
                <a href="{{ route('buku.edit', $buku->id) }}" 
                   class="btn btn-outline-warning" 
                   title="Edit Buku">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    .hover-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
</style>