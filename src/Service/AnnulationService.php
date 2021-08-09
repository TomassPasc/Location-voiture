<?php

namespace App\Service;

use App\Entity\Location;
use App\Entity\Annulation;
use Doctrine\ORM\EntityManager;

class AnnulationService{

    public function injectionBdd(Location $location, EntityManager $em)
    {
        $annnulation = new Annulation();
        $annnulation->setNumReservation($location->getId());
        $annnulation->setUser($location->getUser()->getId());
        $annnulation->setVoiture($location->getVoiture()->getId());
        $annnulation->setDebut($location->getDebut());
        $annnulation->setFin($location->getFin());
        $annnulation->setDateAnnulation(new \Datetime());
        $annnulation->setPrix($location->getPrix());
        $annnulation->setStripeToken($location->getStripeToken());
        $annnulation->setIdChargeStripe($location->getIdChargeStripe());
        $em->persist($annnulation);
        $em->flush();   
    }
}