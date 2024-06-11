<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttachUserToView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $loggedInUser = Auth::user();
            $user = DB::table('users')
                ->leftJoin('m_toko', 'users.toko_id', '=', 'm_toko.norectoko')
                ->where('users.noregistrasi', $loggedInUser->noregistrasi)
                ->select('users.*', 'm_toko.*')
                ->first();

            View::share('globalUser', $user);
        }

        return $next($request);
    }
}