<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Unverified;
use App\Policies\UnverifiedWordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Unverified::class => UnverifiedWordPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('update-unverified-word',UnverifiedWordPolicy::class. 'control');
    }
}
