<?php

namespace App\Http\Cursor;

interface CursorEncoderInterface
{
    /**
     * encode a cursor value to be returned
     * @param $cursor
     * @return mixed
     */
    public function encodeCursor($cursor);

    /**
     * decode a previously encoded cursor
     * @param $cursor
     * @return mixed
     */
    public function decodeCursor($cursor);
}