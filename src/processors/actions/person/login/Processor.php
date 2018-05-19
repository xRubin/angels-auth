<?php

namespace angels2\auth\processors\actions\person\login;

use angels2\auth\storage\entities\Person;
use angels2\auth\storage\interfaces\PersonRepositoryInterface;
use angels2\auth\storage\interfaces\SecurityInterface;
use angels2\auth\server\interfaces\ProcessorInterface;
use angels2\auth\processors\exceptions;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;

class Processor implements ProcessorInterface
{
    /** @var SecurityInterface */
    private $security;
    /** @var PersonRepositoryInterface */
    private $personRepository;

    /**
     * @param SecurityInterface $security
     * @param PersonRepositoryInterface $personRepository
     */
    public function __construct(SecurityInterface $security, PersonRepositoryInterface $personRepository)
    {
        $this->security = $security;
        $this->personRepository = $personRepository;
    }

    /**
     * @return SecurityInterface
     */
    protected function getSecurity(): SecurityInterface
    {
        return $this->security;
    }

    /**
     * @return PersonRepositoryInterface
     */
    protected function getPersonRepository(): PersonRepositoryInterface
    {
        return $this->personRepository;
    }

    /**
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function run(HttpRequest $request, HttpResponse $response): void
    {
        $data = (new Validator())->validate($request);

        $person = $this->personRepository->findOne(['login' => $data['credentials']['login']]);
        if (null === $person)
            throw new exceptions\InvalidCredentialsException();

        if (!$this->getSecurity()->validatePassword($data['credentials']['password'], $person->password))
            throw new exceptions\InvalidCredentialsException();

        if (in_array(Person::ROLE_BLOCKED, $person->roles))
            throw new exceptions\ActionUnavailableException();

        $person->session = uniqid();
        $person->save();

        $response->status(200);
        $response->end(json_encode(['result' => [
            'id' => $person->id,
            'session' => $person->session,
        ]]));
    }
}

