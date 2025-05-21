<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTime $loanedAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dueAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $returnedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLoanedAt(): ?\DateTime
    {
        return $this->loanedAt;
    }

    public function setLoanedAt(\DateTime $loanedAt): static
    {
        $this->loanedAt = $loanedAt;

        return $this;
    }

    public function getDueAt(): ?\DateTime
    {
        return $this->dueAt;
    }

    public function setDueAt(\DateTime $dueAt): static
    {
        $this->dueAt = $dueAt;

        return $this;
    }

    public function getReturnedAt(): ?\DateTime
    {
        return $this->returnedAt;
    }

    public function setReturnedAt(?\DateTime $returnedAt): static
    {
        $this->returnedAt = $returnedAt;

        return $this;
    }
}
