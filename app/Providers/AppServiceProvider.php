<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Company;
use App\Policies\CompanyUserPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\CompanyActivityPolicy;
use App\Models\Activity;

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
        Gate::policy(Company::class, CompanyUserPolicy::class);
        Gate::policy(Activity::class, CompanyActivityPolicy::class); 
    }
}
