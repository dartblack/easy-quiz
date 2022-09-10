<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Services\QuestionLoadService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class QuestionFixtures extends Fixture
{
    public function __construct(private readonly QuestionLoadService $questionLoadService)
    {
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function load(ObjectManager $manager): void
    {
        $booleanTypeQuestions = $this->questionLoadService->loadQuestions(QuestionLoadService::BOOLEAN_TYPE, 15);
        foreach ($booleanTypeQuestions['results'] as $item) {
            $question = new Question();
            $question->setMode(Question::MODE_BINARY);
            $question->setText(html_entity_decode($item['question']));
            if ($item['correct_answer'] == 'True') {
                $question->setCorrect(true);
            }
            $manager->persist($question);
        }
        $manager->flush();

        $multipleTypeQuestions = $this->questionLoadService->loadQuestions(QuestionLoadService::MULTIPLE_TYPE, 15);
        foreach ($multipleTypeQuestions['results'] as $item) {
            $question = new Question();
            $question->setMode(Question::MODE_CHOICE);
            $question->setText(html_entity_decode($item['question']));
            $correctAnswer = new Answer();
            $correctAnswer->setCorrect(true);
            $correctAnswer->setText(html_entity_decode($item['correct_answer']));
            $question->addAnswer($correctAnswer);
            for ($i = 0; $i < 2; $i++) {
                $answer = new Answer();
                $answer->setText(html_entity_decode($item['incorrect_answers'][$i]));
                $question->addAnswer($answer);
            }
            $manager->persist($question);
        }
        $manager->flush();
    }
}
