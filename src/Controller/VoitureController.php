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
        $jours = 0;
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
        if ($formDateReservation->isSubmitted() && $formDateReservation->isValid()) {
            $voitures = $paginatorInterface->paginate(
                $repoVoiture->findByDisponibility($location),
                $request->query->getInt('page', 1), /*page number*/
                6 /*limit per page*/
            );
            $jours =$calculService->nombreJours($formDateReservation->get('debut')->getData(), $formDateReservation->get('fin')->getData());
    }   
        
     
        return $this->render('voiture/voitures.html.twig', [
            'voitures' => $voitures,
            'form' => $form->createView(),
            'formDateReservation' => $formDateReservation->createView(),
            'admin' => false,
            'jours' => $jours
        ]);
    }
    #[Route('/client/voiture/{id}', name: 'voiture')]
    public function afficherVoiture(Voiture $voiture): Response
    {

        return $this->render('voiture/voiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(VoitureRepository $repoVoiture): Response
    {
    $repoVoiture->test();
        
        return $this->render('voiture/test.html.twig', [
            
        ]);
    }

}
