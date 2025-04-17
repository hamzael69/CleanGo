<?php

namespace App\Controller;

use App\Entity\CleaningRequest;
use App\Form\CleaningRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CleaningRequestController extends AbstractController
{
    #[Route('/cleaning-request/create', name: 'cleaning_request_create')]
    public function create(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $cleaningRequest = new CleaningRequest();
        $form = $this->createForm(CleaningRequestType::class, $cleaningRequest);
        $form->handleRequest($request);

        /**
         * @var \App\Entity\User $user
         */
        $user = $security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $cleaningRequest->setClient($user->getClient());
            $cleaningRequest->setIsAccepted(false);

            $em->persist($cleaningRequest);
            $em->flush();

            return $this->redirectToRoute('app_client_index'); // à adapter
        }

        return $this->render('cleaning_request/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
