<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    const MODE_BINARY = 'binary';
    const MODE_CHOICE = 'choice';

    const QUESTION_MODES = [
        'Binary (Yes/No)' => self::MODE_BINARY,
        'Multiple choice' => self::MODE_CHOICE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[NotBlank]
    #[Choice(choices: self::QUESTION_MODES)]
    #[ORM\Column(length: 60)]
    private ?string $mode = self::MODE_BINARY;

    #[NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[Expression('(this.getMode()=="choice" and this.getAnswers().count()==3) or this.getMode()=="binary"',
        message: 'When chosen "Multiple Choice" answers numbers must be 3')]
    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $answers;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $correct = false;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->text;
    }

    public function isCorrect(): ?bool
    {
        return $this->correct;
    }

    public function getStringCorrect(): ?string
    {
        return $this->isCorrect() ? 'True' : 'False';
    }

    public function setCorrect(bool $correct): self
    {
        $this->correct = $correct;

        return $this;
    }
}
