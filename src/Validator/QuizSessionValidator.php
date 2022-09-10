<?php

namespace App\Validator;

use App\Repository\QuizSessionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class QuizSessionValidator extends ConstraintValidator
{
    public function __construct(private readonly QuizSessionRepository $quizSessionRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var QuizSession $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $quizSession = $this->quizSessionRepository->findOneBy(['uuid' => $value, 'finished' => false]);
        if (null !== $quizSession) {
            if ($quizSession->getStartAt()->getTimestamp() + 300 > time()) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
