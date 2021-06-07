<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $departureTime;

    /**
     * @ORM\Column(type="time")
     */
    private $arrivalTime;

    /**
     * @ORM\Column(type="array")
     */
    private $regularity = [];

    /**
     * @ORM\ManyToOne(targetEntity=Route::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $route;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="trip", orphanRemoval=true)
     */
    private $tickets;

    /**
     * @ORM\OneToOne(targetEntity=Bus::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="bus_id", referencedColumnName="id", nullable=true)
     */
    private $bus;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function __toString()
    {
        $route = $this->getRoute();
        return $route->getDeparturePoint() . ' '
            . $route->getArrivalPoint() . '|'
            . $this->getDepartureTime()->format('H:i');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(\DateTimeInterface $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(\DateTimeInterface $arrivalTime): self
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    public function getRegularity(): ?array
    {
        return $this->regularity;
    }

    public function setRegularity(array $regularity): self
    {
        $this->regularity = $regularity;

        return $this;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function setRoute(?Route $route): self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setTrip($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getTrip() === $this) {
                $ticket->setTrip(null);
            }
        }

        return $this;
    }

    public function getBus(): ?Bus
    {
        return $this->bus;
    }

    public function setBus(Bus $bus): self
    {
        $this->bus = $bus;

        return $this;
    }
}
