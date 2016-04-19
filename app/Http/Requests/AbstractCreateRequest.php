<?php
declare(strict_types = 1);

namespace App\Http\Requests;

use App\Serializer\AsymmetricNameConverter;

abstract class AbstractCreateRequest extends AbstractDoctrineEntityRequest
{
    protected $type;

    public function deserializeRequest()
    {
        if(method_exists($this, 'conversionMap')) {
            $conversionMap = $this->conversionMap();
            foreach($conversionMap as $class => $map) {
                $this->getDeserializer()->addNameConverter(new AsymmetricNameConverter($map), $class);
            }
        }

        return $this->getDeserializer()->deserialize($this->content, $this->type, $this->dataFormat);
    }
}