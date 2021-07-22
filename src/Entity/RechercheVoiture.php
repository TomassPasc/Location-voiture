<?php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RechercheVoiture{
    
    /**
     * @Assert\LessThanOrEqual(propertyPath="maxAnnee", message="doit être plus petit que l'année Max")
     */
    private $minAnnee;

    /**
     * @Assert\GreaterThanOrEqual(propertyPath="minAnnee", message="doit être plus grand que l'année Min")
     */
    private $maxAnnee;
    

    /**
     * Get the value of minAnnee
     */ 
    public function getMinAnnee()
    {
        return $this->minAnnee;
    }

    /**
     * Set the value of minAnnee
     *
     * @return  self
     */ 
    public function setMinAnnee($minAnnee)
    {
        $this->minAnnee = $minAnnee;

        return $this;
    }

    /**
     * Get the value of maxAnnee
     */ 
    public function getMaxAnnee()
    {
        return $this->maxAnnee;
    }

    /**
     * Set the value of maxAnnee
     *
     * @return  self
     */ 
    public function setMaxAnnee($maxAnnee)
    {
        $this->maxAnnee = $maxAnnee;

        return $this;
    }
}