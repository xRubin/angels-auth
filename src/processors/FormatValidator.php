<?php

namespace angels2\auth\processors;

use angels2\auth\processors\interfaces\ValidatorInterface;
use angels2\auth\processors\exceptions\InvalidFormatException;
use Swoole\Http\Request as HttpRequest;
use Symfony\Component\Validator;
use Symfony\Component\Validator\Constraints;

class FormatValidator implements ValidatorInterface
{
    /** @var ValidatorInterface */
    private $validator;

    /**
     * @return Validator\Validator\ValidatorInterface
     */
    protected function getValidator():Validator\Validator\ValidatorInterface
    {
        if (null === $this->validator)
            $this->validator = Validator\Validation::createValidator();

        return $this->validator;
    }

    /**
     * @param HttpRequest $request
     * @return array
     */
    public function validate(HttpRequest $request): array
    {
        if ($this->getValidator()->validate($request->server, new Constraints\Collection([
            'fields' => [
                'request_method' => new Constraints\EqualTo('POST')
            ],
            'allowExtraFields' => true,
        ]))->count())
            throw new InvalidFormatException();

        if ($this->getValidator()->validate($request->header, new Constraints\Collection([
            'fields' => [
                'content-type' => new Constraints\Regex(['pattern' => '/^application\/json(.*)?/'])
            ],
            'allowExtraFields' => true,
        ]))->count())
            throw new InvalidFormatException();

        return [];
    }
}

