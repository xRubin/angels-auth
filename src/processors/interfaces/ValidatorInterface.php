<?php

namespace angels2\auth\processors\interfaces;

use Swoole\Http\Request as HttpRequest;

interface ValidatorInterface
{
    public function validate(HttpRequest $request): array;
}