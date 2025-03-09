<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Matchs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $terrain = null;

    #[ORM\Column(type: "string", length: 10)]
    private ?string $tactique = null;

    #[ORM\ManyToOne(targetEntity: EquipesAdversaires::class, inversedBy: "matchs")]
    #[ORM\JoinColumn(nullable: false)]
    private ?EquipesAdversaires $equipeAdverse = null;

    #[ORM\ManyToMany(targetEntity: Joueur::class)]
    #[ORM\JoinTable(name: "match_joueurs")]
    private Collection $joueurs;

    #[ORM\OneToMany(mappedBy: "match", targetEntity: Ticket::class, cascade: ["persist", "remove"])]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: "match", targetEntity: Logistic::class, cascade: ["persist", "remove"])]
    private Collection $logistiques;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->logistiques = new ArrayCollection();
    }

    public function getId(): ?int
    { 
        return $this->id; 
    }

    public function getDate(): ?\DateTimeInterface
    { 
        return $this->date; 
    }

    public function setDate(\DateTimeInterface $date): self 
    { 
        $this->date = $date; return $this; 
    }

    public function getTerrain(): ?string 
    { 
        return $this->terrain; 
    }

    public function setTerrain(string $terrain): self 
    { 
        $this->terrain = $terrain; return $this; 
    }

    public function getTactique(): ?string 
    { 
        return $this->tactique; 
    }

    public function setTactique(string $tactique): self 
    { 
        $this->tactique = $tactique; return $this; 
    }

    public function getEquipeAdverse(): ?EquipesAdversaires 
    { 
        return $this->equipeAdverse; 
    }

    public function setEquipeAdverse(?EquipesAdversaires $equipeAdverse): self 
    { 
        $this->equipeAdverse = $equipeAdverse; return $this; 
    }

    public function getJoueurs(): Collection 
    { 
        return $this->joueurs; 
    }

    public function addJoueur(Joueur $joueur): self 
    { 
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
        } 
        return $this; 
    }

    public function removeJoueur(Joueur $joueur): self 
    { 
        $this->joueurs->removeElement($joueur); 
        return $this; 
    }

    public function getTickets(): Collection 
    { 
        return $this->tickets; 
    }

    public function getLogistics(): Collection
    {
        return $this->logistiques;
    }

    public function addLogistic(Logistic $logistiques): self
    {
        if (!$this->logistiques->contains($logistiques)) {
            $this->logistiques[] = $logistiques;
            $logistiques->setMatch($this);
        }

        return $this;
    }

    public function removeLogistic(Logistic $logistiques): self
    {
        $this->logistiques->removeElement($logistiques);

        // Set the owning side to null (unless already done)
        if ($logistiques->getMatch() === $this) {
            $logistiques->setMatch(null);
        }

        return $this;
    }
}
