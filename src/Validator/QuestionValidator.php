<?php

namespace App\Validator;

use App\Repository\QuestionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class QuestionValidator extends ConstraintValidator
{
    public function __construct(private readonly QuestionRepository $questionRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var Question $constraint */

        if (null === $value) {
            return;
        }

        $question = $this->questionRepository->find($value);
        if (null !== $question) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
