<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Location;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use App\Service\CalculService;

class VoitureController extends AbstractController
{
    #[Route('/client/voitures', name: 'voitures')]
    public function afficherVoitures(VoitureRepository $repoVoiture, LocationRepository $repoLocation, PaginatorInterface $paginatorInterface, Request $request, CalculService $calculService): Response
    {
        $session = $request->getSession();
        $reservations = $session->get('reservations', []);
        //$reservations['jours'] = 0;
        $session->set('reservations', $reservations);
        //dd($session->get('reservations'));
        // $jours = 0;
       // $jours = $request->query->get('jours');
        //dd($jours);
        //recherche voiture par année
        $rechercheVoiture = new RechercheVoiture();
        $form = $this->createForm(RechercheVoitureType::class, $rechercheVoiture);
        $form->handleRequest($request);

        $voitures = $paginatorInterface->paginate(
            $repoVoiture->findAllWithPagination($rechercheVoiture),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        //recherche voiture par disponibilité(non louée)
        $location = new Location();
        $formDateReservation = $this->createForm(LocationType::class, $location);
        $formDateReservation->handleRequest($request);


        return $this->render('voiture/voitures.html.twig', [
            'voitures' => $voitures,
            'form' => $form->createView(),
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
        $voitures = $paginatorInterface->paginate(
            $repoVoiture->findByDisponibility($location),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        $jours = $calculService->nombreJours($formDateReservation->get('debut')->getData(), $formDateReservation->get('fin')->getData());
        $reservations['jours'] = $jours;
        $reservations['date_debut'] = $formDateReservation->get('debut')->getData();
        $reservations['date_fin'] = $formDateReservation->get('fin')->getData();
        $session->set('reservations', $reservations);
       // dd($session->get('reservations'));
        return $this->redirectToRoute( 'voitures');;
    }


    #[Route('/client/voiture/{id}', name: 'voiture')]
    public function afficherVoiture(Voiture $voiture): Response
    {
        return $this->render('voiture/voiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }

}
