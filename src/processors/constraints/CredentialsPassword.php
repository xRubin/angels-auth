<?php

namespace angels2\auth\processors\constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;

class CredentialsPassword extends Constraint
{
    public $message = 'Invalid Credentials Password.';
}

class CredentialsPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $violations = Validation::createValidator()->validate(
            $value,
            [
                new Constraints\NotBlank(),
                new Constraints\Length(['max' => 255])
            ]
        );

        if ($violations->count()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
