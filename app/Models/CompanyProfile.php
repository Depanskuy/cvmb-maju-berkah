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
                'description'   => 'Perusahaan distribusi barang dan penyediaan kebutuhan usaha.',
                'contact_email' => 'info@majuberkah.test',
                'contact_phone' => '021-0000000',
            ]
        );
    }
}