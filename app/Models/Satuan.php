<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'm_satuan';

    protected $primaryKey = 'id_satuan';

    protected $fillable = [
        'id_satuan',
        'desc_satuan',
        'aliase_satuan'
    ];
}
