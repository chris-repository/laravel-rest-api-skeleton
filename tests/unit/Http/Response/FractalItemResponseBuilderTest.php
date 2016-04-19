<?php


namespace Tests\Unit\Http\Response;

use App\Http\Response\AbstractFractalResponseBuilder;
use App\Http\Response\FractalItemResponseBuilder;
use App\Transformers\TransformerInterface;
use App\Transformers\TransformerProvider;
use Exception;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use stdClass;


class FractalItemResponseBuilderTest extends MockeryTestCase
{
    private $sut;

    /** @var  Mockery\MockInterface */
    private $resource;
    /** @var  Mockery\MockInterface */
    private $response;
    /** @var  Mockery\MockInterface */
    private $manager;
    /** @var  Mockery\MockInterface */
    private $transformerProvider;

    protected function setUp()
    {
        $this->resource = Mockery::mock(Item::class);
        $this->response = Mockery::mock(Response::class);
        $this->manager = Mockery::mock(Manager::class);
        $this->transformerProvider = Mockery::mock(TransformerProvider::class);
        $this->sut = new FractalItemResponseBuilder(
            $this->resource,
            $this->response,
            $this->manager,
            $this->transformerProvider
        );
        parent::setUp();
    }

    public function testBuild()
    {
        $item = new StdClass();
        $transformer = Mockery::mock(TransformerInterface::class);
        $scope = Mockery::mock(Scope::class);

        $this->transformerProvider->shouldReceive('getTransformer')
            ->once()
            ->with(StdClass::class)
            ->andReturn($transformer);

        $this->resource->shouldReceive('setTransformer')
            ->with($transformer)
            ->once()
            ->andReturnSelf();


        $this->resource->shouldReceive('setData')
            ->once()
            ->with($item)
            ->andReturnSelf();

        $this->resource->shouldReceive('setResourceKey')
            ->with(AbstractFractalResponseBuilder::RESOURCE_KEY)
            ->once();

        $scope->shouldReceive('toArray')
            ->once()
            ->andReturn([
                "some Content"
            ]);

        $this->response->shouldReceive('setContent')
            ->with([
                "some Content"
            ])->once()
            ->andReturn($this->response);

        $this->manager->shouldReceive('createData')
            ->once()
            ->with($this->resource)
            ->andReturn($scope);

        $this->sut->build($item);
    }

    public function testFailedBuild()
    {
        $this->setExpectedException(Exception::class);
        $this->sut->build([]);
    }

}
