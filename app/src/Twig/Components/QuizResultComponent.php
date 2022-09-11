<?php

namespace App\Twig\Components;

use App\Entity\QuizSession;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('quiz_result')]
class QuizResultComponent
{
    public QuizSession $quizSession;
}