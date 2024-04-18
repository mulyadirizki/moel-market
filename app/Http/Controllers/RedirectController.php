<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class RedirectController extends Controller
{
    public function cek() {
        if (auth()->user()->roles === 1) {
            return redirect('/admin/koffe');
        } else  {
            return redirect('/');
        }
    }
}
