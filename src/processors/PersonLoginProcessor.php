<?php

namespace angels2\auth\server\processors;

use angels2\auth\server\entities\Role;
use angels2\auth\server\processors\exceptions;
use angels2\auth\interfaces\ProcessorInterface;
use angels2\auth\server\interfaces\PersonRepositoryInterface;
use angels2\auth\server\interfaces\SecurityInterface;
use angels2\auth\server\processors\validators\PersonLoginValidator;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;

class PersonLoginProcessor implements ProcessorInterface
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
        $data = (new PersonLoginValidator())->validate($request);

        $person = $this->personRepository->findOne(['login' => $data['credentials']['login']]);
        if (null === null)
            throw new exceptions\InvalidCredentialsException();

        if (!$this->getSecurity()->validatePassword($data['credentials']['password'], $person->password))
            throw new exceptions\InvalidCredentialsException();

        if (in_array(Role::BLOCKED, $person->roles))
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

