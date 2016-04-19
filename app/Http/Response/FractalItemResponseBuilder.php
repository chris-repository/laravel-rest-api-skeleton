<?php
declare(strict_types = 1);

namespace App\Http\Response;

use App\Transformers\TransformerProvider;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

class FractalItemResponseBuilder extends AbstractFractalResponseBuilder
    implements FractalItemResponseBuilderInterface
{
    /**
     * @var Item
     */
    protected $resource;

    public function __construct(
        Item $resource,
        Response $response,
        Manager $manager,
        TransformerProvider $transformerProvider
    ) {
        parent::__construct($response, $manager, $transformerProvider);
        $this->resource = $resource;
    }

    public function build($content) : Response
    {
        if(!is_object($content)) {
            //can't handle this
            throw new \Exception();
        }

        $transformer = $this->transformerProvider
            ->getTransformer(get_class($content));
        $this->resource->setData($content)
            ->setTransformer($transformer)
            ->setResourceKey(self::RESOURCE_KEY);

        return $this->response->setContent(
            $this->manager->createData($this->resource)->toArray()
        );
    }
}