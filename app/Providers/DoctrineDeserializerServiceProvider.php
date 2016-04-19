<?php
declare(strict_types = 1);

namespace App\Providers;

use App\Serializer\DeserializeConfiguration;
use App\Serializer\DeserializerAdapterInterface;
use App\Serializer\DoctrineDeserializer;
use App\Serializer\DoctrineEntityDenormalizer;
use App\Serializer\PropertyTypeFactory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\Serializers\ArrayEncoder;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class DoctrineDeserializerServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DoctrineDeserializer::class, function ($app, $nameConverters = []) {
            /** @var Application $app */
            /** @var Repository $appConfig */
            $appConfig = $app->make(Repository::class);
            $deserializeConfig = new DeserializeConfiguration();
            $deserializeConfig->setIgnoreAdditional($appConfig->get('deserialize.ignoreAdditional'))
                ->setRecursionDepth($appConfig->get('deserialize.recursionDepth'));
            return new DoctrineDeserializer(
                new DoctrineEntityDenormalizer(
                    new PropertyTypeFactory(),
                    $deserializeConfig,
                    $this->app->make('em'),
                    $nameConverters
                )
                ,
                [new JsonDecode(true), new ArrayEncoder()]
            );
        });

        $this->app->alias(DoctrineDeserializer::class, DeserializerAdapterInterface::class);
    }
}