<?php


namespace App\Http\Requests;

use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class AbstractDeleteRequest extends AbstractDoctrineEntityRequest
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

        if(!($class = $this->getEntityManager()->find($this->type, $routeParameter))) {
            throw new ModelNotFoundException;
        }

        return $this->getDeserializer()->deserialize(json_encode(array()), $class, $this->dataFormat);
    }
}