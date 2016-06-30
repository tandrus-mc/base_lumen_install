<?php

namespace App\Providers;

use App\Lead;
use App\LeadList;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use App\Policies\LeadPolicy;
use App\Policies\LeadListPolicy;

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

        $this->app['auth']->viaRequest('api', function ($request)
        {
            return User::where('login', $request->input('login'))->first();
        });

        $this->app[Gate::class]->policy(Lead::class, LeadPolicy::class);

        $this->app[Gate::class]->policy(LeadList::class, LeadListPolicy::class);

        $this->app[Gate::class]->policy(User::class, UserPolicy::class);

    }
}
