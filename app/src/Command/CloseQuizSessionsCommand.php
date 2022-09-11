<?php

namespace App\Command;

use App\Services\QuizService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:close-quiz-sessions',
    description: 'Close expired open quiz sessions',
)]
class CloseQuizSessionsCommand extends Command
{

    public function __construct(private readonly QuizService $quizService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Close expired open quiz sessions');

        $sessions = $this->quizService->getExpiredQuizSessions();
        $io->progressStart(count($sessions));
        foreach ($sessions as $session) {
            $this->quizService->endSession($session);
            $io->progressAdvance();
        }
        $io->progressFinish();

        $io->success('Completed!');

        return Command::SUCCESS;
    }
}
