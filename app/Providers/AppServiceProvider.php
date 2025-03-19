<?php

namespace App\Providers;

use App\Repositories\Eloquent\PessoaRepository;
use App\Repositories\Eloquent\ServidorEfetivoRepository;
use App\Repositories\PessoaRepositoryInterface;
use App\Repositories\ServidorEfetivoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(PessoaRepositoryInterface::class, PessoaRepository::class);
        $this->app->bind(ServidorEfetivoRepositoryInterface::class, ServidorEfetivoRepository::class);

    }
}
