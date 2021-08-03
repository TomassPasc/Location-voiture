<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $users      = $this->getUsersCount();
        $voitures        = $this->getVoituresCount();
        $locations   = $this->getLocationsCount();
        //$comments   = $this->getCommentsCount();

        return compact('users', 'voitures', 'locations');
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getVoituresCount()
    {
        return $this->manager->createQuery('SELECT COUNT(v) FROM App\Entity\Voiture v')->getSingleScalarResult();
    }

    public function getLocationsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(l) FROM App\Entity\Location l')->getSingleScalarResult();
    }

    // public function getCommentsCount() {
    //     return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    // }

    public function getLocationFuturStats()
    {
        return $this->manager->createQuery(
            'SELECT l
            FROM App\Entity\Location l 
            WHERE l.debut > :now
            ORDER BY l.debut'
        )
            ->setParameter('now', new \DateTime('now'))
            ->getResult();
    }

    public function getLocationPasseStats()
    {
        return $this->manager->createQuery(
            'SELECT l
            FROM App\Entity\Location l 
            WHERE l.fin < :now
            ORDER BY l.debut DESC'
        )
            ->setParameter('now', new \DateTime('now'))
            ->getResult();
    }

    public function getLocationPresenteStats()
    {


        return $this->manager->createQuery(
            'SELECT l
            FROM App\Entity\Location l 
            WHERE :now BETWEEN l.debut AND l.fin
            ORDER BY l.debut'
        )->setParameter('now', new \DateTime('now'))
        ->getResult();

    }
}
