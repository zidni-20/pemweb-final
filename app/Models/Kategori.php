<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'icon',
        'warna',
    ];

    /**
     * Relasi ke Buku (berdasarkan nama kategori).
     */
    public function bukus()
    {
        return Buku::where('kategori', $this->nama_kategori);
    }

    /**
     * Hitung jumlah buku di kategori ini.
     */
    public function getJumlahBukuAttribute()
    {
        return Buku::where('kategori', $this->nama_kategori)->count();
    }
}
