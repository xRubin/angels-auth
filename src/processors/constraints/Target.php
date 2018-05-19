<?php

namespace angels2\auth\processors\constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;

class Target extends Constraint
{
    public $message = 'Invalid Target.';
}

class TargetValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $violations = Validation::createValidator()->validate(
            $value,
            new Constraints\Collection([
                'id' => new UserId(),
            ])
        );

        if ($violations->count()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}