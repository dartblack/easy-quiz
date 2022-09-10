<?php

namespace App\Request;

use App\Validator\Question;
use App\Validator\QuizSession;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class QuizSessionQuestionRequest
{
    #[NotBlank]
    #[QuizSession]
    private string $uuid;

    #[NotBlank]
    #[Question]
    private int $questionId;

    #[NotBlank]
    #[Type('integer')]
    private int $answer;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return int
     */
    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    /**
     * @param int $questionId
     */
    public function setQuestionId(int $questionId): void
    {
        $this->questionId = $questionId;
    }

    /**
     * @return int
     */
    public function getAnswer(): int
    {
        return $this->answer;
    }

    /**
     * @param int $answer
     */
    public function setAnswer(int $answer): void
    {
        $this->answer = $answer;
    }

}