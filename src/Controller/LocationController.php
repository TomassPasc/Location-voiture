<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Location;
use App\Form\LocationType;
use App\Form\ReservationType;
use App\Service\CalculService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationController extends AbstractController
{
    #[Route('/client/location/{id}', name: 'location')]
    public function index(Voiture $voiture, Request $request, EntityManagerInterface $em, CalculService $calculService): Response
    {
         $session = $request->getSession();
         $reservations = $session->get('reservations');
        // dd($reservations['date_debut']);
       
        $user = $this->getUser(); 
        $location = new Location;
        $form = $this->createForm(ReservationType::class, $location);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           // dd($voiture);
            //calcul nombre de jours à mettre dans un service
            $jours = $calculService->nombreJours($reservations['date_debut'], $reservations['date_fin']);
            $location->setUser($user)
                    ->setVoiture($voiture)
                    ->setDebut($reservations['date_debut'])
                    ->setFin($reservations['date_fin'])
                    ->setPrix($voiture->getModele()->getPrixMoyen() * $jours) //entre le prix total de la location
                    ->setDateCreation(new \DateTime()); //on set l'user actuel et la voiture choisit qu'on a recuppéré grâce à l'id de la voiture.

            $em->persist($location);
            $em->flush();
            return $this->redirectToRoute('voitures');
        }

        return $this->render('location/index.html.twig', [
            'form' => $form->createView(),
            'reservations' => $reservations,
            'voiture' => $voiture
        ]);
    }
}
