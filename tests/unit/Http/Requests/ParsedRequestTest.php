<?php

namespace Tests\Unit\Http\Requests;


use App\Collections\FilterProcessors\FilterProcessor;
use App\Http\Cursor\CursorEncoder;
use App\Http\Requests\ParsedRequest;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class ParsedRequestTest extends MockeryTestCase
{
    private $sut;
    private $request;
    private $cursorEncoder;

    public function setUp()
    {
        $this->cursorEncoder = Mockery::mock(CursorEncoder::class);
        $this->request = Mockery::mock(Request::class);
        $this->sut = new ParsedRequest($this->request, $this->cursorEncoder);
        parent::setUp();
    }

    public function testGetAfter()
    {
        $this->request->shouldReceive('input')
            ->once()
            ->with(ParsedRequest::PARAM_CURSOR_AFTER, 0)
            ->andReturn('MQ==');
        $this->sut->getAfter();
    }


    public function testGetMaxResults()
    {
        $this->request->shouldReceive('input')
            ->once()
            ->with(ParsedRequest::PARAM_MAX_RESULTS, FilterProcessor::DEFAULT_MAX_RESULTS)
            ->andReturn(FilterProcessor::DEFAULT_MAX_RESULTS);
        $this->sut->getMaxResults();
    }

    public function testGetOrderBy()
    {
        $this->request->shouldReceive('input')
            ->once()
            ->with(ParsedRequest::PARAM_ORDER_BY);
        $this->sut->getOrderBy();
    }
}