<?php

namespace App\Exceptions;

use Exception;

class NoContentException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): bool
    {
        return false;
    }
}
