<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 't_pengeluaran';

    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'id_pengeluaran',
        'toko_id',
        'norec_user',
        'tgl_pengeluaran',
        'nama_barang',
        'harga_barang',
        'jenis_pembayaran',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id_pengeluaran = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    public function getCasts()
    {
        if ($this->incrementing) {
            return array_merge([
                $this->getKeyName() => 'int',
            ], $this->casts);
        }
        return $this->casts;
    }

    protected $casts = [
        'id_pengeluaran' => 'string'
    ];
}
