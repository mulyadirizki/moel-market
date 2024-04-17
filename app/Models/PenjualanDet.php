<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MasterTrait;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class PenjualanDet extends Model
{
    use HasFactory;

    protected $table = 't_penjualan_det';

    protected $primaryKey = 'id_penjualan_det';

    protected $fillable = [
        'id_penjualan_det',
        'id_penjualan',
        'tgl_penjualan',
        'id_item',
        'qty',
        'harga_peritem',
        'sub_total',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id_penjualan_det = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
