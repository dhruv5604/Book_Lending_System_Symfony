<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class OverDueLoanEvent extends Event
{
    public const NAME = 'loan.overdue';

    private array $usersWithOverdues;

    public function __construct(array $usersWithOverdues)
    {
        $this->usersWithOverdues = $usersWithOverdues;
    }

    public function getUsersWithOverdues(): array
    {
        return $this->usersWithOverdues;
    }
}
