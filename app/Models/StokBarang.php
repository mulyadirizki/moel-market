<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    use HasFactory;

    protected $table = 't_total_stok_barang';

    // protected $primaryKey = null;
    protected $primaryKey = 'id_stok_barang';
    public $incrementing = false;

    protected $fillable = [
        'id_barang',
        'total_stok',
        'toko_id',
    ];
}
