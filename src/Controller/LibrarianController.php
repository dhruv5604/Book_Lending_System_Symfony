<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormTypeForm;
use App\Repository\BookRepository;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BookType;
use App\Form\BookTypeForm;

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

    #[Route('add','app_add_book')]
    public function addBook(Request $request, EntityManagerInterface $entityManager)
    {
        $book = new Book();
        $form = $this->createForm(BookTypeForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('book/add.html.twig',[
            "form" => $form->createView()
        ]);
    }

    #[Route('/edit/{id}',"app_edit_book")]
    public function editBook(Book $book, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(BookTypeForm::class,$book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('book/add.html.twig',[
            "form" => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', 'app_delete_book')]
    public function delete(Book $book, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard');
    }

}