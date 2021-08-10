<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Location;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use App\Service\CalculService;
use App\Service\CalendarService;


class VoitureController extends AbstractController
{
    #[Route('/', name: 'voitures')]
    public function afficherVoitures(VoitureRepository $repoVoiture, PaginatorInterface $paginatorInterface, Request $request, LocationRepository $locationRepository): Response
    {
        $session = $request->getSession();
        $reservations = $session->get('reservations', []);
        $session->set('reservations', $reservations);

        
        //recherche voiture par disponibilité(non louée)
        $location = new Location();
        $formDateReservation = $this->createForm(LocationType::class, $location);

        //check si il y a déjà eu une recherche effectuée si ce n'est pas le cas envoie toutes les voitures
        if(!isset($reservations['date_debut']) or !isset($reservations['date_fin'])){
            $requete = $repoVoiture->findAll();
        } else {
            $requete = $repoVoiture->findByDisponibility($reservations['date_debut'], $reservations['date_fin'], $locationRepository);
        };

        $voitures = $paginatorInterface->paginate(
            $requete,
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        return $this->render('voiture/voitures.html.twig', [
            'voitures' => $voitures,
            'formDateReservation' => $formDateReservation->createView(),
            'admin' => false,
            'reservations' => $reservations
        ]);
    }

    #[Route('/client/voitures/form', name: 'date_traitement')]
    public function formTraitement(CalculService $calculService,  PaginatorInterface $paginatorInterface, VoitureRepository $repoVoiture, Request $request): Response
    {    
        $session = $request->getSession();
        $reservations = $session->get('reservation');
        $location = new Location();
        $formDateReservation = $this->createForm(LocationType::class, $location);
        $formDateReservation->handleRequest($request);
        $jours = $calculService->nombreJours($formDateReservation->get('debut')->getData(), $formDateReservation->get('fin')->getData());
        $reservations['jours'] = $jours;
        $reservations['date_debut'] = $formDateReservation->get('debut')->getData();
        $reservations['date_fin'] = $formDateReservation->get('fin')->getData();
        $session->set('reservations', $reservations);
        return $this->redirectToRoute( 'voitures');
    }


    #[Route('/client/voiture/{id}/show', name: 'voiture')]
    public function afficherVoiture(Voiture $voiture, Request $request, LocationRepository $repoLocation, CalendarService $calendarService): Response
    {
        //session pour afficher le prix total si il y a déjà une requête effectué
        $session = $request->getSession();
        $reservations = $session->get('reservations', []);

        //on réccupère les locations que de la voiture choisi
        //TODO: recuperer que les locations future
        $locationsVoiture = $repoLocation->findBy(['voiture' => $voiture->getId()]);

        //on parse les données en json pour les transmettre au calendrier
        $data = $calendarService->parseData($locationsVoiture);
        
        return $this->render('voiture/voiture.html.twig', [
            'voiture' => $voiture,
            'reservations' => $reservations,
            'data' => $data
        ]);
    }
}
