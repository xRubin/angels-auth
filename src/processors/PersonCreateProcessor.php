<?php

namespace angels2\auth\server\processors;

use angels2\auth\server\interfaces\ProcessorInterface;
use angels2\auth\server\interfaces\SecurityInterface;
use angels2\auth\server\interfaces\PersonRepositoryInterface;
use angels2\auth\server\processors\validators\PersonCreateValidator;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response as HttpResponse;
use Ramsey\Uuid\Uuid;

class PersonCreateProcessor implements ProcessorInterface
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
        $data = (new PersonCreateValidator())->validate($request);

        $person = $this->getPersonRepository()->create([
            'id' => Uuid::uuid4()->toString(),
            'login' => $data['credentials']['login'],
            'password' => $this->getSecurity()->cryptPassword($data['credentials']['password']),
        ]);

        $person->session = uniqid();
        $person->save();

        $response->status(200);
        $response->end(json_encode(['result' => [
            'id' => $person->id,
            'session' => $person->session,
        ]]));
    }
}



