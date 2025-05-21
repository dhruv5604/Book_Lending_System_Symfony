<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LibrarianController extends AbstractController
{   
    // #[Route("/","app_homepage")]
    // public function homepage(BookRepository $bookRepository)
    // {
    //     $books = $bookRepository->findAll();

    //     return $this->render('homepage.html.twig',[
    //         "books" => $books
    //     ]);
    // }
}