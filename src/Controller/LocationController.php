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

        //calcul nombre de jours à mettre dans un service
        $debut = $form->get('debut')->getData();
        $fin = $form->get('fin')->getData();
        $jours = ($fin->diff($debut)->format("%a")) + 1;

        if ($form->isSubmitted() && $form->isValid()) {
            $location->setUser($user)
                    ->setVoiture($voiture)
                    ->setPrix($voiture->getModele()->getPrixMoyen() * $jours) //entre le prix total de la location
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
