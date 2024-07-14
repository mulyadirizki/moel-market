<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanMarketDet extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_market_det';

    protected $primaryKey = 'id_penjualan_market_det';

    protected $fillable = [
        'id_penjualan_market_det',
        'id_penjualan_market',
        'tgl_penjualan',
        'id_barang',
        'qty',
        'harga_jual_default',
        'sub_total',
        'user',
        'toko_id',
    ];
}
