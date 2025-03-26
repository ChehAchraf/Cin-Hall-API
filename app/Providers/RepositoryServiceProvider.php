<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\HallRepository;
use App\Repositories\MovieRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\HallRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Repositories\SeatRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(HallRepositoryInterface::class, HallRepository::class);
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(SeatRepositoryInterface::class, SeatRepository::class);
    }

    public function boot()
    {
        //
    }
} 