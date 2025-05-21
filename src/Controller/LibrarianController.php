<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LibrarianController extends AbstractController
{   
    #[Route("/dashboard","app_dashboard")]
    public function homepage(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render('Librarian/homepage.html.twig',[
            "books" => $books
        ]);
    }

    #[Route('/dashboard/loans','app_dashboard_loans')]
    public function viewLoans(LoanRepository $loanRepository)
    {
        $loans = $loanRepository->findAll();

        return $this->render('Librarian/viewLoans.html.twig',[
            "loans" => $loans
        ]);
    }


}