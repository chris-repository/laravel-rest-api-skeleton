<?php

namespace App\Http\Requests\ExampleItem;

use App\Http\Requests\AbstractUpdateRequest;
use App\Models\ExampleItem;

class UpdateRequest extends AbstractUpdateRequest
{
    protected $type = ExampleItem::class;

    public function getIdentifier() : string
    {
        return 'example_items';
    }

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