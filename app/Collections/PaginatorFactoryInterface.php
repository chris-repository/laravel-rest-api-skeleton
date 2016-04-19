<?php


namespace App\Collections;


use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface PaginatorFactoryInterface
{
    /**
     * @param Query|QueryBuilder $query
     * @return Paginator
     */
    public function createPaginator($query) : Paginator;
}