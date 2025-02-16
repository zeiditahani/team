<?php

namespace App\Entity;

use App\Repository\ContratPhotographeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratPhotographeRepository::class)]
class ContratPhotographe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $salaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_affectation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_contrat = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\ManyToOne(targetEntity: Photographe::class, inversedBy: 'contrats')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Photographe $photographe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDateAffectation(): ?\DateTimeInterface
    {
        return $this->date_affectation;
    }

    public function setDateAffectation(\DateTimeInterface $date_affectation): static
    {
        $this->date_affectation = $date_affectation;

        return $this;
    }

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->date_fin_contrat;
    }

    public function setDateFinContrat(?\DateTimeInterface $date_fin_contrat): static
    {
        $this->date_fin_contrat = $date_fin_contrat;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getPhotographe(): ?Photographe
    {
        return $this->photographe;
    }

    public function setPhotographe(?Photographe $photographe): self
    {
        $this->photographe = $photographe;
        return $this;
    }
}
