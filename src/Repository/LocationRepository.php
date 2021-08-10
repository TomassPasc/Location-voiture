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

    }

    public function findByCarRentId($debut, $fin)
    {
        //querybuilder stocke les locations pendant les dates chercher
        $queryBuilder = $this->createQueryBuilder('l')
            ->join('l.voiture', 'v')
            ->addSelect('v')
            ->where('v.id = v')
            ->andwhere("((l.debut > :debut) OR (l.fin > :debut)) AND ((l.debut < :fin) OR (l.fin < :fin))")
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->getQuery()
            ->getArrayResult();

        //on transforme le querybuilder en tableau avec les ids des voitures louees pendant ces dates
        // ex de retour ['3', '5', '6']
        $voituresLoueesId = [];
        foreach ($queryBuilder as $v) {
            $voituresLoueesId[] = $v['voiture']['id'];
        }
        return $voituresLoueesId;
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
