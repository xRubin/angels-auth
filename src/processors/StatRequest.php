<?php

namespace angels2\auth\requests;

use angels2\auth\RequestDispatcher;
use angels2\auth\requests\validators\StatValidator;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;

class StatRequest extends AbstractProcessor
{
    /**
     * @param RequestDispatcher $dispatcher
     */
    public function __construct(RequestDispatcher $dispatcher)
    {
        parent::__construct($dispatcher, new StatValidator());
    }

    /**
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function run(HttpRequest $request, HttpResponse $response): void
    {
        $response->status(200);
        $response->end(json_encode(['result' => [
            'cnt' => $this->dispatcher->getPersonRepository()->getMapper()->getClient()->call('box.space.person:count')->getData()[0],
            'online' => 0,
        ]]));
    }
}

