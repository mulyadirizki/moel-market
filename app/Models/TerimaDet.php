<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerimaDet extends Model
{
    use HasFactory;

    protected $table = 't_barang_terima_det';

    protected $fillable = [
        'id_terima',
        'id_barang',
        'qty',
        'tgl_expired',
        'user',
        'toko_id',
    ];
}
