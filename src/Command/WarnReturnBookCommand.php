<?php

namespace App\Command;

use App\Entity\User;
use App\Event\OverDueLoanEvent;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:check-overdue-loans', description: 'Checks for users with 2+ overdue loans and dispatches email event.')]
class WarnReturnBookCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email address to check specific user only')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getOption('email');
        if ($email) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$user) {
                $output->writeln('No user found with the provided email address.');
                return Command::FAILURE;
            }
            $users = [$user];
        } else {
            $users = $this->entityManager->getRepository(User::class)->findAll();
        }

        $now = new \DateTime();
        $warningUsers = [];
        foreach ($users as $user) {
            $overdueLoans = 0;
            foreach ($user->getLoans() as $loan) {
                if ($loan->getDueAt() < $now && !$loan->getReturnedAt()) {
                    $interval = $loan->getDueAt()->diff($now);
                    if ($interval->days > 25) {
                        $overdueLoans++;
                    }
                }
            }
            if ($overdueLoans >= 2) {
                $warningUsers[] = $user;
            }
        }
        if (!empty($warningUsers)) {
            $event = new OverDueLoanEvent($warningUsers);
            $this->eventDispatcher->dispatch($event, OverDueLoanEvent::NAME);
            $output->writeln('Warning emails dispatched to users with overdue loans.');
        } else {
            $output->writeln('No users with overdue loans found.');
        }
        $output->writeln('Check completed successfully.');

        return Command::SUCCESS;
    }
}
