<?php

namespace App\Command;

use App\Entity\Movie;
use App\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:movie:import',
    description: 'Add a short description for your command',
)]
class MovieImportCommand extends Command
{
    private OmdbApi $omdbApi;
    private EntityManagerInterface $manager;

    public function __construct(OmdbApi $omdbApi, EntityManagerInterface $manager)
    {
        $this->omdbApi = $omdbApi;
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('keyword', 'k', InputOption::VALUE_REQUIRED, 'Mot-clé de recherche de films pour l\'import en BDD')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $keyword = $input->getOption(('keyword'));
        $movies = $this->omdbApi->requestAllBySearch($keyword);
        // dump($movies);

        $io->title("# Films a importer en BDD ayant le mot-clé : " . $keyword);

        //Initialisation de la progressbar en console
        $io->progressStart(count(($movies['Search'])));  

        foreach ($movies['Search'] as $movieData) {
            $movieData = $this->omdbApi->requestOneById($movieData['imdbID']);

            //Ne marche pas pour moi car sur Windows
            // if (!$keyword) {
            //     $keyword = $io->ask('🧐 Vous avez oublie de preciser le mot-cle, faites le maintenant !', 'HarryPotter');
            // }
            
            $movie = Movie::fromApi($movieData);
            // dump($movie);
            $this->manager->persist($movie);
            
            //Itération de la progressbar
            $io->progressAdvance(1);

        }
        $this->manager->flush();

        //retour chariot pour supprimer la progressbar une fois la requête terminée
        $output->write("\r");

        //Fin de la progressbar
        // $io->progressFinish();


        $io->success(sprintf('%d films viennent d\'etre importes', count($movies['Search'])));

        return Command::SUCCESS;
    }
}
