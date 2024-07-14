<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDet extends Model
{
    use HasFactory;

    protected $table = 't_inv_stok_det';

    protected $primaryKey = 'id_stok_det';
    public $incrementing = false;

    protected $fillable = [
        'id_tx',
        'id_barang',
        'qty',
        'harga_pokok',
        'harga_jual',
        'harga_jual_default',
        'id_trans',
        'tgl_trans',
        'tgl_expired',
        'in_out',
        'user',
        'toko_id',
    ];
}
