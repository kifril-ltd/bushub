<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $passportNumber;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="passenger", orphanRemoval=true)
     */
    private $purchasedTickets;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="seller")
     */
    private $soldTickets;

    /**
     * @ORM\OneToMany(targetEntity=Route::class, mappedBy="creator", orphanRemoval=true)
     */
    private $createdRoutes;

    public function __construct()
    {
        $this->purchasedTickets = new ArrayCollection();
        $this->soldTickets = new ArrayCollection();
        $this->createdRoutes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function __toString()
    {
        return "$this->lastName $this->firstName $this->patronymic";
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getPassportNumber(): ?string
    {
        return $this->passportNumber;
    }

    public function setPassportNumber(string $passportNumber): self
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getPurchasedTickets(): Collection
    {
        return $this->purchasedTickets;
    }

    public function addPurchasedTicket(Ticket $purchasedTicket): self
    {
        if (!$this->purchasedTickets->contains($purchasedTicket)) {
            $this->purchasedTickets[] = $purchasedTicket;
            $purchasedTicket->setPassenger($this);
        }

        return $this;
    }

    public function removePurchasedTicket(Ticket $purchasedTicket): self
    {
        if ($this->purchasedTickets->removeElement($purchasedTicket)) {
            // set the owning side to null (unless already changed)
            if ($purchasedTicket->getPassenger() === $this) {
                $purchasedTicket->setPassenger(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getSoldTickets(): Collection
    {
        return $this->soldTickets;
    }

    public function addSoldTicket(Ticket $soldTicket): self
    {
        if (!$this->soldTickets->contains($soldTicket)) {
            $this->soldTickets[] = $soldTicket;
            $soldTicket->setSeller($this);
        }

        return $this;
    }

    public function removeSoldTicket(Ticket $soldTicket): self
    {
        if ($this->soldTickets->removeElement($soldTicket)) {
            // set the owning side to null (unless already changed)
            if ($soldTicket->getSeller() === $this) {
                $soldTicket->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Route[]
     */
    public function getCreatedRoutes(): Collection
    {
        return $this->createdRoutes;
    }

    public function addCreatedRoute(Route $createdRoute): self
    {
        if (!$this->createdRoutes->contains($createdRoute)) {
            $this->createdRoutes[] = $createdRoute;
            $createdRoute->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedRoute(Route $createdRoute): self
    {
        if ($this->createdRoutes->removeElement($createdRoute)) {
            // set the owning side to null (unless already changed)
            if ($createdRoute->getCreator() === $this) {
                $createdRoute->setCreator(null);
            }
        }

        return $this;
    }
}
