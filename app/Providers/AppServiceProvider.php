<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $loggedInUser = Auth::user();
                $user = DB::table('users')
                    ->leftJoin('m_toko', 'users.toko_id', '=', 'm_toko.norectoko')
                    ->where('users.noregistrasi', $loggedInUser->noregistrasi)
                    ->select('users.*', 'm_toko.*')
                    ->first();

                $view->with('globalUser', $user);
            }
        });
    }
}
