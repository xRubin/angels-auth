<?php

namespace angels2\auth\processors\exceptions;

use angels2\auth\server\exceptions\AuthException;
use Throwable;

class ActionUnavailableException extends AuthException
{
    public function __construct(int $status = 403, string $message = 'Action Unavailable', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($status, $message, $code, $previous);
    }
}