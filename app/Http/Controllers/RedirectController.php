<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RedirectController extends Controller
{
    public function cek(Request $request) {
        $loggedInUser = auth()->user();
        $user = DB::table('users')
            ->leftJoin('m_toko', 'users.toko_id', '=', 'm_toko.norectoko')
            ->where('users.noregistrasi', $loggedInUser->noregistrasi)
            ->select('users.*', 'm_toko.*')
            ->first();

        if ($user->bisnis_id === 1) {
            $request->session()->regenerate();
            if (auth()->user()->roles === 1) {
                return redirect('/backend/market');
            } else if (auth()->user()->roles === 2) {
                return redirect('/front/market');
            } else if (auth()->user()->roles === 3) {
                return redirect('/logout');
            } else {
                return redirect()->route('logout')->with('method', 'post');
            }
        } else if($user->bisnis_id === 2)  {
            $request->session()->regenerate();
            if (auth()->user()->roles === 1) {
                return redirect('/backend/koffe');
            } else if ($user->bisnis_id === 2) {
                return redirect('/front/koffe');
            } else {
                return redirect()->route('logout')->with('method', 'post');
            }
        }

        // if (auth()->user()->roles === 1) {
        //     $request->session()->regenerate();
        //     if ($user->bisnis_id === 1) {
        //         return redirect('/backend/market');
        //     } else if ($user->bisnis_id === 2) {
        //         return redirect('/backend/koffe');
        //     } else {
        //         return redirect()->route('logout')->with('method', 'post');
        //     }
        // } else if(auth()->user()->roles === 2)  {
        //     $request->session()->regenerate();
        //     if ($user->bisnis_id === 1) {
        //         return redirect('/front/market');
        //     } else if ($user->bisnis_id === 2) {
        //         return redirect('/front/koffe');
        //     } else {
        //         return redirect()->route('logout')->with('method', 'post');
        //     }
        // }
    }
}
