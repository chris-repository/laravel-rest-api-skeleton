<?php

namespace App\Http\Requests;

use App\Collections\FilterProcessors\FilterProcessor;
use App\Http\Cursor\CursorEncoder;
use Illuminate\Http\Request;

class ParsedRequest
{

    //Available parameters
    const PARAM_CURSOR_AFTER = 'after';
    const PARAM_CURSOR_PREV = 'prev';
    const PARAM_MAX_RESULTS = 'max_results';
    const PARAM_ORDER_BY = 'order_by';


    /**
     * @var Request
     */
    private $request;

    /**
     * @var CursorEncoder
     */
    private $cursorEncoder;

    /**
     * RequestParser constructor.
     * @param Request $request
     * @param CursorEncoder $cursorHandler
     */
    public function __construct(Request $request, CursorEncoder $cursorHandler)
    {
        $this->request = $request;
        $this->cursorEncoder = $cursorHandler;
    }

    /**
     * @return array|string
     */
    public function getAfter()
    {
        return $this->request->input(self::PARAM_CURSOR_AFTER, 0);
    }

    /**
     * @return array|string
     */
    public function getMaxResults()
    {
        return $this->request->input(self::PARAM_MAX_RESULTS, FilterProcessor::DEFAULT_MAX_RESULTS);
    }

    /**
     * @return array|string
     */
    public function getOrderBy()
    {
        return $this->request->input(self::PARAM_ORDER_BY);
    }

    /**
     * @return Request
     */
    public function getRequest() : Request
    {
        return $this->request;
    }

    public function getPrev()
    {
        return $this->request->input(self::PARAM_CURSOR_PREV);
    }
}