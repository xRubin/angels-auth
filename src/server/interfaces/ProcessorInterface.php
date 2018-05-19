<?php

namespace angels2\auth\server\interfaces;

use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;

interface ProcessorInterface
{
    /**
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function run(HttpRequest $request, HttpResponse $response);
}