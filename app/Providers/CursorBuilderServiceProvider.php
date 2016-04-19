<?php

namespace App\Providers;

use App\Http\Cursor\CursorBuilder;
use App\Http\Cursor\CursorBuilderInterface;
use App\Http\Cursor\CursorEncoder;
use App\Http\Cursor\CursorEncoderInterface;
use App\Http\Cursor\CursorQueryBuilder;
use App\Http\Cursor\CursorQueryBuilderInterface;
use App\Http\Requests\RequestQueryBuilder;
use App\Http\Requests\RequestQueryBuilderInterface;
use Illuminate\Support\ServiceProvider;

class CursorBuilderServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CursorBuilderInterface::class, CursorBuilder::class);
        $this->app->bind(CursorQueryBuilderInterface::class, CursorQueryBuilder::class);
        $this->app->bind(RequestQueryBuilderInterface::class, RequestQueryBuilder::class);
        $this->app->bind(CursorEncoderInterface::class, CursorEncoder::class);
    }
}
