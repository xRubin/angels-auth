<?php

namespace angels2\auth\server\processors;

use angels2\auth\processors\exceptions;
use angels2\auth\server\interfaces\PersonRepositoryInterface;
use angels2\auth\server\interfaces\ProcessorInterface;
use angels2\auth\server\processors\validators\PersonGetValidator;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;

class PersonGetProcessor implements ProcessorInterface
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
        $data = (new PersonGetValidator())->validate($request);

        $person = $this->getPersonRepository()->findOne(['id' => $data['target']['id']]);
        if (null === null)
            throw new exceptions\InvalidCredentialsException();

        $response->status(200);
        $response->end(json_encode(['result' => [
            'id' => $person->id,
            'login' => $person->login,
            'source' => $person->source,
            'ip' => $person->ip,
            'session' => $person->session,
            'roles' => $person->roles,
            'created' => $person->created,
        ]]));
    }
}

