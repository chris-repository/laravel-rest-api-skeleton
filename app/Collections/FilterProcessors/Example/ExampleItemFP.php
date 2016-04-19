<?php

namespace App\Collections\FilterProcessors\Example;

use App\Collections\FilterProcessors\FilterProcessor;
use App\Collections\QueryBuilderProviders\Example\ExampleItemQBP;

class ExampleItemFP extends FilterProcessor
{
    public function __construct(ExampleItemQBP $queryBuilderProvider) {
        parent::__construct($queryBuilderProvider);
    }
}