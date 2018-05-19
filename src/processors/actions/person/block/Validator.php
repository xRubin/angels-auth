<?php

namespace angels2\auth\processors\actions\person\block;

use angels2\auth\processors\exceptions\InvalidFormatException;
use angels2\auth\processors\FormatValidator;
use Swoole\Http\Request as HttpRequest;
use angels2\auth\processors\constraints;
use Symfony\Component\Validator\Constraints\Collection;

class Validator extends FormatValidator
{
    /**
     * @param HttpRequest $request
     * @return array
     */
    public function validate(HttpRequest $request): array
    {
        parent::validate($request);

        $data = json_decode($request->rawcontent(), true);

        $violations = $this->getValidator()->validate($data, new Collection([
            'fields' => [
                'target' => new constraints\Target(),
            ],
            'allowExtraFields' => true,
        ]));

        if ($violations->count()) {
            /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
            foreach ($violations as $violation)
                throw new InvalidFormatException(400, $violation->getMessage());
        }

        return $data;
    }
}

