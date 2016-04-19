<?php
declare(strict_types = 1);

namespace App\Collections\FilterProcessors;


use App\Collections\FilterMethodNormalizer;
use App\Collections\MethodNameConverter;
use App\Collections\QueryBuilderProviders\QueryBuilderProviderInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\Request;
use ReflectionClass;
use ReflectionMethod;

class FilterProcessor
{
    const DEFAULT_MAX_RESULTS = 15;

    /** @var  QueryBuilder */
    protected $queryBuilder;

    /** @var QueryBuilderProviderInterface */
    protected $queryBuilderProvider;

    /** @var  MethodNameConverter */
    private $filterMethodNormalizer;

    /**
     * AbstractFilterProcessor constructor.
     * @param QueryBuilderProviderInterface $queryBuilderProvider
     * @param MethodNameConverter $filterMethodNormalizer
     */
    public function __construct(
        QueryBuilderProviderInterface $queryBuilderProvider,
        MethodNameConverter $filterMethodNormalizer = null
    ) {
        $this->queryBuilderProvider = $queryBuilderProvider;
        $this->queryBuilder = $queryBuilderProvider->createQueryBuilder();
        $this->filterMethodNormalizer = $filterMethodNormalizer;
    }

    /** @TODO do this better */
    public function processAfterFilter($value)
    {
        /** @var OrderBy $orderBy */
        $orderBy = $this->queryBuilder->getDQLParts()['orderBy'][0];
        $primaryOrderBy = $orderBy->getParts()[0];
        list($orderColumn, $direction) = explode(" ", $primaryOrderBy);
        switch ($direction) {
            case 'DESC' :
                $expr = 'lt';
                break;
            case 'ASC' :
            default:
                $expr = 'gt';
        }
        $this->queryBuilder->andWhere($this->queryBuilder->expr()->$expr($orderColumn,
            ':cursor'))->setParameter(':cursor', $value);
    }

    public function processMaxResultsFilter($value = self::DEFAULT_MAX_RESULTS)
    {
        $this->queryBuilder->setMaxResults($value);
    }

    public function __call($name, array $arguments)
    {
        $method = $this->getFilterMethodNormalizer()->normalize($name);
        if (method_exists($this, $method)) {
            $reflectionMethod = new ReflectionMethod($this, $method);
            if (empty($arguments)) {
                if (!$reflectionMethod->getNumberOfRequiredParameters()) {
                    $this->$method();
                }
            } else {
                $parameter = $reflectionMethod->getParameters()[0];
                $type = $parameter->getType();
                $value = $arguments[0];
                if ($type !== null) {
                    //check value type
                    if ($type == 'array') {
                        $value = explode(',', $value);
                    }
                }
                if ($parameter->getClass()) {
                    if ($parameter->getClass()->name === 'DateTime') {
                        $value = new DateTime($value);
                    }
                }


                $this->$method($value);
            }
        }
    }

    public function processRequest(Request $request) : QueryBuilder
    {
        /** @var ReflectionMethod[] $defaults */
        $defaults = [];
        $reflectionClass = new ReflectionClass($this);

        foreach ($reflectionClass->getMethods() as $method) {
            if (!$method->getNumberOfRequiredParameters()) {
                if ($filterName = $this->getFilterMethodNormalizer()->denormalize($method->getName())) {
                    $defaults[$filterName] = $method;
                }
            }
        };
        //apply requested filters
        foreach ($request->query->all() as $filter => $value) {
            $this->$filter($value);
            unset($defaults[$filter]);
        }

        foreach ($defaults as $method) {
            $method->invoke($this);
        }

        return $this->queryBuilder;

    }

    /**
     * @return MethodNameConverter
     */
    public function getFilterMethodNormalizer() : MethodNameConverter
    {
        if ($this->filterMethodNormalizer === null) {
            $this->filterMethodNormalizer = new FilterMethodNormalizer();
        }
        return $this->filterMethodNormalizer;
    }

    /**
     * @param MethodNameConverter $filterMethodNormalizer
     * @return $this
     */
    public function setFilterMethodNormalizer(MethodNameConverter $filterMethodNormalizer)
    {
        $this->filterMethodNormalizer = $filterMethodNormalizer;
        return $this;
    }
}
