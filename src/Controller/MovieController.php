<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'movie')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    #[Route('/movie/latest', name: 'movie_latest')]
    public function latest(): Response
    {
        return $this->render('movie/latest.html.twig');
    }

    #[Route('/movie/search', name: 'movie_search')]
    public function search(): Response
    {
        return $this->render('movie/search.html.twig');
    }

    #[Route('/movie/{id}', name: 'movie_show', requirements: ['id' => "\d+"])]
    public function show(int $id): Response
    {
        return $this->render('movie/show.html.twig', [
            'id' => $id,
        ]);
    }
}
