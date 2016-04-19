<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\Console\Application as ConsoleApplication;

class Handler implements ExceptionHandler
{
    /**
     * @var Reporter
     */
    protected $reporter;
    /**
     * @var Renderer
     */
    protected $renderer;

    public function __construct(Reporter $reporter, Renderer $renderer)
    {
        $this->reporter = $reporter;
        $this->renderer = $renderer;
    }

    public function report(Exception $exception)
    {
        $this->reporter->exception($exception);
    }

    public function render($request, Exception $e) : Response
    {
        return $this->renderer->getExceptionResponse($e, $request);
    }

    public function renderForConsole($output, Exception $e)
    {
        (new ConsoleApplication)->renderException($e, $output);
    }
}
