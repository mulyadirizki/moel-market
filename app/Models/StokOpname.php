<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $table = 't_so';

    // protected $primaryKey = null;
    // protected $primaryKey = 'id_so';
    // public $incrementing = false;

    protected $fillable = [
        'id_so',
        'tgl_so',
        'th',
        'bln',
        'ket_so',
        'aktif',
        'user',
        'toko_id',
    ];
}
