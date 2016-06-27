<?php

namespace App\Providers;

use App\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });

        $this->app[Gate::class]->define('destroy-lead', function($user){
            return $user->isAdmin();
        });

        $this->app[Gate::class]->define('show-lead', function($user, $lead){
            return ($user->isAdmin() || $user->config_id === $lead->config_id);
        });

        $this->app[Gate::class]->define('update-lead', function($user){
            return ($user->isAdmin() || $user->isManager());
        });

    }
}
