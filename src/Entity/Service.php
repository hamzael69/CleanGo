<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, CleaningRequest>
     */
    #[ORM\ManyToMany(targetEntity: CleaningRequest::class, mappedBy: 'service')]
    private Collection $cleaningRequests;

    public function __construct()
    {
        $this->cleaningRequests = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $cleaningRequest->addService($this);
        }

        return $this;
    }

    public function removeCleaningRequest(CleaningRequest $cleaningRequest): static
    {
        if ($this->cleaningRequests->removeElement($cleaningRequest)) {
            $cleaningRequest->removeService($this);
        }

        return $this;
    }
}
