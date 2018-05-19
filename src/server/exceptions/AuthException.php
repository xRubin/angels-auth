<?php

namespace angels2\auth\server\exceptions;

use Throwable;

class AuthException extends \RuntimeException
{
    /** @var int */
    protected $status;

    public function __construct($status = 400, string $message = 'Bad request', int $code = 0, Throwable $previous = null)
    {
        $this->status = $status;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    public function __toString()
    {
        return json_encode([
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'error' => [
                'errors' => [
                    'domain' => 'auth',
                    'reason' => $this->getMessage(),
                    'message' => $this->getMessage(),
                ]
            ],
            'code' => $this->getStatus(),
            'message' => $this->getMessage(),
        ]);
    }
}