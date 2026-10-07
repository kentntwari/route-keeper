<?php

namespace App\Exceptions;

use Exception;

class AvoidDuplicateStopException extends Exception
{
    public function __construct($message = 'A stop has already been created today.', $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
