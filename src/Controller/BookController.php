<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}