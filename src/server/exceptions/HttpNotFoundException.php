<?php

namespace angels2\auth\server\exceptions;

use Throwable;

class HttpNotFoundException extends AuthException
{
    public function __construct(int $status = 404, string $message = 'Not Found', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($status, $message, $code, $previous);
    }
}