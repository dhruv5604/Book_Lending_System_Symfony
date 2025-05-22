<?php

namespace App\Twig;

use DateTime;
use DateTimeInterface;
use Twig\Extension\AbstractExtension as ExtensionAbstractExtension;
use Twig\TwigFilter;

class DaysLeftExtension extends ExtensionAbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('days_left',[$this,'getDaysLeft'])
        ];
    }

    public function getDaysLeft(?DateTimeInterface $dueDate)
    {
        if (!$dueDate) {
            return 'No due Date';
        }

        $now = new DateTime();
        $interval = $now->diff($dueDate);
        $days = (int) $interval->format('%r%a');

        if ($days < 0) {
            return 'OverDue';
        } elseif ($days === 0) {
            return 'Due Today';
        } else {
            return "$days day" . ($days > 1 ? 's' : '') . " left"; 
        }
    }
}