<?php

namespace App\Database\Exception;

use RuntimeException;
use Throwable;

class DbException extends RuntimeException
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
