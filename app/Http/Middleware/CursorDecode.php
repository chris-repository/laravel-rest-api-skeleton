<?php
declare(strict_types = 1);

namespace App\Http\Middleware;


use App\Http\Cursor\CursorEncoderInterface;
use App\Http\Requests\ParsedRequest;
use Closure;
use Illuminate\Http\Request;

class CursorDecode
{
    /** @var  CursorEncoderInterface */
    private $cursorEncoder;

    /**
     * CursorDecode constructor.
     * @param CursorEncoderInterface $cursorEncoder
     */
    public function __construct(CursorEncoderInterface $cursorEncoder)
    {
        $this->cursorEncoder = $cursorEncoder;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->addDecodedCursor(ParsedRequest::PARAM_CURSOR_AFTER, $request);
        $this->addDecodedCursor(ParsedRequest::PARAM_CURSOR_PREV, $request);
        return $next($request);

    }

    private function addDecodedCursor(string $cursor, Request $request)
    {
        if($requestCursor = $request->input($cursor, false)) {
            $request->query->add([
                $cursor => $this->cursorEncoder->decodeCursor($requestCursor)
            ]);
        }
    }
}