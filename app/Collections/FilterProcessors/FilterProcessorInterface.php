<?php


namespace App\Collections\FilterProcessors;


use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\Request;

interface FilterProcessorInterface
{
    /**
     * @param Request $request
     * @return QueryBuilder
     */
    public function processRequest(Request $request) : QueryBuilder;
}