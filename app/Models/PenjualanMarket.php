<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanMarket extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_market';

    protected $primaryKey = 'id_penjualan_market';

    protected $fillable = [
        'id_penjualan_market',
        'statusenabled',
        'user',
        'toko_id',
        'no_nota',
        'tgl_nota',
        'total',
        'uang_bayar',
        'uang_kembali',
        'cara_bayar',
        'uang_kembali',
        'cara_bayar',
        'nm_pelanggan',
        'tgl_pembayaran',
        'keteranganrefund',
    ];
}
