<?php

namespace App\Providers;

use App\Collections\FilterMethodNormalizer;
use App\Collections\MethodNormalizerInterface;
use App\Collections\PaginatorFactory;
use App\Collections\PaginatorFactoryInterface;
use App\Console\Commands\ProposeOld;
use App\OAuth\OAuthAuthorizer;
use App\Utility\ApplicationAwareInterface;
use App\Utility\EntityManagerAwareInterface;
use App\Utility\OAuthAuthorizerAwareInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(function ($object, $app) {

            /** @var Application $app */
            if($object instanceof ApplicationAwareInterface) {
                $object->setApplication($app);
            }
            if($object instanceof EntityManagerAwareInterface) {
                $object->setEntityManager($app['em']);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaginatorFactoryInterface::class, PaginatorFactory::class);
        $this->app->bind(MethodNormalizerInterface::class, FilterMethodNormalizer::class);
    }
}
