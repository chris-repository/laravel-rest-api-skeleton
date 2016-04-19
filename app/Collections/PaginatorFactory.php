<?php
declare(strict_types = 1);

namespace App\Collections;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorFactory implements PaginatorFactoryInterface
{

    /**
     * @param Query|QueryBuilder $query
     * @return Paginator
     */
    public function createPaginator($query) : Paginator
    {
        return new Paginator($query);
    }
}