<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\ORM\Query;
use App\Entity\Location;
use App\Entity\RechercheVoiture;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    public function findAllWithPagination(RechercheVoiture $rechercheVoiture) : Query
    {
        $req = $this->createQueryBuilder('v');
        if($rechercheVoiture->getMinAnnee()){
            $req = $req->andWhere('v.annee >= :min')
            ->setParameter(':min', $rechercheVoiture->getMinAnnee());
        }
        if ($rechercheVoiture->getMaxAnnee()) {
            $req = $req->andWhere('v.annee <= :max')
            ->setParameter(':max', $rechercheVoiture->getMaxAnnee());
        }
        return $req->getQuery();

    }

    public function findByDisponibility($debut, $fin, LocationRepository $locationRepository)
    {
        //recupère les id des voitures louees sous formes de tableau
        $voituresLoueesId = $locationRepository->findByCarRentId($debut, $fin);
        //si le tableau est vide = pas de voitures louees on envoie toutes les voitures
         if (empty($voituresLoueesId)){
              return $this->findAll();
          }
          else{
            $qb2 = $this->createQueryBuilder('v');
            $req =  $qb2
                ->select('v')
                ->where($qb2->expr()->notIn('v.id', $voituresLoueesId))
                ->getQuery()
                ->getResult();
            return $req;
          }
    }

    // /**
    //  * @return Voiture[] Returns an array of Voiture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voiture
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
