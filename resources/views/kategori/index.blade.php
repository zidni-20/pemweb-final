<x-app-layout>
    <x-slot name="title">Daftar Kategori</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="bi bi-tags"></i>
            Daftar Kategori Buku
        </h1>
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Icon</th>
                            <th>Warna</th>
                            <th>Jumlah Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $kategori)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>
                                        @if($kategori->icon)
                                            <i class="bi {{ $kategori->icon }}"></i>
                                        @endif
                                        {{ $kategori->nama_kategori }}
                                    </strong>
                                </td>
                                <td>{{ Str::limit($kategori->deskripsi, 50) ?? '-' }}</td>
                                <td><code>{{ $kategori->icon ?? '-' }}</code></td>
                                <td>
                                    @if($kategori->warna)
                                        <span class="badge bg-{{ $kategori->warna }}">{{ $kategori->warna }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $kategori->jumlah_buku }} buku</span>
                                </td>
                                <td>
                                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Belum ada kategori
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
