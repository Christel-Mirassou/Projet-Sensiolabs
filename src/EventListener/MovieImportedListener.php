<?php

namespace App\EventListener;

use App\Entity\Movie;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieImportedListener
{
   public function postPersist(Movie $movie, LifecycleEventArgs $event): void
   {
      $movie = $event->getObject();

      $email = [
         'to' => 'member@sensiotv.io',
         'title' => 'Un nouveau film ' . $movie->getTitle() . 'vient d\'arriver'
      ];
      dump($email);
   }

}
