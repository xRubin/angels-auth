<?php

namespace angels2\auth\processors\exceptions;

use angels2\auth\server\exceptions\AuthException;
use Throwable;

class InvalidCredentialsException extends AuthException
{
    public function __construct(int $status = 400, string $message = 'Invalid Credentials', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($status, $message, $code, $previous);
    }
}