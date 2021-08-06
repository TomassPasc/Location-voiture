<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Manager\StripeManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestStripeController extends AbstractController
{
    #[Route('/client/payment/{id}/show', name: 'payment', methods:['GET','POST'])]
    public function payment(Voiture $voiture, StripeManager $stripeManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('test_stripe/payment.html.twig', [
            'user' => $this->getUser(),
            'intentSecret' => $stripeManager->intentSecret($voiture),
            'voiture' => $voiture
        ]);
    }


    #[Route('/client/subscription/{id}/paiement/load', name: 'subscription_paiement', methods: ['GET', 'POST'])]
    public function subscription(Voiture $voiture, Request $request, StripeManager $stripeManager) 
    {
        $user = $this->getUser();

         $session = $request->getSession();
         $reservations = $session->get('reservations');

        if ($request->getMethod() === "POST") {
            $resource = $stripeManager->stripe($_POST, $voiture);

            if (null !== $resource) {
                $stripeManager->create_subscription($resource, $voiture, $user, $reservations);

                return $this->render('test_stripe/response.html.twig', [
                    'voiture' => $voiture
                ]);
            }
        }

        return $this->redirectToRoute('payment', ['id' => $$voiture->getId()]);
    }




}
