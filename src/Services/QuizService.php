<?php

namespace App\Services;

use App\Entity\Answer;
use App\Entity\Participant;
use App\Entity\Question;
use App\Entity\QuizSession;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizSessionRepository;
use Symfony\Component\Uid\Uuid;

class QuizService
{
    public function __construct(
        private readonly QuizSessionRepository $quizSessionRepository,
        private readonly QuestionRepository $questionRepository,
        private readonly AnswerRepository $answerRepository
    )
    {
    }

    public function createQuizSession(Participant $participant): QuizSession
    {
        $quizSession = new QuizSession();
        $quizSession->setUuid(Uuid::v4());
        $quizSession->setParticipant($participant);
        $quizSession->setFinished(false);
        $quizSession->setUnanswered(10);
        $quizSession->setScore(0);
        $quizSession->setBestResult(false);
        $quizSession->setStartAt(new \DateTimeImmutable());
        $quizSession->setQuestionIds($this->getRandomQuizSessionQuestionIds());
        $this->quizSessionRepository->add($quizSession, true);

        return $quizSession;
    }

    public function updateSession(string $uuid, bool $correct): ?QuizSession
    {
        $quizSession = $this->quizSessionRepository->findOneBy(['uuid' => $uuid]);
        if ($correct) {
            $quizSession->setScore($quizSession->getScore() + 1);
        }
        $quizSession->setUnanswered($quizSession->getUnanswered() - 1);
        $this->quizSessionRepository->add($quizSession, true);
        return $quizSession;
    }

    public function endSession(QuizSession $quizSession): void
    {
        $participant = $quizSession->getParticipant();
        $sessions = $participant->getQuizSessions();

        if ($sessions->count() == 1) {
            $quizSession->setBestResult(true);
        } else {
            $lastBestResult = $this->getParticipantBestResult($participant);
            if ($quizSession->getScore() > $lastBestResult->getScore()) {
                $quizSession->setBestResult(true);
            } else if ($quizSession->getScore() == $lastBestResult->getScore()) {
                if ($quizSession->getQuizTime() < $lastBestResult->getQuizTime()) {
                    $quizSession->setBestResult(true);
                }
            }
        }
        $quizSession->setFinished(true);
        $quizSession->setEndAt(new \DateTimeImmutable());
        $quizSession->setQuizTime($quizSession->getEndAt()->getTimestamp() - $quizSession->getStartAt()->getTimestamp());
        $this->quizSessionRepository->add($quizSession, true);
    }

    public function getParticipantBestResult(Participant $participant): ?QuizSession
    {
        return $this->quizSessionRepository->findOneBy(['finished' => true, 'bestResult' => true, 'participant' => $participant]);
    }

    public function getRandomQuizSessionQuestionIds(): array
    {
        return $this->questionRepository->getQuestionIds();
    }

    public function getQuestionById(int $id): ?Question
    {
        return $this->questionRepository->find($id);
    }

    public function getAnswer(int $answerId, int $questionId): ?Answer
    {
        return $this->answerRepository->findOneBy(['id' => $answerId, 'question' => $questionId]);
    }

    public function getCorrectAnswer(int $questionId): ?Answer
    {
        return $this->answerRepository->findOneBy(['correct' => true, 'question' => $questionId]);
    }

    public function checkLastQuestion(int $questionId, QuizSession $quizSession): bool
    {
        $ids = $quizSession->getQuestionIds();
        return $questionId == $ids[count($ids) - 1];
    }
}