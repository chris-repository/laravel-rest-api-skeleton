<?php
declare(strict_types = 1);

namespace App\Providers;

use App\Http\Response\AbstractFractalResponseBuilder;
use App\Serializer\Fractal\DataArraySerializer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class FractalManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->resolving(Manager::class, function ($object, $app) {
            /** @var Request $request */
            /** @var Application $app */
            $request = $app->make(Request::class);
            if($includes = $request->get(AbstractFractalResponseBuilder::PARAM_INCLUDES)) {
                /** @var Manager $object */
                $object->parseIncludes($includes);
            }
        });
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Manager::class, function () {
            return (new Manager())->setSerializer(new DataArraySerializer());
        });
    }
}