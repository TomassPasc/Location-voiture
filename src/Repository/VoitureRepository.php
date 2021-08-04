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

    public function findByDisponibility(Location $location)
    {
        // selectionnne les voitures dispo  
//requete sql correspondante avec valur en dur 2021-12-01 <  voiture dispo   < 2022-02-1

//  
//         SELECT * FROM `voiture` where `voiture`.id NOT IN (
//             SELECT `voiture`.id FROM `voiture`, `location`
//             WHERE `voiture`.id = `location`.voiture_id AND 
//                   `location`.`debut` > '2021-12-01' AND 
//                    `location`.`fin` < '2022-02-01')

        $qb = $this->_em->createQueryBuilder();
        $not = $qb
        ->select('v')
        ->from('App\Entity\Voiture', 'v')
        ->addSelect('l')
        ->join('v.locations', 'l')
        ->andwhere('l.debut > :debut')
        ->setParameter('debut', $location->getDebut())
        ->andwhere('l.fin < :fin')
        ->setParameter('fin', $location->getFin())
        ->getQuery()
        ->getArrayResult();
        $tab = [];
         foreach($not as $v){
             $tab[] = $v['id'];
         }
        //si l'array est vide on retourne toutes les voitures
         if (empty($tab)){
             return $this->findAll();
         }

      $qb2 = $this->createQueryBuilder('v');
        $req =  $qb2
            ->select('v')
            ->where($qb2->expr()->notIn('v.id', $tab))
            ->getQuery()
            ->getResult();
    
        return $req;
    }

    public function findByDisponibilityForOneCar($debut, $fin)
    {
        //requete sql correspondante avec valur en dur
        //         SELECT * FROM `voiture` where `voiture`.id NOT IN (
        //             SELECT `voiture`.id FROM `voiture` join `location` 
        // 	           ON `voiture`.id = `location`.voiture_id
        //             WHERE `location`.`debut` > '2021-12-01' AND `location`.`fin` < '2022-02-01')

        $qb = $this->_em->createQueryBuilder();
        $not = $qb
            ->select('v')
            ->from('App\Entity\Voiture', 'v')
            ->addSelect('l')
            ->join('v.locations', 'l')
            ->where("(l.debut > 2021-08-15 OR l.fin > 2021-08-15) AND (l.debut < 2021-08-16 OR l.fin < 2021-08-16)")
            ->getQuery()
            ->getArrayResult();

        

        $tab = [];
        foreach ($not as $v) {
            $tab[] = $v;
        }
        dd($tab);
        //si l'array est vide on retourne toutes les voitures
        if (empty($tab)) {
            echo 'good';
            return ;
        }

        $qb2 = $this->createQueryBuilder('v');
        $req =  $qb2
            ->select('v')
            ->where($qb2->expr()->notIn('v.id', $tab))
            ->getQuery()
            ->getResult();

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
