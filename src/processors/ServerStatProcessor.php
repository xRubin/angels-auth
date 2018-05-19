<?php

namespace angels2\auth\server\processors;

use angels2\auth\server\interfaces\ProcessorInterface;
use angels2\auth\server\processors\validators\ServerStatValidator;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;

class ServerStatProcessor implements ProcessorInterface
{
    /** @var \Tarantool\Client\Client */
    private $client;

    /**
     * @param \Tarantool\Client\Client $client
     */
    public function __construct(\Tarantool\Client\Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Tarantool\Client\Client
     */
    public function getClient(): \Tarantool\Client\Client
    {
        return $this->client;
    }

    /**
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function run(HttpRequest $request, HttpResponse $response): void
    {
        (new ServerStatValidator())->validate($request);

        $response->status(200);
        $response->end(json_encode(['result' => [
            'cnt' => $this->getClient()->call('box.space.person:count')->getData()[0],
            'online' => 0,
        ]]));
    }
}

