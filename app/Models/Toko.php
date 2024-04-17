<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MasterTrait;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'm_toko';

    protected $fillable = [
        'norectoko',
        'nama_toko',
        'alamat',
        'bisnis_id',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         try {
    //             $model->norectoko = Generator::uuid4()->toString();
    //         } catch (UnsatisfiedDependencyException $e) {
    //             abort(500, $e->getMessage());
    //         }
    //     });
    // }

    // public function getCasts()
    // {
    //     if ($this->incrementing) {
    //         return array_merge([
    //             $this->getKeyName() => 'int',
    //         ], $this->casts);
    //     }
    //     return $this->casts;
    // }

    // protected $casts = [
    //     'norectoko' => 'string'
    // ];
}
