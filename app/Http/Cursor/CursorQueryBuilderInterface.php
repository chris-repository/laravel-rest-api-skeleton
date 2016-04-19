<?php


namespace App\Http\Cursor;


use App\Http\Requests\ParsedRequest;

interface CursorQueryBuilderInterface
{
    /**
     * @param array $results
     * @param ParsedRequest $parsedRequest
     * @return mixed
     */
    public function buildNextUrl(array $results, ParsedRequest $parsedRequest);

    /**
     * @param ParsedRequest $parsedRequest
     * @return mixed
     */
    public function buildPrevUrl(ParsedRequest $parsedRequest);
}