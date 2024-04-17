<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MasterTrait;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 't_penjualan';

    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'id_penjualan',
        'toko_id',
        'norec_user',
        'no_nota',
        'tgl_nota',
        'total',
        'uang_bayar',
        'uang_kembali',
        'status',
        'nm_pelanggan',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         try {
    //             $model->id_penjualan = Generator::uuid4()->toString();
    //         } catch (UnsatisfiedDependencyException $e) {
    //             abort(500, $e->getMessage());
    //         }
    //     });
    // }
}
