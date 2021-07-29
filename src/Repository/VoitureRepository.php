<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\ORM\Query;
use App\Entity\Location;
use App\Entity\RechercheVoiture;
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

    public function findByDateReservation(Location $location): Query
    {
        $req = $this->createQueryBuilder('v');
        $sub = $req->join('v.locations','l')
                    ->andWhere('l.debut > :debut')
                    ->setParameter(':debut', $location->getDebut())
                    ->andWhere('l.fin > :fin')
                    ->setParameter(':fin', $location->getFin());

        // $req = $this->createQueryBuilder('l');
        // if ($location->getDebut()) {
        //     $req = $req->andWhere('l.debut >= :debut')
        //     ->setParameter(':debut', $location->getDebut());
        // }
        // if ($location->getFin()) {
        //     $req = $req->andWhere('l.fin <= :fin')
        //     ->setParameter(':fin', $location->getFin());
        // }
        // return $req->getQuery();
    }
    public function test(): Query
    {
        

        $qb = $this->_em->createQueryBuilder();

        $not = $qb
        ->select('v')
        ->from('App\Entity\Voiture', 'v')

        ->addSelect('l')
        ->join('v.locations', 'l')
        ->andwhere('l.debut > :debut')
        ->setParameter(':debut', '2021-12-01')
        ->andwhere('l.fin < :fin')
        ->setParameter(':fin', '2022-02-01')
        ->getQuery()
        ->getArrayResult();

        $tab = [];
         foreach($not as $v){
             $tab[] = $v['id'];
         }

      $qb2 = $this->createQueryBuilder('v');
        $req =  $qb2
            ->select('v')
            ->where($qb2->expr()->notIn('v.id', $tab))
            ->getQuery()
            ->getResult();
        dd($req);

        return $req;
        




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
