<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Matchs::class, inversedBy: "tickets")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matchs $match = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $type = null; // Pelouse, Virage

    #[ORM\Column(type: "integer")]
    private int $nbTicketDispo;

    #[ORM\Column(type: "integer")]
    private int $nbTicketTotal;

    #[ORM\Column(type: "float")]
    private float $prix;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $statut = "disponible";

    public function getId(): ?int 
    { 
        return $this->id; 
    }
    public function getMatch(): ?Matchs 
    { 
        return $this->match; 
    }
    public function setMatch(?Matchs $match): self 
    { 
        $this->match = $match; 
        return $this; 
    }
    public function getType(): ?string 
    { 
        return $this->type; 
    }
    public function setType(string $type): self 
    { 
        $this->type = $type; 
        return $this; 
    }
    public function getNbTicketDispo(): int 
    { 
        return $this->nbTicketDispo; 
    }
    public function setNbTicketDispo(int $nbTicketDispo): self 
    { 
        $this->nbTicketDispo = $nbTicketDispo; 
        return $this; 
    }
    public function getNbTicketTotal(): int 
    { 
        return $this->nbTicketTotal; 
    }
    public function setNbTicketTotal(int $nbTicketTotal): self 
    { 
        $this->nbTicketTotal = $nbTicketTotal; 
        return $this; 
    }
    public function getPrix(): float 
    { 
        return $this->prix; 
    }
    public function setPrix(float $prix): self 
    { 
        $this->prix = $prix; 
        return $this; 
    }
    public function getStatut(): ?string 
    { 
        return $this->statut; 
    }
    public function setStatut(string $statut): self 
    { 
        $this->statut = $statut; 
        return $this; 
    }
}
