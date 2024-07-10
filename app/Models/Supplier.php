<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MasterTrait;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'm_supplier';

    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'id_supplier',
        'toko_id',
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'no_hp',
    ];

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
        'id_supplier' => 'string'
    ];
}
