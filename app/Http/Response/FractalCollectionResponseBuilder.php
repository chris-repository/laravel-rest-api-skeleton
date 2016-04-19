<?php
declare(strict_types = 1);

namespace App\Http\Response;


use App\Transformers\TransformerProvider;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;

class FractalCollectionResponseBuilder extends AbstractFractalResponseBuilder
    implements FractalCollectionResponseBuilderInterface
{
    /**
     * @var Collection
     */
    protected $resource;

    public function __construct(
        Collection $resource,
        Response $response,
        Manager $manager,
        TransformerProvider $transformerProvider
    ) {
        parent::__construct($response, $manager, $transformerProvider);
        $this->resource = $resource;
        $this->resource->setData([])->setResourceKey(self::RESOURCE_KEY);
    }


    public function build(array $content, Cursor $cursor) : Response
    {
        if(!empty($content)) {

            $transformer = $this->transformerProvider
                ->getTransformer(get_class($content[0]));
            $this->resource->setTransformer($transformer);

            /** @var Collection $resource */
            $this->resource->setCursor($cursor)
                ->setData($content)
                ->setResourceKey(self::RESOURCE_KEY);
        }

        return $this->response->setContent(
            $this->manager->createData($this->resource)->toArray()
        );
    }
}