<?php

namespace Tests\Unit\Http\Exceptions;

use App\Exceptions\Renderer;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @todo Finish tests
 */
class RendererTest extends MockeryTestCase
{
    /**
     * @var Renderer
     */
    private $renderer;
    private $response;
    private $repository;

    public function setUp()
    {
        $this->response = Mockery::mock(Response::class);
        $this->repository = Mockery::mock(Repository::class);
        $this->repository->shouldReceive('get');
        $this->renderer = Mockery::mock(Renderer::class, array($this->response, $this->repository))->makePartial();
        parent::setUp();
    }

    public function testWriteResponse()
    {
        $this->response->shouldReceive('setStatusCode')->once()->andReturn($this->response);
        $this->assertInstanceOf(Response::class, $this->renderer->writeResponse(array(), 2));

        $this->response->shouldReceive('setContent')->once()->andReturn($this->response);
        $this->response->shouldReceive('setStatusCode')->once()->andReturn($this->response);
        $this->assertInstanceOf(Response::class, $this->renderer->writeResponse(array('not empty'), 2));
    }
}