<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\Table(name: '`movie`')]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'date')]
    private $releaseDate;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private $imdbId;

    #[ORM\Column(type: 'string', length: 255)]
    private $poster;

    #[ORM\Column(type: 'text', nullable: true)]
    private $plot;

    #[ORM\Column(type: 'string', length: 10)]
    private $duration;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $rated;

    #[ORM\Column(type: 'float', nullable: true)]
    private $imdbRating;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getImdbId(): ?string
    {
        return $this->imdbId;
    }

    public function setImdbId(string $imdbId): self
    {
        $this->imdbId = $imdbId;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(?string $plot): self
    {
        $this->plot = $plot;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRated(): ?string
    {
        return $this->rated;
    }

    public function setRated(?string $rated): self
    {
        $this->rated = $rated;

        return $this;
    }

    public function getImdbRating(): ?float
    {
        return $this->imdbRating;
    }

    public function setImdbRating(?float $imdbRating): self
    {
        $this->imdbRating = $imdbRating;

        return $this;
    } 

    public static function fromApi(array $movieInfo): self
    {
        $movie = new self();

        $movie
            ->setTitle($movieInfo['Title'])
            ->setImdbId($movieInfo['imdbID'])
            ->setReleaseDate(new \DateTime($movieInfo['Released']))
            ->setPoster($movieInfo['Poster'])
            ->setPlot($movieInfo['Plot'])
            ->setDuration($movieInfo['Runtime'])

            ->setImdbRating($movieInfo['imdbRating'])
            ->setRated($movieInfo['Rated'])
        ;
            
        return $movie;
    }
}