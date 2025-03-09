<?php

namespace App\Entity;

use App\Repository\LogisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogisticRepository::class)]
class Logistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: "float")]
    private float $depense;

    #[ORM\ManyToOne(targetEntity: Matchs::class, inversedBy: 'logistics')]
    #[ORM\JoinColumn(nullable: false)]  // Cette relation est obligatoire
    private ?Matchs $match = null;

    public function getId(): ?int 
    { 
        return $this->id; 
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

    public function getDepense(): float 
    { 
        return $this->depense; 
    }

    public function setDepense(float $depense): self 
    { 
        $this->depense = $depense; 
        
        return $this; 
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
}
