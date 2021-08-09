<?php

namespace App\Entity;

use App\Repository\AnnulationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnulationRepository::class)
 */
class Annulation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numReservation;

    /**
     * @ORM\Column(type="integer")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $voiture;

    /**
     * @ORM\Column(type="date")
     */
    private $debut;

    /**
     * @ORM\Column(type="date")
     */
    private $fin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAnnulation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idChargeStripe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumReservation(): ?int
    {
        return $this->numReservation;
    }

    public function setNumReservation(int $numReservation): self
    {
        $this->numReservation = $numReservation;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVoiture(): ?int
    {
        return $this->voiture;
    }

    public function setVoiture(int $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    public function setStripeToken(?string $stripeToken): self
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->idChargeStripe;
    }

    public function setIdChargeStripe(?string $idChargeStripe): self
    {
        $this->idChargeStripe = $idChargeStripe;

        return $this;
    }
}
