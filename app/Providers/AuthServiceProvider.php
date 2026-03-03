<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Guild::class => \App\Policies\GuildPolicy::class,
        \App\Models\GuildMember::class => \App\Policies\GuildMemberPolicy::class,
        \App\Models\GuildCategory::class => \App\Policies\GuildCategoryPolicy::class,
        \App\Models\GuildPost::class => \App\Policies\GuildPostPolicy::class,
        \App\Models\GuildPostComment::class => \App\Policies\GuildPostCommentPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });
    }
}
