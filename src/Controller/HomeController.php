<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // #[Route('/register', name: 'app_register')]
    // public function register(): Response
    // {
    //     return $this->render('authentification/register.html.twig');
    // }

    // #[Route('/connexion', name: 'app_connexion')]
    // public function connexion(): Response
    // {
    //     return $this->render('authentification/connexion.html.twig');
    // }

    // #[Route('/contact', name: 'app_contact')]
    // public function contact(): Response
    // {
    //     return $this->render('authentification/contact.html.twig');
    // }
    // #[Route('/profil', name: 'app_profil')]
    // public function profil(): Response
    // {
    //     return $this->render('authentification/profil.html.twig');
    // }


    // #[Route('/reservez', name: 'app_reservez')]
    // public function reservez(): Response
    // {
    //     return $this->render('authentification/reservez.html.twig');
    // }




}
