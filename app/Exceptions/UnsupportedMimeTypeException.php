<?php
declare(strict_types = 1);

namespace App\Exceptions;


use Exception;

class UnsupportedMimeTypeException extends Exception
{
    public function __construct($mimeType, $code = 0, Exception $previous = null)
    {
        $message = 'Unsupported MimeType ' . $mimeType;
        parent::__construct($message, $code, $previous);
    }
}