<?php

namespace App\Http\Controllers\Koffe\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function dataPembelian()
    {
        return view('koffe.backend.activity.pembelian');
    }
}
