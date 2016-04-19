<?php


namespace Tests\Unit\Http\Response;

use App\Collections\FilterProcessors\FilterProcessor;
use App\Collections\FilterProcessors\QueryProcessor;
use App\Collections\PaginatorFactoryInterface;
use App\Collections\AbstractQueryBuilderProvider;
use App\Http\Cursor\CursorBuilder;
use App\Http\Requests\ParsedRequest;
use App\Http\Response\FractalCollectionResponseBuilder;
use App\Http\Response\ResponseGenerator;
use ArrayIterator;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;


class ResponseGeneratorTest extends MockeryTestCase
{
    /** @var  ResponseGenerator */
    private $sut;

    /** @var  Mockery\MockInterface */
    private $cursorBuilder;

    /** @var  Mockery\MockInterface */
    private $fractalCollectionResponseBuilder;

    /** @var  Mockery\MockInterface */
    private $paginatorFactory;

    /** @var  Mockery\MockInterface */
    private $filterProcessor;

    protected function setUp()
    {
        $this->fractalCollectionResponseBuilder = Mockery::mock(FractalCollectionResponseBuilder::class);
        $this->cursorBuilder = Mockery::mock(CursorBuilder::class);
        $this->paginatorFactory = Mockery::mock(PaginatorFactoryInterface::class);
        $this->filterProcessor = Mockery::mock(FilterProcessor::class);
        $this->sut = new ResponseGenerator(
            $this->fractalCollectionResponseBuilder,
            $this->cursorBuilder,
            $this->paginatorFactory,
            $this->filterProcessor
        );
        parent::setUp();
    }

    public function testGenerateCollectionResponse()
    {
        $queryBuilder = Mockery::mock(QueryBuilder::class);
        $parsedRequest = Mockery::mock(ParsedRequest::class);
        $request = Mockery::mock(Request::class);
        $paginator = Mockery::mock(Paginator::class);
        $iterator = Mockery::mock(ArrayIterator::class);
        $manager = Mockery::mock(Manager::class);

        $this->fractalCollectionResponseBuilder->shouldReceive('getManager')
            ->once()
            ->andReturn($manager);

        $manager->shouldReceive('getRequestedIncludes')
            ->once()
            ->andReturn([]);

        $queryBuilder->shouldReceive('getMaxResults')
            ->once()
            ->andReturn(5);

        $parsedRequest->shouldReceive('getRequest')
            ->once()
            ->andReturn($request);

        $this->filterProcessor->shouldReceive('processRequest')
            ->once()
            ->with($request)
            ->andReturn($queryBuilder);

        $this->paginatorFactory->shouldReceive('createPaginator')
            ->once()
            ->with($queryBuilder)
            ->andReturn($paginator);

        $paginator->shouldReceive('getIterator')
            ->once()
            ->andReturn($iterator);

        $paginator->shouldReceive('count')
            ->once()
            ->withNoArgs()
            ->andReturn(2);

        $iterator->shouldReceive('getArrayCopy')
            ->once()
            ->andReturn([]);

        $cursor = Mockery::mock(Cursor::class);
        $this->cursorBuilder->shouldReceive('buildCursor')
            ->once()
            ->andReturn($cursor);

        $this->fractalCollectionResponseBuilder->shouldReceive('build')
            ->once()
            ->with([], $cursor);

        $this->sut->generateCollectionResponse($parsedRequest);
    }


}
