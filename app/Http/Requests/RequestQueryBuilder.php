<?php
declare(strict_types = 1);

namespace App\Http\Requests;


use Illuminate\Http\Request;

class RequestQueryBuilder implements RequestQueryBuilderInterface
{
    /**
     * @param Request $request
     * @param array $params
     * @return string
     */
    public function buildQueryFromRequest(Request $request, array $params)
    {
        $queryParams = clone $request->query;
        $queryParams->add($params);
        return $this->buildQuery($request->path(), $queryParams->all());
    }

    public function buildQuery(string $path, array $params)
    {
        return '/' . $path . '?' .http_build_query($params);
    }
}