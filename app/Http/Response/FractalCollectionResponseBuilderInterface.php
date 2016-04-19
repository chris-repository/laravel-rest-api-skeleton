<?php


namespace App\Http\Response;


use Illuminate\Http\Response;
use League\Fractal\Pagination\Cursor;

interface FractalCollectionResponseBuilderInterface extends FractalManagerAwareInterface
{
    /**
     * @param array $content
     * @param Cursor $cursor
     * @return Response
     */
    public function build(array $content, Cursor $cursor) : Response;
}