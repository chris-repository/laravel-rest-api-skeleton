<?php
declare(strict_types = 1);

namespace App\Providers;


use App\Transformers\TransformerProvider;
use App\Transformers\TransformerProviderInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TransformerProviderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TransformerProviderInterface::class, function ($app) {
            /** @var Application $app */
            return new TransformerProvider(
                $app->make(Repository::class),
                $app
            );
        });
    }
}