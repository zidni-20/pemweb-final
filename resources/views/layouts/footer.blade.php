<footer class="bg-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-8">
            <div>
                <h5 class="text-lg font-semibold flex items-center gap-2">
                    <i class="bi bi-book-fill text-white"></i>
                    Sistem Perpustakaan
                </h5>
                <p class="text-blue-200 mt-2">Sistem Manajemen Perpustakaan menggunakan Laravel 12</p>
            </div>

            <div>
                <h6 class="font-medium">Menu</h6>
                <ul class="mt-2 space-y-1">
                    <li><a href="{{ url('/') }}" class="text-blue-100 hover:text-white">Home</a></li>
                    <li><a href="{{ route('buku.index') }}" class="text-blue-100 hover:text-white">Buku</a></li>
                    <li><a href="{{ route('anggota.index') }}" class="text-blue-100 hover:text-white">Anggota</a></li>
                </ul>
            </div>

            <div>
                <h6 class="font-medium">Kontak</h6>
                <p class="text-blue-200 mt-2 text-sm">
                    <i class="bi bi-envelope"></i> perpustakaan@example.com<br />
                    <i class="bi bi-telephone"></i> (021) 1234-5678
                </p>
            </div>
        </div>

        <div class="border-t border-blue-700 pt-4 pb-6">
            <div class="text-center text-blue-200 text-sm">
                &copy; {{ date('Y') }} Sistem Perpustakaan. Built with <i class="bi bi-heart-fill text-red-500"></i>
                using Laravel 12.
            </div>
        </div>
    </div>
</footer>