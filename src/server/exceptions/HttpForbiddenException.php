<?php

namespace angels2\auth\server\exceptions;

use Throwable;

class HttpForbiddenException extends AuthException
{
    public function __construct(int $status = 403, string $message = 'Forbidden', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($status, $message, $code, $previous);
    }
}