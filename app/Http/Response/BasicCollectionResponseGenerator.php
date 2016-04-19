<?php

namespace App\Http\Response;

use Illuminate\Http\Request;
use League\Fractal\Pagination\Cursor;
use Illuminate\Http\Response;

class BasicCollectionResponseGenerator
{
    /**
     * @var FractalCollectionResponseBuilderInterface
     */
    protected $fractalResponseBuilder;

    /**
     * @var Cursor
     */
    protected $cursor;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param FractalCollectionResponseBuilder $fractalResponseBuilder
     * @param Cursor $cursor
     * @param Request $request
     */
    public function __construct(
        FractalCollectionResponseBuilder $fractalResponseBuilder,
        Cursor $cursor,
        Request $request
    ) {
        $this->fractalResponseBuilder = $fractalResponseBuilder;
        $this->cursor = $cursor;
        $this->request = $request;
    }

    public function generateCollectionResponse(array $list) : Response
    {
        $this->cursor
            ->setCurrent($this->request->getRequestUri())
            ->setPrev(null)
            ->setNext(null)
            ->setCount(count($list));

        return $this->fractalResponseBuilder->build($list, $this->cursor);
    }
}