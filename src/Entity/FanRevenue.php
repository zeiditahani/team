<?php

namespace App\Entity;

use App\Repository\FanRevenueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FanRevenueRepository::class)]
class FanRevenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $revenue_obtenu_fan = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_encaissement = null;

    #[ORM\ManyToOne(targetEntity: Fan::class, inversedBy: 'fan_revenue')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fan $fan = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRevenueObtenuFan(): ?float
    {
        return $this->revenue_obtenu_fan;
    }

    public function setRevenueObtenuFan(float $revenue_obtenu_fan): static
    {
        $this->revenue_obtenu_fan = $revenue_obtenu_fan;

        return $this;
    }

    public function getDateEncaissement(): ?\DateTimeInterface
    {
        return $this->date_encaissement;
    }

    public function setDateEncaissement(\DateTimeInterface $date_encaissement): static
    {
        $this->date_encaissement = $date_encaissement;

        return $this;
    }

    public function getFan(): ?Fan
    {
        return $this->fan;
    }

    public function setFan(?Fan $fan): static
    {
        $this->fan = $fan;

        return $this;
    }
}
