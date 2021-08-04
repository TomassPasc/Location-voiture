<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Location;
use App\Form\LocationType;
use App\Form\ReservationType;
use App\Repository\LocationRepository;
use App\Service\CalculService;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationController extends AbstractController
{
    #[Route('/client/location/{id}', name: 'location')]
    public function index(Voiture $voiture, Request $request, EntityManagerInterface $em, CalculService $calculService, MailerService $mailer, LocationRepository $repoLocation): Response
    {
         $session = $request->getSession();
         $reservations = $session->get('reservations');
  
        $user = $this->getUser(); 
   
        $location = new Location;
        $form = $this->createForm(ReservationType::class, $location);
        $form->handleRequest($request);

        //verifie si la voiture n'est pas reserve à ces dates. 
        $voitureDispo = $repoLocation->findByDisponibilityForOneCar($reservations['date_debut'], $reservations['date_fin'], $voiture->getId());
       
        if(!$voitureDispo){
            $this->addFlash('warning', "Cette voiture n'est plus disponible veuillez faire une nouvelle recherche");
            return $this->redirectToRoute('voitures');
        }
        else{
            if ($form->isSubmitted() && $form->isValid()) {

                //calcul avec le service:
                $jours = $calculService->nombreJours($reservations['date_debut'], $reservations['date_fin']);

                //on ajoute toutes les données nécessaire pour la location
                $location->setUser($user)
                    ->setVoiture($voiture)
                    ->setDebut($reservations['date_debut'])
                    ->setFin($reservations['date_fin'])
                    ->setPrix($voiture->getModele()->getPrixMoyen() * $jours) //entre le prix total de la location
                    ->setDateCreation(new \DateTime()); //on set l'user actuel et la voiture choisit qu'on a recuppéré grâce à l'id de la voiture.

                $em->persist($location);
                $em->flush();

                //message pour le retour à la page principal
                $this->addFlash('success', "Votre réservation a bien été effectué");


                $mailer->send(
                    "thoma1@free.fr",
                    $user->getEmail(),
                    'votre réservation de location',
                    "location/mail.html.twig",
                    [
                        'location' => $location
                    ]
                );


                return $this->redirectToRoute('voitures');
            }
        }

        return $this->render('location/index.html.twig', [
            'form' => $form->createView(),
            'reservations' => $reservations,
            'voiture' => $voiture
        ]);
    }
}
