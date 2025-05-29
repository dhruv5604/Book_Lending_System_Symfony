<?php

namespace App\Event;

use App\Entity\Loan;
use Symfony\Contracts\EventDispatcher\Event;

class LoanReturnedEvent extends Event
{
    public const NAME = 'loan.returned';
    private Loan $loan;
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }
   
    public function getLoan()
    {
        return $this->loan;
    }
}