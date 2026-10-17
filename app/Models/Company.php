<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model `Company` (tabel `companies`) — profil perusahaan.
 * Kontrak blueprint §7.2 dan §8.
 */
class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'user_id',
        'name', 'slug', 'tagline', 'description',
        'vision', 'mission', 'services',
        'address', 'contact_email', 'contact_phone',
        'logo_path',
    ];

    // ============ Relasi ============

    /**
     * Akun admin perusahaan yang mengelola profil ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lowongan yang dimiliki perusahaan ini.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    // ============ Helper ============

    /**
     * Profil perusahaan tunggal yang dipakai situs.
     * Kembalikan baris pertama, atau buat dengan nilai default bila belum ada.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['slug' => 'cv-maju-berkah'],
            [
                'name'          => 'CV Maju Berkah',
                'tagline'       => 'Bertumbuh Bersama, Melangkah Lebih Maju.',
                'description'   => 'Perusahaan distribusi barang dan penyediaan kebutuhan usaha yang menghubungkan produsen terpercaya dengan pelaku usaha di seluruh Indonesia.',
                'vision'        => 'Menjadi mitra distribusi barang dan kebutuhan usaha paling andal di Indonesia.',
                'mission'       => 'Menyediakan pasokan berkualitas dengan harga bersaing, menjaga ketepatan pengiriman, dan membangun kemitraan usaha yang saling menguntungkan.',
                'services'      => "Distribusi & Pengiriman Barang\nPenyediaan Produk Kebutuhan Usaha\nPengelolaan Persediaan & Pergudangan\nLayanan Pelanggan & Konsultasi Pasokan",
                'address'       => 'Jl. Gatot Subroto No. 12, Jakarta Selatan 12930',
                'contact_email' => 'info@majuberkah.test',
                'contact_phone' => '021-0000000',
            ]
        );
    }
}
