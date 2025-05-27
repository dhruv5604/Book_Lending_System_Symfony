<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Event\LoanReturnedEvent as EventLoanReturnedEvent;
use App\Repository\BookRepository;
use App\Repository\LoanRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use LoanReturnedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route("/","app_homepage")]
    public function homepage(BookRepository $bookRepository)
    {
        $books = $bookRepository->createQueryBuilder("q")
                    ->where('q.isAvailable=1')
                    ->getQuery()
                    ->getResult();

        return $this->render('homepage.html.twig',[
            "books" => $books
        ]);
    }

    #[Route('/view/borrowedBook/{id}', "app_view_borrowed_books")]
    public function view_borrowed_books($id, BookRepository $bookRepository)
    {
        $borrowed_books = $bookRepository->createQueryBuilder("q")
                            ->join('q.loans',"loans")
                            ->addSelect('loans')
                            ->where('loans.user = :val')
                            ->setParameter('val',$id)
                            ->getQuery()
                            ->getResult();

        return $this->render('user/viewBorrowedBook.html.twig',[
            "borrowed_books" => $borrowed_books
        ]);
    }

    #[Route('/borrowBook/{id}',"app_borrow_book")]
    public function borrowBook($id, BookRepository $bookRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $book = $bookRepository->find($id);
        $user = $security->getUser();

        if (!$book || !$user){
            throw $this->createNotFoundException("Book or User not found");
        }

        $loan = new Loan();
        $loan->setBook($book);
        $loan->setUser($user);
        $loan->setLoanedAt(new DateTime());
        $loan->setDueAt((new DateTime())->modify('+14 days'));
        $loan->setReturnedAt(null);

        $entityManager->persist($loan);
        $book->setIsAvailable(0);
        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }

    #[Route('/returnBook/{id}', "app_return_book")]
    public function returnBook($id, BookRepository $bookRepository, Security $security, EntityManagerInterface $entityManager, LoanRepository $loanRepository,  EventDispatcherInterface $eventDispatcher)
    {
        $book = $bookRepository->find($id);
        $user = $security->getUser();

        $loan = $loanRepository->createQueryBuilder("q")
                        ->where('q.id = :var1')
                        ->setParameter('var1',$id)
                        ->getQuery()
                        ->getOneOrNullResult();

        $loan->setReturnedAt(new DateTime());

        $entityManager->flush();

        $event = new EventLoanReturnedEvent($loan);
        $eventDispatcher->dispatch($event, EventLoanReturnedEvent::NAME);
        
        return $this->redirectToRoute('app_view_borrowed_books',[
            'id' => $user->getId()
        ]);
    }
}