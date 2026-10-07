<?php

namespace App\Exceptions;

use Exception;

class InvalidDateQueryException extends Exception
{
    public function __construct($message = 'The date query is invalid.', $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
