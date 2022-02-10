<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{

    const BLACKLISTED_WORDs = ['shit', 'fuck'];

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['MOVIE_SHOW'])
            && $subject instanceof \App\Entity\Movie;
    }

    protected function voteOnAttribute(string $attribute, $movie, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        foreach (self::BLACKLISTED_WORDs as $word) {
            //on interdit l'accès lorsqu'un mot clé est touvé dans le titre
            if (false !== strpos(strtolower($movie->getTitle()), $word)) {
                return false;
            }
        }
        

        return true;
    }
}
