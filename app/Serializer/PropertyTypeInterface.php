<?php
declare(strict_types = 1);

namespace App\Serializer;


interface PropertyTypeInterface
{

    public function getAnnotation();

    /**
     * @return string
     */
    public function getType() : string;

}