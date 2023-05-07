<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('admin', function (User $user) {
            return ($user->role === 'admin');
        });
        Gate::define('pimpinan', function (User $user) {
            return ($user->role === 'pimpinan');
        });
        Gate::define('adpim', function (User $user) {
            return ($user->role === 'admin' or $user->role === 'pimpinan');
        });
        Gate::define('guru', function (User $user) {
            return ($user->role === 'guru');
        });
        Gate::define('siswa', function (User $user) {
            return ($user->role === 'siswa');
        });
        Gate::define('waliAsrama', function (User $user) {
            return ($user->role === 'wali_asrama');
        });
        Gate::define('adwal', function (User $user) {
            return ($user->role === 'admin' or $user->role === 'wali_asrama');
        });
        Gate::define('adwalpim', function (User $user) {
            return ($user->role === 'admin' or $user->role === 'wali_asrama' or $user->role === 'pimpinan');
        });
    }
}
