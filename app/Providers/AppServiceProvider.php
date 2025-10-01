<?php

namespace App\Providers;

use App\Repository\Eloquent\UserRepository;
use \App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /** Registra no Service Container do Laravel que a interface
        * UserRepositoryInterface deve ser resolvida como uma instância
        * da classe UserRepository.
        * Assim, quando um controller ou outro serviço fizer type-hint
        * de UserRepositoryInterface, o Laravel injeta UserRepository.
        * Observação: bind fornece uma nova instância a cada resolução.
        */
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
