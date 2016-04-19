<?php

namespace Tests\Unit\Http\Exceptions;

use App\Exceptions\Handler;
use App\Exceptions\Renderer;
use App\Exceptions\Reporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Exception;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class HandlerTest extends MockeryTestCase
{
    /**
     * @var Handler
     */
    private $handler;
    private $reporter;
    private $renderer;

    public function setUp()
    {
        $this->reporter = Mockery::mock(Reporter::class);
        $this->renderer = Mockery::mock(Renderer::class);
        $this->handler = new Handler($this->reporter, $this->renderer);
        parent::setUp();
    }

    public function testReport()
    {
        $exception = Mockery::mock(Exception::class);
        $this->reporter->shouldReceive('exception')->once()->with($exception);
        $this->handler->report($exception);
    }

    public function testRender()
    {
        $exception = Mockery::mock(Exception::class);
        $request = Mockery::mock(Request::class);
        $this->renderer->shouldReceive('getExceptionResponse')->once()->with($exception, $request);
        $this->assertInstanceOf(Response::class, $this->handler->render($request, $exception));
    }
}