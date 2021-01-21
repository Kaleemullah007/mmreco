<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

         // --------------------------------
        // BEFORE ANYTHING ELSE
        // --------------------------------
        // If this condition is true, ANYTHING else below will be asssumed
        // to be true. This can cause weird blade behavior.
        $gate->before(function ($user) {
            if ($user->isSuperUser()) {
                return true;
            }
        });

        // --------------------------------
        // GENERAL GATES
        // These control general sections of the admin
        // --------------------------------
        $gate->define('admin', function ($user) {
            if ($user->hasAccess('admin')) {
                return true;
            }
        });

        # -----------------------------------------
        # Users
        # -----------------------------------------

        $gate->define('users.view', function ($user) {
            if (($user->hasAccess('users.view')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });

        $gate->define('users.create', function ($user) {
            if (($user->hasAccess('users.create')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });

        $gate->define('users.edit', function ($user) {
            if (($user->hasAccess('users.edit')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });

        $gate->define('users.delete', function ($user) {
            if (($user->hasAccess('users.delete')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });

        $gate->define('location.view', function ($user) {
            if (($user->hasAccess('location.view')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });

        $gate->define('location.create', function ($user) {
            if (($user->hasAccess('location.create')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });

        $gate->define('location.edit', function ($user) {
            if (($user->hasAccess('location.edit')) || ($user->hasAccess('admin'))) {
                return true;
            }
        });
    }
}
