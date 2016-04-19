<?php


namespace App\Http\Requests;


use Illuminate\Http\Request;

interface RequestQueryBuilderInterface
{
    /**
     * @param Request $request
     * @param array $params
     * @return mixed
     */
    public function buildQueryFromRequest(Request $request, array $params);

    /**
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function buildQuery(string $path, array $params);
}