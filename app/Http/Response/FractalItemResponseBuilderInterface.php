<?php


namespace App\Http\Response;

use Illuminate\Http\Response;

interface FractalItemResponseBuilderInterface extends FractalManagerAwareInterface
{
    /**
     * @param $item
     * @return Response
     */
    public function build($item) : Response;
}