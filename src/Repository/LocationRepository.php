<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findByDisponibilityForOneCar($debut, $fin, $voitureId, $edit = false, $locationId = null)
    {

        $queryBuilder = $this->createQueryBuilder('l')
        ->where("((l.debut > :debut) OR (l.fin > :debut)) AND ((l.debut < :fin) OR (l.fin < :fin)) AND l.voiture = :voitId")
        ->setParameter('debut', $debut)
        ->setParameter('fin', $fin)
        ->setParameter('voitId', $voitureId)
        ->getQuery()
        ->getArrayResult();
       
        //partie edit on doit prendre en compte la location actuelle qui va etre modif
        if($edit){
            
            if((count($queryBuilder) == 1 && $queryBuilder[0]['id'] == $locationId) || (count($queryBuilder) == 0)){
                return true;
            } else {
                return false;
            }


        } else { 
            //si ce n'est pas une edition on verifie juste si l'array est vide alors la voiture est dispo sinon elle ne l'est pas.
            if(count($queryBuilder) == 0){
                return true;
            } else{
                return false;
            };
        }
        
        //requete sql correspondante avec valur en dur
        //         SELECT * FROM `voiture` where `voiture`.id NOT IN (
        //             SELECT `voiture`.id FROM `voiture` join `location` 
        // 	           ON `voiture`.id = `location`.voiture_id
        //             WHERE `location`.`debut` > '2021-12-01' AND `location`.`fin` < '2022-02-01')

        // $qb = $this->_em->createQueryBuilder();
        // $not = $qb
        //     ->select('v')
        //     ->from('App\Entity\Voiture', 'v')
        //     ->addSelect('l')
        //     ->join('v.locations', 'l')
        //     ->where("(l.debut > 2021-08-15 OR l.fin > 2021-08-15) AND (l.debut < 2021-08-16 OR l.fin < 2021-08-16)")
        //     ->getQuery()
        //     ->getArrayResult();



        // $tab = [];
        // foreach ($not as $v) {
        //     $tab[] = $v;
        // }
        // dd($tab);
        // //si l'array est vide on retourne toutes les voitures
        // if (empty($tab)) {
        //     echo 'good';
        //     return;
        // }

        // $qb2 = $this->createQueryBuilder('v');
        // $req =  $qb2
        //     ->select('v')
        //     ->where($qb2->expr()->notIn('v.id', $tab))
        //     ->getQuery()
        //     ->getResult();

        // return $req;
    }

    // /**
    //  * @return Location[] Returns an array of Location objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Location
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
