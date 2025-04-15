<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
final class ClientController extends AbstractController{
    #[Route(name: 'app_client_index', methods: ['GET', 'POST'])]
    public function index(ClientRepository $clientRepository, EntityManagerInterface $entityManager, Request $request): Response
    {

        /**
         * @var User $user
         */
        $user = $this->getUser();

        


        if(!$user ){
            return $this->redirectToRoute('app_login');
        }

        $client = $user->getClient();
 
        if (!$client) {
            $client = new Client();
            $client->setUser($user);
            $entityManager->persist($client);
            $entityManager->flush();
        }


        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setUser($user); // Ajouter cette ligne pour associer l'utilisateur
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/index.html.twig', [
            'form' => $form,
        ]);
    }


}
