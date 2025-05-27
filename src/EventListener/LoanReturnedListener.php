<?php

namespace App\EventListener;

use App\Event\LoanReturnedEvent as EventLoanReturnedEvent;
use Doctrine\ORM\EntityManagerInterface;
use LoanReturnedEvent;
use Psr\Log\LoggerInterface;

class LoanReturnedListener 
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function onLoanReturned(EventLoanReturnedEvent $event): void
    {
        $loan = $event->getLoan();
        $book = $loan->getBook();

        $book->setIsAvailable(1);
        $this->em->flush();

        $this->logger->info('Email sent: Book "' . $book->getTitle() . '" has been returned.');
    }

}