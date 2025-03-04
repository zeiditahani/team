<?php

namespace App\Entity;

use App\Repository\SponsorRevenueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SponsorRevenueRepository::class)]
class SponsorRevenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $revenueObtenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEncaissement = null;

    #[ORM\ManyToOne(targetEntity: Sponsor::class, inversedBy: 'revenus', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sponsor $sponsor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRevenueObtenu(): ?float
    {
        return $this->revenueObtenu;
    }

    public function setRevenueObtenu(float $revenueObtenu): static
    {
        $this->revenueObtenu = $revenueObtenu;

        return $this;
    }

    public function getDateEncaissement(): ?\DateTimeInterface
    {
        return $this->dateEncaissement;
    }

    public function setDateEncaissement(\DateTimeInterface $dateEncaissement): static
    {
        $this->dateEncaissement = $dateEncaissement;

        return $this;
    }

    public function getSponsor(): ?Sponsor
    {
        return $this->sponsor;
    }

    public function setSponsor(?Sponsor $sponsor): static
    {
        $this->sponsor = $sponsor;

        return $this;
    }
}
