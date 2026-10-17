<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $table = 'company_profile';

    protected $fillable = [
        'name', 'slug', 'tagline', 'description',
        'vision', 'mission', 'services',
        'address', 'contact_email', 'contact_phone',
        'logo_path',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'name'          => 'CV Maju Berkah',
                'slug'          => 'cv-maju-berkah',
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