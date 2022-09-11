<?php

namespace App\Entity;

use App\Repository\QuizSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizSessionRepository::class)]
class QuizSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $finished = false;

    #[ORM\ManyToOne(inversedBy: 'quizSessions')]
    private ?Participant $participant = null;

    #[ORM\Column(length: 80, unique: true)]
    private ?string $uuid = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column]
    private ?int $unanswered = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $quizTime = null;

    #[ORM\Column(type: Types::JSON)]
    private array $questionIds = [];

    #[ORM\Column]
    private ?bool $bestResult = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getUnanswered(): ?int
    {
        return $this->unanswered;
    }

    public function setUnanswered(int $unanswered): self
    {
        $this->unanswered = $unanswered;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuizTime(): ?int
    {
        return $this->quizTime;
    }

    public function getQuizTimeString(): string
    {
        $interval = date_diff($this->getStartAt(), $this->getEndAt());
        return $interval->format('%H:%I:%S');
    }

    public function setQuizTime(?int $quizTime): self
    {
        $this->quizTime = $quizTime;

        return $this;
    }

    public function getQuestionIds(): array
    {
        return $this->questionIds;
    }

    public function setQuestionIds(array $questionIds): self
    {
        $this->questionIds = $questionIds;

        return $this;
    }

    public function isBestResult(): ?bool
    {
        return $this->bestResult;
    }

    public function setBestResult(bool $bestResult): self
    {
        $this->bestResult = $bestResult;

        return $this;
    }
}
