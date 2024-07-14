<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

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

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_satuan', 'id_satuan');
    }
}
