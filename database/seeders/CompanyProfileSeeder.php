<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Isi / lengkapi profil perusahaan agar halaman publik (beranda, tentang,
     * kontak) menampilkan data yang sesuai.
     */
    public function run(): void
    {
        CompanyProfile::updateOrCreate(
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
