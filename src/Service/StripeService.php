<?php

namespace App\Service;

use Stripe\Stripe;
use App\Entity\Voiture;
use Stripe\StripeClient;

class StripeService
{

    private $privateKey;

    public function __construct()
    {
        if ($_ENV['APP_ENV']  === 'dev') {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
    }
    //associe le prix de la reservation à une clé
    public function paymentIntent(Voiture $voiture)
    {
        $stripe = new StripeClient($this->privateKey);

        return $stripe->paymentIntents->create([
            'amount' => $voiture->getModele()->getPrixMoyen() * 100,
            'currency' => 'eur',
            'payment_method_types' => ['card'],
        ]);
    }

    //déclenche le paiment
    public function paiement($amount, $currency, $description,
    array $stripeParameter){
        $stripe = new StripeClient($this->privateKey);
        $payment_intent = null;

        if (isset($stripeParameter['stripeIntentId'])){
             $payment_intent =$stripe->paymentIntents->retrieve($stripeParameter['stripeIntentId']);
        }
        if($stripeParameter['stripeIntentStatus'] === 'succeeded'){
            //TODO:declencher un listener par exemple
        } else {
            $payment_intent->cancel();
        }
        return $payment_intent;
    }

    public function stripe(array $stripeParameter, Voiture $voiture)
    {
        return $this->paiement(
            $voiture->getModele()->getPrixMoyen() * 100,
            'eur',
            $voiture->getImmatriculation(),
            $stripeParameter
        );
    }
}