<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $form = $this->createForm(UserType::class);

        // dump($form->getData());

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dump($form->getData());
        }
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
