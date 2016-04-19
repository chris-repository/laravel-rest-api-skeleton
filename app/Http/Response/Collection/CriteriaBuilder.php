<?php
declare(strict_types=1);

namespace App\Http\Response\Collection;


use App\Http\Requests\ParsedRequest;
use Doctrine\Common\Collections\Criteria;

class CriteriaBuilder implements CriteriaBuilderInterface
{

    /**
     * @var Criteria
     */
    private $criteria;

    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    public function build(ParsedRequest $parsedRequest) : Criteria
    {
        return $this->criteria;
    }

}