<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login()
    {
        $loggedInUser = auth()->user();
        if ($loggedInUser != null) {
            $userData = DB::table('users')
                ->leftJoin('m_toko', 'users.toko_id', '=', 'm_toko.norectoko')
                ->where('users.noregistrasi', $loggedInUser->noregistrasi)
                ->select('users.*', 'm_toko.*')
                ->first();
            if (Auth::check() && $user = Auth::user()) {
                if ($userData->bisnis_id === 1) {
                    if ($user->roles == 1) {
                        return redirect()->intended('admin.market');
                    } else if ($user->roles == 2) {
                        return redirect()->intended('market.kasir');
                    } else if ($user->roles == 3) {
                        return redirect()->intended('market.manajemen');
                    }
                } else if ($userData->bisnis_id === 2) {
                    if ($user->roles == 2) {
                        return redirect()->intended('kasir');
                    }
                }
            } else {
                return view('auth.login');
            }
        } else {
            return view('auth.login');
        }
    }

    public function dologin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            // Define redirect URLs
            // market
            $redirectUrlAdminMarket = Redirect::route('admin.market')->getTargetUrl();
            $redirectUrlKasirMarket = Redirect::route('market.kasir')->getTargetUrl();
            $redirectUrlManajemenMarket = Redirect::route('market.manajemen')->getTargetUrl();

            $redirectUrlAdmin = Redirect::route('admin')->getTargetUrl();
            $redirectUrlKasir = Redirect::route('kasir')->getTargetUrl();

            $loggedInUser = auth()->user();
            $user = DB::table('users')
                ->leftJoin('m_toko', 'users.toko_id', '=', 'm_toko.norectoko')
                ->where('users.noregistrasi', $loggedInUser->noregistrasi)
                ->select('users.*', 'm_toko.*')
                ->first();

            if ($user->bisnis_id === 1) {

                if ($loggedInUser->roles === 1) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrlAdminMarket,
                        'message' => 'Login berhasil'
                    ]);
                } else if ($loggedInUser->roles === 2) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrlKasirMarket,
                        'message' => 'Login berhasil'
                    ]);
                } else if ($loggedInUser->roles === 3) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrlManajemenMarket,
                        'message' => 'Login berhasil'
                    ]);
                }
            } else if ($user->bisnis_id === 2) {
                if ($loggedInUser->roles === 1) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrlAdmin,
                        'message' => 'Login berhasil'
                    ]);
                } else if ($loggedInUser->roles === 2) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrlKasir,
                        'message' => 'Login berhasil'
                    ]);
                }
            }

            // if ($loggedInUser->roles === 1) {

            //     if ($user->bisnis_id === 1) {
            //         return response()->json([
            //             'success' => true,
            //             'redirect' => $redirectUrlAdminMarket,
            //             'message' => 'Login berhasil'
            //         ]);
            //     } else if ($user->bisnis_id === 2) {
            //         return response()->json([
            //             'success' => true,
            //             'redirect' => $redirectUrlAdmin,
            //             'message' => 'Login berhasil'
            //         ]);
            //     }
            // } else if ($loggedInUser->roles === 2) {
            //     if ($user->bisnis_id === 1) {
            //         return response()->json([
            //             'success' => true,
            //             'redirect' => $redirectUrlKasirMarket,
            //             'message' => 'Login berhasil'
            //         ]);
            //     } else if ($user->bisnis_id === 2) {
            //         return response()->json([
            //             'success' => true,
            //             'redirect' => $redirectUrlKasir,
            //             'message' => 'Login berhasil'
            //         ]);
            //     }
            // }
        }

        return response()->json(['message' => 'Username atau Password salah!', $user], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }
}
