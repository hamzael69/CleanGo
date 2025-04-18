<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, CleaningRequest>
     */
    #[ORM\OneToMany(targetEntity: CleaningRequest::class, mappedBy: 'client', orphanRemoval: true)]
    private Collection $cleaningRequests;

    public function __construct()
    {
        $this->cleaningRequests = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->lastname . ' ' . $this->firstname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, CleaningRequest>
     */
    public function getCleaningRequests(): Collection
    {
        return $this->cleaningRequests;
    }

    public function addCleaningRequest(CleaningRequest $cleaningRequest): static
    {
        if (!$this->cleaningRequests->contains($cleaningRequest)) {
            $this->cleaningRequests->add($cleaningRequest);
            $cleaningRequest->setClient($this);
        }

        return $this;
    }

    public function removeCleaningRequest(CleaningRequest $cleaningRequest): static
    {
        if ($this->cleaningRequests->removeElement($cleaningRequest)) {
            // set the owning side to null (unless already changed)
            if ($cleaningRequest->getClient() === $this) {
                $cleaningRequest->setClient(null);
            }
        }

        return $this;
    }
}
