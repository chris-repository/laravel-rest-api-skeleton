<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class AbstractUpdateRequest extends AbstractDoctrineEntityRequest
{
    protected $type;

    /**
     * This is the route param identifier eg. "users" | "organisations"
     *
     * @return string
     */
    abstract public function getIdentifier() : string;

    public function deserializeRequest()
    {
        $routeParameter = $this->getRouteParameter($this->getIdentifier());

        try {
            $id = $this->getIdHasher()->decode($routeParameter)[0];
        } catch (\ErrorException $ex) {
            throw new ModelNotFoundException;
        }

        if(!($class = $this->getEntityManager()->find($this->type, $id))) {
            throw new ModelNotFoundException;
        }

        return $this->getDeserializer()->deserialize($this->content, $class, $this->dataFormat);
    }
}