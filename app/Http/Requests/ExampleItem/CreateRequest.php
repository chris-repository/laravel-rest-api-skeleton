<?php

namespace App\Http\Requests\ExampleItem;

use App\Http\Requests\AbstractCreateRequest;
use App\Models\ExampleItem;

class CreateRequest extends AbstractCreateRequest
{
    protected $type = ExampleItem::class;

    public function rules() : array
    {
        return [
            'name' => ['string', 'required', 'between:1,100', 'unique:' . ExampleItem::class . ',name'],
        ];
    }

    public function authorize() : bool
    {
        return true;
    }
}