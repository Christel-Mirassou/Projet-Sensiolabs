<?php

namespace App\Controller;

use App\OmdbApi;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class MovieController extends AbstractController
{
    private OmdbApi $omdbApi;

    public function __construct(OmdbApi $omdbApi)
    {
        //appel du nouveau service créé
        $this->omdbApi= $omdbApi; 


        //Plus nécessaire avec la création du nouveau service
        // $this->omdbApi = new OmdbApi(HttpClient::create() ,'28c5b7b1', 'https://www.omdbapi.com');
        
    }

    /**
     * @Route("/movie", name="movie")
     */
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    /**
     * @Route("/movie/latest", name="movie_latest")
     */
    public function latest(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findBy([],['id' => 'DESC']);

        return $this->render('movie/latest.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/movie/search", name="movie_search")
     */
    public function search(Request $request): Response
    {
        $keyword = $request->query->get('keyword', 'Sun');
        $movies = $this->omdbApi->requestAllBySearch($keyword);
        dump($this->omdbApi, $movies);

        return $this->render('movie/search.html.twig', [
            'movies' => $movies,
            'keyword' => $keyword,
        ]);
    }

    /**
     * @Route("/movie/{id}", name="movie_show", requirements={"id": "\d+"})
     */
    public function show(int $id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->findOneById($id);
        if (!$this->isGranted('MOVIE_SHOW', $movie)) {
            throw new AccessDeniedException('You are not allowed to see the movie '. $movie->getTitle());
        }
        return $this->render('movie/show.html.twig', [
            'id' => $id,
            'movie'=> $movie,
        ]);
    }
    
    /**
     * @Route("/movie/{imdbId}/import", name="movie_import")
     * @IsGranted("ROLE_USER")
     */
    public function import($imdbId, EntityManagerInterface $manager): Response
    {
        //Seulement un user connecté en tant qu'admin peut importer un film
        //cette ligne ou l'annotation au-dessus mais pas les 2
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $movieData = $this->omdbApi->requestOneById($imdbId);
        $movie = Movie::fromApi($movieData);
        // dump($movie);
        // die;
        $manager->persist($movie);
        $manager->flush();

        return $this->redirectToRoute('movie_latest');
    }


}
