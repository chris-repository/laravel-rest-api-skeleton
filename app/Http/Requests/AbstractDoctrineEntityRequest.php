<?php

namespace App\Http\Requests;

use App\Serializer\DoctrineDeserializer;
use Doctrine\ORM\EntityManager;
use Illuminate\Routing\Route;

abstract class AbstractDoctrineEntityRequest extends AbstractRequest
{
    abstract public function deserializeRequest();

    /**
     * @return DoctrineDeserializer
     */
    public function getDeserializer() : DoctrineDeserializer
    {
        return $this->container->make(DoctrineDeserializer::class);
    }

    /**
     * @return mixed
     */
    public function getDeserializedContent()
    {
        return $this->deserializeRequest();
    }

    /**
     * @param $identifier
     * @return object|string
     */
    public function getRouteParameter($identifier)
    {
        /** @var Route $route */
        $route = $this->container->make(Route::class);

        return $route->getParameter($identifier);
    }
}