<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'm_barang';

    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'id_barang',  // Tambahkan id_barang di sini
        'statusenabled',
        'kode_barcode',
        'nama_barang',
        'id_kategori',
        'id_satuan',
        'id_merek',
        'stok_min',
        'stok_max',
        'harga_pokok',
        'harga_jual',
        'margin',
        'harga_jual_default',
        'user',
        'toko_id',
        // Tambahkan field lain jika diperlukan
    ];
}
