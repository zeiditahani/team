<?php

namespace App\Entity;

use App\Repository\EquipesAdversairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipesAdversairesRepository::class)]
class EquipesAdversaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: "equipeAdverse", targetEntity: Matchs::class)]
    private Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    public function getId(): ?int 
    { 
        return $this->id; 
    }
    public function getNom(): ?string 
    { 
        return $this->nom; 
    }
    public function setNom(string $nom): self 
    { 
        $this->nom = $nom; 
        return $this; 
    }

    public function getMatchs(): Collection
    {
        return $this->matchs;
    }

    public function addMatch(Matchs $match): self
    {
        if (!$this->matchs->contains($match)) {
            $this->matchs->add($match);
            $match->setEquipeAdverse($this);
        }
        return $this;
    }

    public function removeMatch(Matchs $match): self
    {
        if ($this->matchs->removeElement($match)) {
            // Vérifier si le match est bien lié à cette équipe avant de le détacher
            if ($match->getEquipeAdverse() === $this) {
                $match->setEquipeAdverse(null);
            }
        }
        return $this;
    }
}
