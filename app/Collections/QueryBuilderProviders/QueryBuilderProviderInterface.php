<?php


namespace App\Collections\QueryBuilderProviders;

use Doctrine\ORM\QueryBuilder;

interface QueryBuilderProviderInterface
{
    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder;
}