<?php

namespace App\Http\Cursor;

class CursorEncoder implements CursorEncoderInterface
{
    public function encodeCursor($cursor)
    {
        return base64_encode($cursor);
    }

    public function decodeCursor($cursor)
    {
        if(!$cursor) {
            return 0;
        }
        return base64_decode($cursor);
    }
}