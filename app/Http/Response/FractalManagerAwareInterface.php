<?php


namespace App\Http\Response;


use League\Fractal\Manager;

interface FractalManagerAwareInterface
{
    /**
     * @return Manager
     */
    public function getManager() : Manager;
}