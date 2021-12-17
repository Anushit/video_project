<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Roles;
use App\User;

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
    public function boot()
    {
        $this->registerPolicies();
        $this->registerRolesPolicies();
    }

    public function registerRolesPolicies(){
        Gate::define('roles', function (User $user) {
            $userPermission = $user->hasAccess(['roles']);
            return $userPermission;
        });

        Gate::define('roles.add', function (User $user) {
            $userPermission = $user->hasAccess(['roles.add']);
            return $userPermission;
        });

        Gate::define('roles.edit', function (User $user) {
            $userPermission = $user->hasAccess(['roles.edit']);
            return $userPermission;
        });

        Gate::define('users', function (User $user) {
            $userPermission = $user->hasAccess(['users']);
            return $userPermission;
        });

        Gate::define('users.add', function (User $user) {
            $userPermission = $user->hasAccess(['users.add']);
            return $userPermission;
        });

        Gate::define('users.edit', function (User $user) {
            $userPermission = $user->hasAccess(['users.edit']);
            return $userPermission;
        });

        Gate::define('customers', function (User $user) {
            $userPermission = $user->hasAccess(['customers']);
            return $userPermission;
        });

        Gate::define('customers.add', function (User $user) {
            $userPermission = $user->hasAccess(['customers.add']);
            return $userPermission;
        });

        Gate::define('customers.edit', function (User $user) {
            $userPermission = $user->hasAccess(['customers.edit']);
            return $userPermission;
        });

        Gate::define('cms.pages', function (User $user) {
            $userPermission = $user->hasAccess(['cms.pages']);
            return $userPermission;
        });

        Gate::define('cms.pages.edit', function (User $user) {
            $userPermission = $user->hasAccess(['cms.pages.edit']);
            return $userPermission;
        });

        Gate::define('general.settings', function (User $user) {
            $userPermission = $user->hasAccess(['general.settings']);
            return $userPermission;
        });
        Gate::define('general.settings.edit', function (User $user) {
            $userPermission = $user->hasAccess(['general.settings.edit']);
            return $userPermission;
        });

        Gate::define('categories', function (User $user) {
            $userPermission = $user->hasAccess(['categories']);
            return $userPermission;
        });
        Gate::define('categories.add', function (User $user) {
            $userPermission = $user->hasAccess(['categories.add']);
            return $userPermission;
        });
        Gate::define('categories.edit', function (User $user) {
            $userPermission = $user->hasAccess(['categories.edit']);
            return $userPermission;
        });

        Gate::define('videos', function (User $user) {
            $userPermission = $user->hasAccess(['videos']);
            return $userPermission;
        });
        Gate::define('videos.add', function (User $user) {
            $userPermission = $user->hasAccess(['videos.add']);
            return $userPermission;
        });
        Gate::define('videos.edit', function (User $user) {
            $userPermission = $user->hasAccess(['videos.edit']);
            return $userPermission;
        });
        Gate::define('videos.remarks', function (User $user) {
            $userPermission = $user->hasAccess(['videos.remarks']);
            return $userPermission;
        });
        Gate::define('banners', function (User $user) {
            $userPermission = $user->hasAccess(['banners']);
            //print_r($userPermission);die;
            return $userPermission;
        });
       Gate::define('banners.add', function (User $user) {
        $userPermission = $user->hasAccess(['banners.add']);
        return $userPermission;
       });
        Gate::define('banners.edit', function (User $user) {
        $userPermission = $user->hasAccess(['banners.edit']);
        return $userPermission;
       });
       Gate::define('plans', function (User $user) {
        $userPermission = $user->hasAccess(['plans']);
        //print_r($userPermission);die;
        return $userPermission;
       });
       Gate::define('plans.add', function (User $user) {
       $userPermission = $user->hasAccess(['plans.add']);
       return $userPermission;
       });
       Gate::define('plans.edit', function (User $user) {
        $userPermission = $user->hasAccess(['plans.edit']);
        return $userPermission;
       });

       Gate::define('question-answer', function (User $user) {
        $userPermission = $user->hasAccess(['question-answer']);
        return $userPermission;
       });

       Gate::define('question-answer.add', function (User $user) {
        $userPermission = $user->hasAccess(['question-answer.add']);
        return $userPermission;
       });
    }
}
