<?php
declare(strict_types = 1);

namespace App\Http\Response;

use App\Collections\FilterProcessors\FilterProcessor;
use App\Collections\PaginatorFactoryInterface;
use App\Http\Cursor\CursorBuilderInterface;
use App\Http\Requests\ParsedRequest;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Illuminate\Http\Response;

class ResponseGenerator
{
    /** @var FractalCollectionResponseBuilderInterface */
    private $fractalResponseBuilder;

    /** @var CursorBuilderInterface */
    private $cursorBuilder;

    /** @var PaginatorFactoryInterface */
    private $paginatorFactory;

    /** @var FilterProcessor */
    private $filterProcessor;

    /**
     * ResponseGenerator constructor.
     * @param FractalCollectionResponseBuilderInterface $fractalResponseBuilder
     * @param CursorBuilderInterface $cursorBuilder
     * @param PaginatorFactoryInterface $paginatorFactory
     * @param FilterProcessor $filterProcessor
     */
    public function __construct(
        FractalCollectionResponseBuilderInterface $fractalResponseBuilder,
        CursorBuilderInterface $cursorBuilder,
        PaginatorFactoryInterface $paginatorFactory,
        FilterProcessor $filterProcessor
    ) {
        $this->fractalResponseBuilder = $fractalResponseBuilder;
        $this->cursorBuilder = $cursorBuilder;
        $this->paginatorFactory = $paginatorFactory;
        $this->filterProcessor = $filterProcessor;
    }


    /**
     * @param ParsedRequest $parsedRequest
     * @return Response
     */
    public function generateCollectionResponse(ParsedRequest $parsedRequest) : Response
    {
        $queryBuilder = $this->filterProcessor->processRequest($parsedRequest->getRequest());

        $includes = $this->fractalResponseBuilder->getManager()->getRequestedIncludes();

        //set eager loading of includes
        /**
         * @TODO this won't work for nested includes e.g. profile_applications.lender_profile
         */
        foreach ($includes as $include) {
            $queryBuilder->getQuery()->setFetchMode($queryBuilder->getRootEntities()[0], $include,
                ClassMetadata::FETCH_EAGER);
        }

        $paginator = $this->paginatorFactory->createPaginator($queryBuilder);

        $result = $paginator->getIterator()->getArrayCopy();

        $cursor = $this->cursorBuilder->buildCursor(
            $result,
            $paginator->count(),
            $parsedRequest,
            (int)$queryBuilder->getMaxResults()
        );

        return $this->fractalResponseBuilder->build($result, $cursor);
    }

}