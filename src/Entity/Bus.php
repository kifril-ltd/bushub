<?php

namespace App\Entity;

use App\Repository\BusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BusRepository::class)
 */
class Bus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $licensePlate;

    /**
     * @ORM\Column(type="integer")
     */
    private $seatsNumber;

    /**
     * @ORM\OneToOne(targetEntity=Trip::class, inversedBy="bus", cascade={"persist", "remove"})
     */
    private $trip;


    public function __toString()
    {
        return $this->licensePlate . ' | ' . "мест: $this->seatsNumber";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): self
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getSeatsNumber(): ?int
    {
        return $this->seatsNumber;
    }

    public function setSeatsNumber(int $seatsNumber): self
    {
        $this->seatsNumber = $seatsNumber;

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): self
    {
        $this->trip = $trip;

        return $this;
    }

}