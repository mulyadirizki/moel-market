<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MasterTrait;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

class Category extends Model
{
    use HasFactory;

    protected $table = 'm_category';

    protected $primaryKey = 'id_category';

    protected $fillable = [
        'id_category',
        'category_name',
        'toko_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id_category = Generator::uuid4()->toString();
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
        'id_category' => 'string'
    ];
}
