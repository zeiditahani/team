<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_MAILLOT', fields: ['numero_maillot'])]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?int $phone_number = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column]
    private ?int $numero_maillot = null;

    #[ORM\Column(length: 255)]
    private ?string $position = null;


    #[ORM\Column(nullable: true)]
    private ?int $nb_carton_jaune = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_carton_rouge = null;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: ContratJoueur::class, cascade: ['persist', 'remove'])]
    private Collection $contrats;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: MedicalCost::class, cascade: ['persist', 'remove'])]
    private Collection $medicalCosts;

    #[ORM\OneToOne(inversedBy: 'joueur')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'joueurs')]
    private ?Equipe $equipe = null;
    

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
        $this->medicalCosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(int $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNumeroMaillot(): ?int
    {
        return $this->numero_maillot;
    }

    public function setNumeroMaillot(int $numero_maillot): static
    {
        $this->numero_maillot = $numero_maillot;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }


    public function getNbCartonJaune(): ?int
    {
        return $this->nb_carton_jaune;
    }

    public function setNbCartonJaune(?int $nb_carton_jaune): static
    {
        $this->nb_carton_jaune = $nb_carton_jaune;

        return $this;
    }

    public function getNbCartonRouge(): ?int
    {
        return $this->nb_carton_rouge;
    }

    public function setNbCartonRouge(?int $nb_carton_rouge): static
    {
        $this->nb_carton_rouge = $nb_carton_rouge;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(ContratJoueur $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setJoueur($this);
        }

        return $this;
    }

    public function removeContrat(ContratJoueur $contrat): self
    {
        if ($this->contrats->removeElement($contrat)) {
            // Définir le joueur à null pour éviter une relation orpheline
            if ($contrat->getJoueur() === $this) {
                $contrat->setJoueur(null);
            }
        }

        return $this;
    }

    public function getMedicalCosts(): Collection
    {
        return $this->medicalCosts;
    }

    public function addMedicalCost(MedicalCost $medicalCost): self
    {
        if (!$this->medicalCosts->contains($medicalCost)) {
            $this->medicalCosts->add($medicalCost);
            $medicalCost->setJoueur($this); // Association avec le joueur
        }

        return $this;
    }

    public function removeMedicalCost(MedicalCost $medicalCost): self
    {
        if ($this->medicalCosts->removeElement($medicalCost)) {
            // Définir le joueur à null pour éviter la relation orpheline
            if ($medicalCost->getJoueur() === $this) {
                $medicalCost->setJoueur(null);
            }
        }

        return $this;
    }
}
