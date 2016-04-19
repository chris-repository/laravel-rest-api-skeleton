<?php

namespace App\Collections\QueryBuilderProviders\Example;

use App\Collections\QueryBuilderProviders\AbstractQueryBuilderProvider;
use App\Models\ExampleItem;
use Doctrine\ORM\QueryBuilder;

class ExampleItemQBP extends AbstractQueryBuilderProvider
{
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->entityManager->getRepository(ExampleItem::class)->createQueryBuilder('example');

        $qb->orderBy('example.id', 'ASC');

        return $qb;
    }
}