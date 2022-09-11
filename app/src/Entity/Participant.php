<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[NotBlank]
    #[Email]
    #[ORM\Column(length: 80, unique: true)]
    private ?string $email = null;

    #[NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\OneToMany(mappedBy: 'participant', targetEntity: QuizSession::class)]
    private Collection $quizSessions;

    public function __construct()
    {
        $this->quizSessions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, QuizSession>
     */
    public function getQuizSessions(): Collection
    {
        return $this->quizSessions;
    }

    public function addQuizSession(QuizSession $quizSession): self
    {
        if (!$this->quizSessions->contains($quizSession)) {
            $this->quizSessions->add($quizSession);
            $quizSession->setParticipant($this);
        }

        return $this;
    }

    public function removeQuizSession(QuizSession $quizSession): self
    {
        if ($this->quizSessions->removeElement($quizSession)) {
            // set the owning side to null (unless already changed)
            if ($quizSession->getParticipant() === $this) {
                $quizSession->setParticipant(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
