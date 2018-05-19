<?php

namespace angels2\auth\processors;

use angels2\auth\entities\Role;
use angels2\auth\exceptions;
use angels2\auth\interfaces\PersonRepositoryInterface;
use angels2\auth\interfaces\ProcessorInterface;
use angels2\auth\RequestDispatcher;
use angels2\auth\requests\validators\BlockValidator;
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
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function run(HttpRequest $request, HttpResponse $response): void
    {
        $data = $this->validator->validate($request);

        $person = $this->dispatcher->getPersonRepository()->findOne(['id' => $data['target']['id']]);
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

