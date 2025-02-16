<?php

namespace App\Entity;

use App\Repository\MedicalCostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicalCostRepository::class)]
class MedicalCost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $costs = null;

    #[ORM\ManyToOne(targetEntity: Joueur::class, inversedBy: 'medicalCosts')]
    #[ORM\JoinColumn(nullable: false)] // La relation est obligatoire, donc le joueur ne peut pas être null
    private ?Joueur $joueur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCosts(): ?float
    {
        return $this->costs;
    }

    public function setCosts(?float $costs): static
    {
        $this->costs = $costs;

        return $this;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): self
    {
        $this->joueur = $joueur;

        return $this;
    }
}
