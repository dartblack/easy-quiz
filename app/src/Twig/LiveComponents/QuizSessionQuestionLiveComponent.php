<?php

namespace App\Twig\LiveComponents;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Request\QuizSessionQuestionRequest;
use App\Services\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('quiz_question_form')]
class QuizSessionQuestionLiveComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'data')]
    public ?QuizSessionQuestionRequest $formRequest = null;
    private ?string $message = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(QuestionType::class, $this->formRequest);
    }


    #[LiveAction]
    public function save(QuizService $quizService): ?RedirectResponse
    {
        $this->submitForm();

        /** @var QuizSessionQuestionRequest $formRequest */
        $formRequest = $this->getFormInstance()->getData();
        $question = $quizService->getQuestionById($formRequest->getQuestionId());
        $correct = false;
        if ($question->getMode() == Question::MODE_BINARY) {
            if ($formRequest->getAnswer() == $question->isCorrect()) {
                $this->message = 'Correct! ';
                $correct = true;
            } else {
                $this->message = 'Sorry, you are wrong! ';
            }
            $this->message .= 'The right answer is ' . $question->getStringCorrect();
        } else {
            $answer = $quizService->getAnswer($formRequest->getAnswer(), $formRequest->getQuestionId());
            if (null !== $answer) {
                if ($answer->isCorrect()) {
                    $this->message = 'Correct! The right answer is ' . $answer->getText();
                    $correct = true;
                } else {
                    $correctAnswer = $quizService->getCorrectAnswer($formRequest->getQuestionId());
                    $this->message = 'Sorry, you are wrong! The right answer is ' . $correctAnswer->getText();
                }
            }
        }
        $quizSession = $quizService->updateSession($formRequest->getUuid(), $correct);
        if ($quizService->checkLastQuestion($formRequest->getQuestionId(), $quizSession)) {
            $quizService->endSession($quizSession);
            return $this->redirectToRoute('app_index_quiz_session', ['uuid' => $quizSession->getUuid()]);
        }
        return null;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}