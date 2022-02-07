<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        $form = $this->createForm(UserType::class);

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('user/signin.html.twig');
    }
}
