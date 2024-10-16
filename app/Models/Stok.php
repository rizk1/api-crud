<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'jumlah_stok',
        'nomor_seri',
        'additional_info',
        'gambar_barang',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'additional_info' => 'array',
    ];
}
