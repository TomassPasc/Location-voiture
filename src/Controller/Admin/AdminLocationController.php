<?php

namespace App\Controller\Admin;

use App\Entity\Location;
use App\Form\AdminLocationType;
use App\Manager\StripeManager;
use App\Repository\LocationRepository;
use App\Service\AnnulationService;
use App\Service\CalculService;
use App\Service\CalendarService;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminLocationController extends AbstractController
{
    #[Route('/admin/location', name: 'admin_location')]
    public function index(LocationRepository $repo, CalendarService $calendarService): Response
    {
        $locations = $repo->findAll();
        $data = $calendarService->parseData($locations, true);
        return $this->render('admin/admin_location/index.html.twig', [
            'locations' => $locations,
            'data' => $data

        ]);
    }

    #[Route('/admin/location/{id}/edit', name: 'admin_location_edit')]
    public function edit(Location $location, Request $request, EntityManagerInterface $em, LocationRepository $repoLocation)
    {
        $form = $this->createForm(AdminLocationType::class, $location);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //verifie si la voiture n'est pas reserve à ces dates. 
            $voitureDispo = $repoLocation->findByDisponibilityForOneCar($location->getDebut(), $location->getFin(), $location->getVoiture(), true, $location->getId());
            if (!$voitureDispo) {
                $this->addFlash('warning', "Cette voiture n'est pas disponible pour ces dates (réservation num {$location->getId()})");
            }
            else{
                $em->persist($location);
                $em->flush();
                $this->addFlash(
                    'success',
                    "La location {$location->getId()} a bien été modifié !"
                );
            }

            return $this->redirectToRoute('admin_location');
        }
        return $this->render('admin/admin_location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/location/{id}/supp', name: 'admin_location_supp')]
    public function supprimer(Location $location, Request $request, EntityManagerInterface $em, StripeManager $stripeManager, AnnulationService $annulation, CalculService $calculService)
    {
        if ($this->isCsrfTokenValid("SUP" . $location->getId(), $request->get("_token"))) {
            $stripeManager->paymentRefund($location, $calculService);

            //injecter dans la table annulation
            $annulation->injectionBdd($location, $em);

            //supprime la location
            $em->remove($location);
            $em->flush();
            $this->addFlash('success', "La suppression a été effectué");
        }
        

        return $this->redirectToRoute("admin_location");
    }
}
