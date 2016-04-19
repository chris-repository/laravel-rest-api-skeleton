<?php

namespace App\Http\Controllers\Status;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class StatusController extends ApiController
{
    public function index()
    {
        return new JsonResponse(
            ["status" => "alive"]
        );
    }

    public function store()
    {
        $this->badRequest();
    }
}