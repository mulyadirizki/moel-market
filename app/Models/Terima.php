<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terima extends Model
{
    use HasFactory;

    protected $table = 't_barang_terima';

    protected $fillable = [
        'id_terima',
        'tgl_terima',
        'tgl_faktur',
        'no_faktur',
        'id_tx',
        'id_supplier',
        'user',
        'toko_id',
    ];
}
