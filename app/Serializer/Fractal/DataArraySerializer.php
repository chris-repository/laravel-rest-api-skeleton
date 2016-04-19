<?php
declare(strict_types = 1);

namespace App\Serializer\Fractal;


use League\Fractal\Serializer\ArraySerializer;

class DataArraySerializer extends ArraySerializer
{

    const STRIPPED_RESOURCE_KEY = 'embedded';

    public function collection($resourceKey, array $data)
    {
        return ($resourceKey && $resourceKey !== self::STRIPPED_RESOURCE_KEY) ? array($resourceKey => $data) : $data;
    }

    public function item($resourceKey, array $data)
    {
        return ($resourceKey && $resourceKey !== self::STRIPPED_RESOURCE_KEY) ? array($resourceKey => $data) : $data;
    }
}