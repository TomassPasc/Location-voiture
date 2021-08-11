# Site de location de voitures

Ce projet a pour but de simuler un site de location de voitures.

## Fonctionalitées

-gestion des rôles utilisateurs et admin
-interface administrateur (gestion des voitures et des locations)
-interface utilisateur (gestion de son profil et de ses locations)
-génération de pdf
-envoie de mail(formulaire de contact / envoie d'un email lors de la réservation)
-gestion des paiments pour la location et des remboursements.
-gestion de la location des voitures en fonction de leur disponibilité
-calcul du prix de la location
-mise en place d'un calendrier

## Fabriqué avec

### Technologies principales

* [Symfony 5.3.3](https://symfony.com/)
* PHP 8.0.3
* [Bootstrap 5](https://getbootstrap.com/) et Bootwatch(thème [sketchy](https://bootswatch.com/sketchy/))

### Quelques services utilisées

* [Stripe](https://stripe.com/fr)
* [MailTrap](https://mailtrap.io/)
* [FullCalendar](https://fullcalendar.io/)
* [DomPdf](https://github.com/dompdf/dompdf)

### Développé avec
* windows 10
* Base de données: MySql


## Pour commencer

### Pré-requis

- PHP 8.0^
- Un compte Stripe (il est nécessaire de mettre sa clé public et privée pour voir les paiments qui sont simulées / un exemple de n° de carte Visa test Stripe: 4242 4242 4242 4242 )
-un compte mailTrap


### Installation

Les étapes pour faire fonctionner en local le projet

- cloner le répo github sur votre machine (git clone https://github.com/TomassPasc/Location-voiture.git)
- renommer le fichier env.exemple par .env et mettre les données relatives à vos comptes stripe et MailTrap
- executer la commande ``composer install`` dans votre terminal afin d'installer les dépendances nécessaires au projet
- Pour la base de données. Executer la commande ``php bin/console doctrine:database:create`` pour créer la database puis `` php bin/console make:migration`` et enfin ``php bin/console make:migration``. Pour insérer un jeu de données lancer la commande ``php bin/console doctrine:fixtures:load``

## Démarrage

- lancer un serveur en local avec la commande symfony: ``symfony server:start``
- ou par exemple ``php -S localhost:8000`` 
