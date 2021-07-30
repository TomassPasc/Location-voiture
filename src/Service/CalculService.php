<?php

namespace App\Service;


class CalculService {

    public function nombreJours($dateDebut, $dateFin)
    {
        $jours = ($dateFin->diff($dateDebut)->format("%a")) + 1;
        return $jours;
       
    }

}