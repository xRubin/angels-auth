<?php

namespace angels2\auth\server;

use Swoole\Http\Request;
use Swoole\Http\Response;
use angels2\auth\server\exceptions;
use angels2\auth\server\interfaces\ProcessorInterface;

class RequestDispatcher
{
    /** @var ProcessorInterface[] */
    private $processors = [];

    /**
     * @param string $path
     * @param ProcessorInterface $processor
     * @return $this
     */
    public function addRoute(string $path, ProcessorInterface $processor): self
    {
        $this->processors[$path] = $processor;
        return $this;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @throws \Exception
     */
    public function run(Request $request, Response $response) :void
    {
        $response->header('Content-Type', 'application/json');

        try {
            if (!array_key_exists($request->server['request_uri'], $this->processors))
                throw new exceptions\HttpNotFoundException();

            $processor = $this->processors[$request->server['request_uri']];
            $processor->run($request, $response);
        } catch (exceptions\AuthException $exception) {
            $response->status($exception->getStatus());
            $response->end((string)$exception);
        }
    }
}