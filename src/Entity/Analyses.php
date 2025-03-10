<?php

namespace App\Entity;

use App\Repository\AnalysesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnalysesRepository::class)]
class Analyses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Matchs::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matchs $match = null;

    #[ORM\ManyToOne(targetEntity: Joueur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Joueur $joueur = null;

    #[ORM\Column(type: 'integer')]
    private int $nb_carton_rouge = 0;

    #[ORM\Column(type: 'integer')]
    private int $nb_carton_jaune = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatch(): ?Matchs
    {
        return $this->match;
    }

    public function setMatch(?Matchs $match): static
    {
        $this->match = $match;
        return $this;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): static
    {
        $this->joueur = $joueur;
        return $this;
    }

    public function getNbCartonRouge(): int
    {
        return $this->nb_carton_rouge;
    }

    public function setNbCartonRouge(int $nb_carton_rouge): static
    {
        $this->nb_carton_rouge = $nb_carton_rouge;
        return $this;
    }

    public function getNbCartonJaune(): int
    {
        return $this->nb_carton_jaune;
    }

    public function setNbCartonJaune(int $nb_carton_jaune): static
    {
        $this->nb_carton_jaune = $nb_carton_jaune;
        return $this;
    }
}
