<?php
declare(strict_types = 1);

namespace App\Providers;

use App\Collections\PaginatorFactory;
use App\Http\Cursor\CursorBuilder;
use App\Http\Response\FractalCollectionResponseBuilder;
use App\Http\Response\ResponseGenerator;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ResponseGeneratorServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ResponseGenerator::class, function ($app, array $params) {
            /** @var Application $app */
            if(empty($params)) {
                throw new Exception;
            }
            return new ResponseGenerator(
                $app->make(FractalCollectionResponseBuilder::class),
                $app->make(CursorBuilder::class),
                $app->make(PaginatorFactory::class),
                $app->make($params[0])
            );
        });
    }
}