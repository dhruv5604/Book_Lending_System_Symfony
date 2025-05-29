<?php

namespace App\EventListener;

use App\Event\OverDueLoanEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class OverDueLoanListener
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onOverDueLoan(OverDueLoanEvent $event)
    {
        foreach ($event->getUsersWithOverdues() as $user) {
            $email = (new Email())
                ->from('dhruvsolanki5604@gmail.com')
                ->to($user->getEmail())
                ->subject('Overdue Loan Notification')
                ->text('Hey ' . $user->getName() . ', your loan is overdue. Please take action.');
            
            $this->mailer->send($email);
        }
    }
}