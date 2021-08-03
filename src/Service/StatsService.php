<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getStats() {
        $users      = $this->getUsersCount();
        $voitures        = $this->getVoituresCount();
        $locations   = $this->getLocationsCount();
        //$comments   = $this->getCommentsCount();

        return compact('users', 'voitures', 'locations');
    }

    public function getUsersCount() {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getVoituresCount() {
        return $this->manager->createQuery('SELECT COUNT(v) FROM App\Entity\Voiture v')->getSingleScalarResult();
    }

    public function getLocationsCount() {
        return $this->manager->createQuery('SELECT COUNT(l) FROM App\Entity\Location l')->getSingleScalarResult();
    }

    // public function getCommentsCount() {
    //     return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    // }

    // public function getAdsStats($direction) {
    //     return $this->manager->createQuery(
    //         'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
    //         FROM App\Entity\Comment c 
    //         JOIN c.ad a
    //         JOIN a.author u
    //         GROUP BY a
    //         ORDER BY note ' . $direction
    //     )
    //     ->setMaxResults(5)
    //     ->getResult();
    // }

}