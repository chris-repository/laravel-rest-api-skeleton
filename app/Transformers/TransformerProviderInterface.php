<?php


namespace App\Transformers;


interface TransformerProviderInterface
{
    /**
     * @param $item
     * @return TransformerInterface
     */
    public function getTransformer($item) : TransformerInterface;
}