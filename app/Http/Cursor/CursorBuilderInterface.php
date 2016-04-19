<?php


namespace App\Http\Cursor;

use App\Http\Requests\ParsedRequest;
use League\Fractal\Pagination\Cursor;

interface CursorBuilderInterface
{
    public function buildCursor(array $result, int $count, ParsedRequest $parsedRequest, int $maxResults) : Cursor;
}