<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 't_inv_stok';

    // protected $primaryKey = null;
    protected $primaryKey = 'id_stok';
    public $incrementing = false;

    protected $fillable = [
        'th',
        'bln',
        'id_barang',
        'rak',
        'tgl_expired',
        'awal',
        'masuk',
        'keluar',
        'sudah_so',
        'toko_id',
    ];
}
