<?php


namespace App\Http\Response\Collection;


use App\Http\Requests\ParsedRequest;
use Doctrine\Common\Collections\Criteria;

interface CriteriaBuilderInterface
{
    /**
     * @param ParsedRequest $parsedRequest
     * @return Criteria
     */
    public function build(ParsedRequest $parsedRequest) : Criteria;
}