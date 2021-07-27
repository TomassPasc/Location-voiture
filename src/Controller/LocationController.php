<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Location;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationController extends AbstractController
{
    #[Route('/client/location/{id}', name: 'location')]
    public function index(Voiture $voiture, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser(); 
        $location = new Location;
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $location->setUser($user)
                    ->setVoiture($voiture) 
                    ->setDateCreation(new \DateTime()); //on set l'user actuel et la voiture choisit qu'on a recuppéré grâce à l'id de la voiture.
            $em->persist($location);
            $em->flush();
            return $this->redirectToRoute('voitures');
        }

        return $this->render('location/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
