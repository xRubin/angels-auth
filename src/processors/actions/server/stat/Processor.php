<?php

namespace angels2\auth\processors\actions\server\stat;

use angels2\auth\server\interfaces\ProcessorInterface;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;
use angels2\auth\storage\interfaces\PersonRepositoryInterface;

class Processor implements ProcessorInterface
{
    /** @var PersonRepositoryInterface */
    private $personRepository;

    /**
     * @param PersonRepositoryInterface $personRepository
     */
    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @return PersonRepositoryInterface
     */
    public function getPersonRepository(): PersonRepositoryInterface
    {
        return $this->personRepository;
    }

    /**
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function run(HttpRequest $request, HttpResponse $response): void
    {
        (new Validator())->validate($request);

        $response->status(200);
        $response->end(json_encode(['result' => [
            'cnt' => $this->getPersonRepository()->getCount(),
            'online' => 0,
        ]]));
    }
}

