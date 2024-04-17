<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Toko;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check() && $user = Auth::user()) {
            if ($user->roles == 2) {
                return redirect()->intended('kasir');
            }
        } else {
            return view('auth.login');
        }
    }

    public function dologin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $dat = User::leftJoin('m_toko', 'users.toko_id', '=', 'm_toko.norectoko')
        ->select('m_toko.bisnis_id')
        ->first();

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->roles === 1 && $dat->bisnis_id === 2) {
                return response()->json([
                    'success'   => true,
                    'redirect'  => '/admin/koffe',
                    'message'   => 'Login berhasil'
                ]);
            } else if(auth()->user()->roles === 2 && $dat->bisnis_id === 2) {
                return response()->json([
                    'success'   => true,
                    'redirect'  => '/front/koffe',
                    'message'   => 'Login berhasil'
                ]);
            }
        }
        return response()->json(['message' => 'Username atau Password salah!'], 401);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
