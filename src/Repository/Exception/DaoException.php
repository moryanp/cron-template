<?php

namespace App\Repository\Exception;

use RuntimeException;
use Throwable;

class DaoException extends RuntimeException
{

    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
