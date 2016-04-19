<?php


namespace Tests\Unit\Http\Response;

use App\Http\Response\AbstractFractalResponseBuilder;
use App\Http\Response\FractalCollectionResponseBuilder;
use App\Transformers\TransformerInterface;
use App\Transformers\TransformerProvider;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;
use League\Fractal\Scope;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use stdClass;


class FractalCollectionResponseBuilderTest extends MockeryTestCase
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
        $this->resource = Mockery::mock(Collection::class);
        $this->response = Mockery::mock(Response::class);
        $this->manager = Mockery::mock(Manager::class);
        $this->transformerProvider = Mockery::mock(TransformerProvider::class);
        $this->resource->shouldReceive('setData')
            ->once()
            ->with([])
            ->andReturnSelf();
        $this->resource->shouldReceive('setResourceKey')
            ->with(AbstractFractalResponseBuilder::RESOURCE_KEY)
            ->once();
        $this->sut = new FractalCollectionResponseBuilder(
            $this->resource,
            $this->response,
            $this->manager,
            $this->transformerProvider
        );
        parent::setUp();
    }

    public function testBuildWithContent()
    {
        $content = [
            new StdClass()
        ];
        $transformer = Mockery::mock(TransformerInterface::class);
        $scope = Mockery::mock(Scope::class);
        $cursor = Mockery::mock(Cursor::class);

        $this->transformerProvider->shouldReceive('getTransformer')
            ->once()
            ->with(StdClass::class)
            ->andReturn($transformer);

        $this->resource->shouldReceive('setTransformer')
            ->with($transformer)
            ->once();

        $this->resource->shouldReceive('setCursor')
            ->with($cursor)
            ->once()
            ->andReturnSelf();

        $this->resource->shouldReceive('setData')
            ->once()
            ->with($content)
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

        $this->sut->build($content, $cursor);
    }

    public function testBuildWithoutContent()
    {
        $content = [];
        $scope = Mockery::mock(Scope::class);
        $cursor = Mockery::mock(Cursor::class);

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

        $this->sut->build($content, $cursor);
    }

}
