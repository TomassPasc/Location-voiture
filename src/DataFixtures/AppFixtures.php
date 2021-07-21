<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Voiture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $marque1 = new Marque();
        $marque1->setLibelle("Yotota");
        $manager->persist($marque1);
        $marque2 = new Marque();
        $marque2->setLibelle("Jeupo");
        $manager->persist($marque2);

        $modele1 = new Modele();
        $modele1->setLibelle("Rayis")
            ->setImage("modele1.jpg")
            ->setPrixMoyen(15000)
            ->setMarque($marque1);
        $manager->persist($modele1);
        $modele2 = new Modele();
        $modele2->setLibelle("Yraus")
            ->setImage("modele2.jpg")
            ->setPrixMoyen(20000)
            ->setMarque($marque1);
        $manager->persist($modele2);
        $modele3 = new Modele();
        $modele3->setLibelle("007")
            ->setImage("modele3.jpg")
            ->setPrixMoyen(30000)
            ->setMarque($marque1);
        $manager->persist($modele3);
        $modele4 = new Modele();
        $modele4->setLibelle("008")
            ->setImage("modele4.jpg")
            ->setPrixMoyen(10000)
            ->setMarque($marque1);
        $manager->persist($modele4);
        $modele5 = new Modele();
        $modele5->setLibelle("009")
            ->setImage("modele5.jpg")
            ->setPrixMoyen(17000)
            ->setMarque($marque1);
        $manager->persist($modele5);

        $modeles = [$modele1, $modele2, $modele3, $modele4, $modele5];

        $voiture1 = new Voiture();
        $voiture1->setImmatriculation("AA-123-BB")
        ->setNbPortes(3)
            ->setAnnee(2012)
            ->setModele($modele1);
        $manager->persist($voiture1);

        $voiture2 = new Voiture();
        $voiture2->setImmatriculation("CC-202-AA")
        ->setNbPortes(5)
            ->setAnnee(2020)
            ->setModele($modele2);
        $manager->persist($voiture2);

        $voiture3 = new Voiture();
        $voiture3->setImmatriculation("CE-203-AC")
        ->setNbPortes(3)
            ->setAnnee(2019)
            ->setModele($modele3);
        $manager->persist($voiture3);


        $voiture4 = new Voiture();
        $voiture4->setImmatriculation("CR-209-BC")
        ->setNbPortes(3)
            ->setAnnee(2015)
            ->setModele($modele4);
        $manager->persist($voiture4);

        $voiture5 = new Voiture();
        $voiture5->setImmatriculation("ZR-219-AC")
        ->setNbPortes(3)
            ->setAnnee(2000)
            ->setModele($modele5);
        $manager->persist($voiture5);

        $voiture6 = new Voiture();
        $voiture6->setImmatriculation("CA-289-AC")
        ->setNbPortes(5)
            ->setAnnee(2019)
            ->setModele($modele5);
        $manager->persist($voiture6);

        $voiture7 = new Voiture();
        $voiture7->setImmatriculation("CR-209-BC")
        ->setNbPortes(5)
            ->setAnnee(2015)
            ->setModele($modele5);
        $manager->persist($voiture7);

        $voiture8 = new Voiture();
        $voiture8->setImmatriculation("AR-209-AZ")
        ->setNbPortes(5)
            ->setAnnee(2019)
            ->setModele($modele5);
        $manager->persist($voiture8);


        $voiture9 = new Voiture();
        $voiture9->setImmatriculation("CC-289-RR")
        ->setNbPortes(3)
            ->setAnnee(2012)
            ->setModele($modele1);
        $manager->persist($voiture9);

        $voiture10 = new Voiture();
        $voiture10->setImmatriculation("CC-289-RR")
        ->setNbPortes(5)
            ->setAnnee(2011)
            ->setModele($modele1);
        $manager->persist($voiture10);

        $voiture11 = new Voiture();
        $voiture11->setImmatriculation("AA-989-ZE")
        ->setNbPortes(3)
            ->setAnnee(2001)
            ->setModele($modele1);
        $manager->persist($voiture11);

        $voiture12 = new Voiture();
        $voiture12->setImmatriculation("AR-989-RR")
        ->setNbPortes(5)
            ->setAnnee(2005)
            ->setModele($modele2);
        $manager->persist($voiture12);

        $voiture13 = new Voiture();
        $voiture13->setImmatriculation("PO-800-AA")
        ->setNbPortes(5)
            ->setAnnee(2004)
            ->setModele($modele3);
        $manager->persist($voiture13);

        $voiture14 = new Voiture();
        $voiture14->setImmatriculation("QS-669-BB")
        ->setNbPortes(5)
            ->setAnnee(2007)
            ->setModele($modele3);
        $manager->persist($voiture14);

        $voiture15 = new Voiture();
        $voiture15->setImmatriculation("QF-669-BA")
        ->setNbPortes(3)
            ->setAnnee(2010)
            ->setModele($modele4);
        $manager->persist($voiture15);

        $voiture16 = new Voiture();
        $voiture16->setImmatriculation("QV-777-AB")
        ->setNbPortes(5)
            ->setAnnee(2007)
            ->setModele($modele4);
        $manager->persist($voiture16);




        

        


        // $faker = \Faker\Factory::create('fr_FR');
        // foreach ($modeles as $m) {
        //     $rand = rand(3, 5);
        //     for ($i = 1; $i <= $rand; $i++) {
        //         $voiture = new Voiture();
        //         //XX1234XX
        //         $voiture->setImmatriculation($faker->regexify("[A-Z]{2}[0-9]{3,4}[A-Z]{2}"))
        //         ->setNbPortes($faker->randomElement($array = array(3, 5)))
        //             ->setAnnee($faker->numberBetween($min = 1990, $max = 2019))
        //             ->setModele($m);
        //         $manager->persist($voiture);
        //     }
        // }

        $manager->flush();


    }
}
