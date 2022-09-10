<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\QuizSession;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Repository\QuizSessionRepository;
use App\Services\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_index_')]
class IndexController extends AbstractController
{
    #[Route(name: 'home', methods: ['GET'])]
    public function index(QuizSessionRepository $quizSessionRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'topList' => $quizSessionRepository->getTopMembers()
        ]);
    }

    #[Route('/start-quiz', name: 'start_quiz', methods: ['GET', 'POST'])]
    public function startQuiz(Request $request, ParticipantRepository $participantRepository, QuizService $quizService): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Participant $participant */
            $participant = $form->getData();
            $obj = $participantRepository->findOneBy(['email' => $participant->getEmail()]);
            if (null === $obj) {
                $obj = $participant;
            } else {
                $obj->setFirstName($participant->getFirstName());
                $obj->setLastName($participant->getLastName());
            }
            $participantRepository->add($obj, true);
            $quizSession = $quizService->createQuizSession($obj);
            return $this->redirectToRoute('app_index_quiz_session', ['uuid' => $quizSession->getUuid()]);
        }

        return $this->renderForm('index/start_quiz.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/quiz/{uuid}', name: 'quiz_session', methods: ['GET'])]
    public function quizSession(QuizSession $quizSession, QuizService $quizService): Response
    {
        if (!$quizSession->isFinished() && $quizSession->getStartAt()->getTimestamp() + 300 < time()) {
            $quizService->endSession($quizSession);
        }

        return $this->render('index/quiz_session.html.twig', [
            'quizSession' => $quizSession,
            'componentName' => $quizSession->isFinished() ? 'quiz_result' : 'quiz_session',
        ]);
    }

    #[Route('/restart-quiz/{uuid}', name: 'restart_quiz_session', methods: ['GET'])]
    public function restartQuiz(QuizSession $quizSession, QuizService $quizService): RedirectResponse
    {
        $newSession = $quizService->createQuizSession($quizSession->getParticipant());
        return $this->redirectToRoute('app_index_quiz_session', ['uuid' => $newSession->getUuid()]);
    }
}
