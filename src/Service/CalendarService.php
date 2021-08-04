<?php

namespace App\Service;



class CalendarService
{


    public function parseData($locationsVoiture, $titleVoiture = false){
        //gère le titre si on veut le mot 'réservé' ou le modèle de la voiture
        if ($titleVoiture == true){
            foreach ($locationsVoiture as $loc) {
                $voitureLouee[] = [
                    'id' => $loc->getId(),
                    'start' => $loc->getDebut()->format('Y-m-d'), //reccupere la date au format texte
                    'end' => $loc->getFin()->format('Y-m-d'),
                    'title' => $loc->getVoiture()->getModele()->getLibelle(),
                    //'description' => 'description',
                    //'backgroundColor' => '#0000ff',
                    //'borderColor' => '#00ffff',
                    //'textColor' => '#00ffff',
                    //'allDay' => true
                ];
                } 
            } 
        else {
            foreach ($locationsVoiture as $loc) {
                $voitureLouee[] = [
                    'id' => $loc->getId(),
                    'start' => $loc->getDebut()->format('Y-m-d'), //reccupere la date au format texte
                    'end' => $loc->getFin()->format('Y-m-d'),
                    'title' => 'réservé',
                    //'description' => 'description',
                ];
            } 


            }
        return $data = json_encode($voitureLouee);
        }
    }
