<?php

namespace App\Http\Cursor;

use App\Http\Requests\ParsedRequest;
use Doctrine\ORM\Query;
use League\Fractal\Pagination\Cursor;


class CursorBuilder implements CursorBuilderInterface
{
    /**
     * @var Cursor
     */
    private $cursor;

    /**
     * @var CursorQueryBuilderInterface
     */
    private $cursorQueryBuilder;


    /**
     * CursorBuilder constructor.
     * @param Cursor $cursor
     * @param CursorQueryBuilderInterface $cursorQueryBuilder
     */
    public function __construct(
        Cursor $cursor,
        CursorQueryBuilderInterface $cursorQueryBuilder
    ) {
        $this->cursor = $cursor;
        $this->cursorQueryBuilder = $cursorQueryBuilder;
    }

    public function buildCursor(array $results, int $count, ParsedRequest $parsedRequest, int $maxResults) : Cursor
    {
        $nextUrl = null;
        if($count >= $maxResults) {
            $nextUrl = $this->cursorQueryBuilder->buildNextUrl($results, $parsedRequest);
        }

        $prevUrl = $this->cursorQueryBuilder->buildPrevUrl($parsedRequest);

        return $this->cursor
            ->setCurrent($parsedRequest->getRequest()->getRequestUri())
            ->setPrev($prevUrl)
            ->setNext($nextUrl)
            ->setCount($count);
    }

}