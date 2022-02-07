<?php

namespace App\Controller;

use App\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

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
    public function search(Request $request): Response
    {
        $keyword = $request->query->get('keyword', 'Sun');
        $omdbApi = new OmdbApi(HttpClient::create() ,'28c5b7b1', 'https://www.omdbapi.com');
        $movies = $omdbApi->requestAllBySearch($keyword);
        dump($omdbApi, $movies);

        return $this->render('movie/search.html.twig', [
            'movies' => $movies,
            'keyword' => $keyword,
        ]);
    }

    #[Route('/movie/{id}', name: 'movie_show', requirements: ['id' => "\d+"])]
    public function show(int $id): Response
    {
        return $this->render('movie/show.html.twig', [
            'id' => $id,
        ]);
    }
}
