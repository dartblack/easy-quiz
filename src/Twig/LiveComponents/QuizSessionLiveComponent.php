<?php

namespace App\Twig\LiveComponents;

use App\Entity\Question;
use App\Entity\QuizSession;
use App\Request\QuizSessionQuestionRequest;
use App\Services\QuizService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('quiz_session')]
class QuizSessionLiveComponent
{
    use DefaultActionTrait;

    #[LiveProp()]
    public QuizSession $quizSession;

    private int $index = 0;

    public function __construct(private readonly QuizService $quizService)
    {
    }

    public function getCurrentQuestion(): ?Question
    {
        $ids = $this->quizSession->getQuestionIds();
        return $this->quizService->getQuestionById($ids[$this->index]);
    }

    public function getQuizIndex(): int
    {
        return $this->index + 1;
    }

    public function getQuestionRequest(): QuizSessionQuestionRequest
    {
        $request = new QuizSessionQuestionRequest();
        $request->setUuid($this->quizSession->getUuid());
        $request->setQuestionId($this->quizSession->getQuestionIds()[$this->index]);;
        return $request;
    }

    #[LiveAction]
    public function nextQuestion(#[LiveArg] int $index): void
    {
        $total = count($this->quizSession->getQuestionIds());
        if ($index >= $total) {
            $index = $total - 1;
        }
        $this->index = $index;
    }
}