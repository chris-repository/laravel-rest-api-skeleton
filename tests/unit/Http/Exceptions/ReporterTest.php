<?php

namespace Tests\Unit\Http\Exceptions;

use App\Exceptions\Reporter;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery;
use Exception;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReporterTest extends MockeryTestCase
{
    private $log;
    private $reporter;

    public function setUp()
    {
        $this->log = Mockery::mock(LoggerInterface::class);
        $this->reporter = Mockery::mock(Reporter::class, array($this->log))->makePartial();
        parent::setUp();
    }

    public function testNotReportException()
    {
        $exception = Mockery::mock(Exception::class);
        $this->reporter->shouldReceive('shouldReport')->once()->andReturn(false);
        $this->reporter->exception($exception);
    }

    public function testShouldReportException()
    {
        $exception = Mockery::mock(Exception::class);
        $this->reporter->shouldReceive('shouldReport')->once()->andReturn(true);
        $this->log->shouldReceive('error')->once()->with($exception);
        $this->reporter->exception($exception);
    }

    /**
     * @dataProvider exceptionProvider
     */
    public function testShouldReport($exception, $expected)
    {
        $this->assertEquals($expected, $this->reporter->shouldReport($exception));
    }

    public function exceptionProvider()
    {
        return [
            [Mockery::mock(AuthorizationException::class), false],
            [Mockery::mock(HttpException::class), false],
            [Mockery::mock(ModelNotFoundException::class), false],
            [Mockery::mock(ValidationException::class), false],
            [Mockery::mock(EntityNotFoundException::class), false],
            [Mockery::mock(Exception::class), true]
        ];
    }
}