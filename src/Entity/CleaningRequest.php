<?php

namespace App\Entity;

use App\Repository\CleaningRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CleaningRequestRepository::class)]
class CleaningRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'cleaningRequests')]
    private Collection $service;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column]
    private ?int $numberOfRooms = null;

    #[ORM\Column]
    private ?int $livingSpace = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isAccepted = null;

    #[ORM\ManyToOne(inversedBy: 'cleaningRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'cleaningRequests')]
    private ?Professional $professional = null;

    public function __construct()
    {
        $this->isAccepted = false;
        $this->date = new \DateTimeImmutable();
        $this->startTime = new \DateTimeImmutable();
        $this->endTime = new \DateTimeImmutable();
        $this->numberOfRooms = 0;
        $this->livingSpace = 0;
        $this->price = 0;
        $this->description = '';
        $this->service = new ArrayCollection();
        $this->professional = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getService(): Collection
    {
        return $this->service;
    }

    public function addService(Service $service): static
    {
        if (!$this->service->contains($service)) {
            $this->service->add($service);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        $this->service->removeElement($service);

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getNumberOfRooms(): ?int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(int $numberOfRooms): static
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    public function getLivingSpace(): ?int
    {
        return $this->livingSpace;
    }

    public function setLivingSpace(int $livingSpace): static
    {
        $this->livingSpace = $livingSpace;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

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

    public function isAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): static
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getProfessional(): ?Professional
    {
        return $this->professional;
    }

    public function setProfessional(?Professional $professional): static
    {
        $this->professional = $professional;

        return $this;
    }
}
