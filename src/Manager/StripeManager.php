<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Voiture;
use App\Entity\Location;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;

class StripeManager
{
    protected $em;
    protected $stripeService;


    public function __construct(StripeService $stripeService, EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

    public function intentSecret(Voiture $voiture, $prix)
    {
        $intent = $this->stripeService->paymentIntent($voiture, $prix);

        return $intent['client_secret'] ?? null; //quand c'est bon il retourne un client secret sinon il retourne null
    }

    public function stripe( array $stripeParameter, Voiture $voiture, $prix)
    {
        $ressource = null;
        $data = $this->stripeService->stripe($stripeParameter, $voiture, $prix);

        if($data){
            $ressource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['status'],
                'stripeToken' => $data['client_secret']
            ];
        }

        return $ressource;
    }

    public function create_subscription(array $resource, Voiture $voiture, User $user, $session)
    {
        //avec l'injection request
        // $session = $request->getSession();
        // $reservations = $session->get('reservations');

        $location = new Location();
        $location->setUser($user);
        $location->setVoiture($voiture);
        $location->setPrix($voiture->getModele()->getPrixMoyen() * $session['jours']);
        $location->setDebut($session['date_debut']);
        $location->setFin($session['date_fin']);
        $location->setDateCreation(new \Datetime());
        $location->setBrandStripe($resource['stripeBrand']);
        $location->setLast4Stripe($resource['stripeLast4']);
        $location->setIdChargeStripe($resource['stripeId']);
        $location->setStripeToken($resource['stripeToken']);
        $location->setStatusStripe($resource['stripeStatus']);
        $this->em->persist($location);
        $this->em->flush();
        
    }

        




}
