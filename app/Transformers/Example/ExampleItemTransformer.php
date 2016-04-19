<?php

namespace App\Transformers\Example;

use App\Models\ExampleItem;
use App\Transformers\TransformerAbstract;

class ExampleItemTransformer extends TransformerAbstract
{
    /**
     * @param ExampleItem $exampleItem
     * @return array
     */
    public function transform($exampleItem)
    {
        return [
            'id'   => $this->hashId($exampleItem->getId()),
            'name' => $exampleItem->getName(),
        ];
    }
}