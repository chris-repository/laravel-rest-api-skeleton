<?php
declare(strict_types = 1);

namespace App\Http\Cursor;


use App\Http\Requests\ParsedRequest;
use App\Http\Requests\RequestQueryBuilderInterface;

class CursorQueryBuilder implements CursorQueryBuilderInterface
{

    /**
     * @var CursorEncoderInterface
     */
    private $cursorEncoder;

    /**
     * @var RequestQueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * CursorQueryBuilder constructor.
     * @param CursorEncoderInterface $cursorEncoder
     * @param RequestQueryBuilderInterface $queryBuilder
     */
    public function __construct(CursorEncoderInterface $cursorEncoder, RequestQueryBuilderInterface $queryBuilder)
    {
        $this->cursorEncoder = $cursorEncoder;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param array $results
     * @param ParsedRequest $parsedRequest
     * @return mixed
     */
    public function buildNextUrl(array $results, ParsedRequest $parsedRequest)
    {
        $params = [];
        if($parsedRequest->getAfter() !== null) {
            $params[ParsedRequest::PARAM_CURSOR_PREV] = $this->cursorEncoder->encodeCursor($parsedRequest->getAfter());
        }
        $params[ParsedRequest::PARAM_CURSOR_AFTER] = $this->cursorEncoder->encodeCursor(last($results)->getId());
        return $this->queryBuilder->buildQueryFromRequest(
            $parsedRequest->getRequest(),
            $params
        );
    }

    /**
     * @param ParsedRequest $parsedRequest
     * @return mixed
     */
    public function buildPrevUrl(ParsedRequest $parsedRequest)
    {
        if($parsedRequest->getPrev() === null) {
            return null;
        }
        return $this->queryBuilder->buildQueryFromRequest(
            $parsedRequest->getRequest(),
            [
                ParsedRequest::PARAM_CURSOR_AFTER => $this->cursorEncoder->encodeCursor($parsedRequest->getPrev()),
                ParsedRequest::PARAM_CURSOR_PREV => null
            ]
        );
    }
}