<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\IFacilityRepository;
use App\Infrastructure\Repositories\EloquentFacilityRepository;
use App\Domain\Repositories\IEquipmentRepository;
use App\Infrastructure\Repositories\EloquentEquipmentRepository;
use App\Domain\Repositories\IProjectRepository;
use App\Infrastructure\Repositories\EloquentProjectRepository;
use App\Domain\Repositories\IProgramRepository;
use App\Infrastructure\Repositories\EloquentProgramRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind domain repository interfaces to Eloquent implementations
        $this->app->bind(IFacilityRepository::class, EloquentFacilityRepository::class);
        $this->app->bind(IEquipmentRepository::class, EloquentEquipmentRepository::class);
        $this->app->bind(IProjectRepository::class, EloquentProjectRepository::class);
    $this->app->bind(IProgramRepository::class, EloquentProgramRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
