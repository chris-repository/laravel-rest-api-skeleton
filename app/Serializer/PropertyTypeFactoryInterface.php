<?php


namespace App\Serializer;


interface PropertyTypeFactoryInterface
{
    /**
     * @param array $propertyAnnotations
     * @return PropertyTypeInterface
     */
    public function getPropertyType(array $propertyAnnotations) : PropertyTypeInterface;
}