<?php

namespace angels2\auth\processors\constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;

class SourceAddress extends Constraint
{
    public $message = 'Invalid Address.';
}

class SourceAddressValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $violations = Validation::createValidator()->validate(
            $value,
            new Constraints\Collection([
                'fields' => [
                    'ip' => new SourceAddressIp(),
                ],
                'allowExtraFields' => true,
            ])
        );

        if ($violations->count()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}