<?php

namespace App\Http\Controllers\Example;

use App\Collections\FilterProcessors\Example\ExampleItemFP;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ExampleItem\CreateRequest;
use App\Http\Requests\ExampleItem\UpdateRequest;
use App\Http\Requests\ParsedRequest;
use App\Models\ExampleItem;

class ExampleItemsController extends ApiController
{
    public function index(ParsedRequest $parsedRequest)
    {
        return $this->getListResponse($parsedRequest, ExampleItemFP::class);
    }

    public function show($hash)
    {
        $exampleItem = $this->getEntityRepository(ExampleItem::class)->find($this->hashToId($hash));

        return $this->getItemResponse($exampleItem);
    }
    
    public function store(CreateRequest $createRequest)
    {
        /** @var ExampleItem $exampleItem */
        $exampleItem = $createRequest->getDeserializedContent();

        $this->entityManager->persist($exampleItem);
        $this->entityManager->flush();

        return $this->getStoreResponse($exampleItem, 'example-items/' . $this->idToHash($exampleItem->getId()));
    }

    public function update(UpdateRequest $updateRequest)
    {
        /** @var ExampleItem $exampleItem */
        $exampleItem = $updateRequest->getDeserializedContent();

        $this->entityManager->persist($exampleItem);
        $this->entityManager->flush();

        return $this->getItemResponse($exampleItem);
    }
    
    public function destroy($hash)
    {
        $exampleItem = $this->getEntityRepository(ExampleItem::class)->find($this->hashToId($hash));
        
        $this->entityManager->remove($exampleItem);
        $this->entityManager->flush();
        
        return $this->getSuccessResponse();
    }
}