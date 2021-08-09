<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\Location;
use App\Form\LocationType;
use App\Form\ReservationType;
use App\Manager\StripeManager;
use App\Service\CalculService;
use App\Service\MailerService;
use App\Service\StripeService;
use App\Repository\LocationRepository;
use App\Service\AnnulationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationController extends AbstractController
{
    #[Route('/client/location/{id}/payment', name: 'location', methods:['GET','POST'])]
    public function payment(Voiture $voiture, Request $request, LocationRepository $repoLocation, StripeManager $stripeManager): Response
    {
        $session = $request->getSession()->get('reservations');
        $nbreJours = $session['jours'];
        $dateDebut = $session['date_debut'];
        $dateFin = $session['date_fin'];
        $prix = $voiture->getModele()->getPrixMoyen() * $nbreJours;

        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        //verifie si la voiture n'est pas reserve à ces dates. 
        $voitureDispo = $repoLocation->findByDisponibilityForOneCar($dateDebut, $dateFin, $voiture->getId());
        if(!$voitureDispo){
            $this->addFlash('warning', "Cette voiture n'est plus disponible veuillez faire une nouvelle recherche");
            return $this->redirectToRoute('voitures');
        }

        return $this->render('location/payment.html.twig', [
            'user' => $this->getUser(),
            'intentSecret' => $stripeManager->intentSecret($voiture, $prix),
            'voiture' => $voiture,
            'jours' => $nbreJours,
            'prix' => $prix
        ]);
    }

    #[Route('/client/subscription/{id}/paiement/load', name: 'subscriptions_paiement', methods: ['GET', 'POST'])]
    public function subscription(Voiture $voiture, Request $request, StripeManager $stripeManager, MailerService $mailer)
    {
        $user = $this->getUser();

        $session = $request->getSession();
        $reservations = $session->get('reservations');
        $nbreJours = $reservations['jours'];
        $dateDebut = $reservations['date_debut'];
        $dateFin = $reservations['date_fin'];
        $prix = $voiture->getModele()->getPrixMoyen() * $nbreJours;

        if ($request->getMethod() === "POST") {
            $resource = $stripeManager->stripe($_POST, $voiture, $prix);

            if (null !== $resource) {
                $stripeManager->create_subscription($resource, $voiture, $user, $reservations);

                //envoie mail
                $mailer->send(
                    "tho1@free.fr",
                    $user->getEmail(),
                    'votre réservation de location',
                    "location/mail.html.twig",
                    ['voiture' => $voiture,
                     'user' => $user,
                     'prix' => $prix,
                     'dateDebut' => $dateDebut,
                     'dateFin' => $dateFin]
                );

                return $this->render('location/response.html.twig', [
                    'voiture' => $voiture,
                    'prix' => $prix
                ]);
            }
        }
        return $this->redirectToRoute('payment', ['id' => $voiture->getId()]);
    }

    #[Route('/client/location/{id}/show', name: 'location_show')]
    public function afficherVoiture(Location $location, CalculService $calculJour): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        $jours = $calculJour->nombreJours($location->getDebut(), $location->getFin());
       

        return $this->render('location/show.html.twig', [
            'location' => $location,
            'jours' => $jours
        ]);
    }

    #[Route('/client/location/{id}/supp', name: 'client_location_supp')]
    public function supprimer(Location $location, Request $request, EntityManagerInterface $em, StripeService $stripeService, AnnulationService $annulation)
    {
        if ($this->isCsrfTokenValid("SUP" . $location->getId(), $request->get("_token"))) {
            $stripeService->paymentRefund($location);
            //injecter dans la table annulation
            $annulation->injectionBdd($location, $em);

            $em->remove($location);
            $em->flush();
            $this->addFlash('success', "La suppression a été effectué");
        }


        return $this->redirectToRoute("voitures");
    }
}
