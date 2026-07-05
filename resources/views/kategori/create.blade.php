<x-app-layout>
    <x-slot name="title">Tambah Kategori</x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Kategori Baru
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama_kategori" id="nama_kategori"
                                class="form-control @error('nama_kategori') is-invalid @enderror"
                                value="{{ old('nama_kategori') }}" placeholder="Contoh: Programming">
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                placeholder="Deskripsi kategori (opsional)">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="icon" class="form-label">Icon Bootstrap</label>
                                <input type="text" name="icon" id="icon"
                                    class="form-control @error('icon') is-invalid @enderror"
                                    value="{{ old('icon') }}" placeholder="Contoh: bi-code-slash">
                                <small class="text-muted">Gunakan class icon dari <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="warna" class="form-label">Warna Badge</label>
                                <select name="warna" id="warna" class="form-select @error('warna') is-invalid @enderror">
                                    <option value="">-- Pilih Warna --</option>
                                    @foreach(['primary', 'success', 'info', 'warning', 'danger', 'secondary', 'dark'] as $w)
                                        <option value="{{ $w }}" {{ old('warna') == $w ? 'selected' : '' }}>
                                            {{ ucfirst($w) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('warna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
