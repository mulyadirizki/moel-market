<?php

namespace App\Http\Controllers\Glob;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    public function index() {
        return view('koffe.backend.home');
    }
}
