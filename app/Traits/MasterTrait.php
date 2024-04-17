<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

trait MasterTrait
{
    protected function idCreate($table, $field)
    {
        $id = IdGenerator::generate(['table' => $table, 'field' => $field, 'length' => 10, 'prefix' => date('ymd'), 'reset_on_prefix_change' => true]);
        return $id;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id_barang = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
