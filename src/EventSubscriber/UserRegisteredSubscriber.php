<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    public function onUserRegistered($event)
    {
        $this->sendWelcomeEmail($event);
    }

    public function sendWelcomeEmail(UserRegisteredEvent $event)
    {
        $user = $event->getUser();
        $email = [
            'from' => 'contact@sensiotv.io',
            'to' => $user->getEmail(),
            'subject' => 'Beinvenue '. $user->getFirstName() . ' sur SensioTV :)',
            'body' => $user->getFirstName() . ', ravi de savoir que vous avez créé un compte sur notre plateforme !'
        ];
        
        dump($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            'user_registered' => 'onUserRegistered',
        ];
    }
}
