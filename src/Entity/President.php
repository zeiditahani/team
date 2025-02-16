<?php

namespace App\Entity;

use App\Repository\PresidentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresidentRepository::class)]
class President
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column]
    private ?int $phone_number = null;

    #[ORM\OneToOne(inversedBy: 'president')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'president', targetEntity: Equipe::class, cascade: ['persist'])]
    private ?Equipe $equipe = null;

    #[ORM\OneToMany(mappedBy: 'president', targetEntity: ContratPresident::class, cascade: ['persist', 'remove'])]
    private Collection $contrats;


    public function __construct()
    {
        $this->contrats = new ArrayCollection();
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

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

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
        // unset the owning side of the relation if necessary
        if ($equipe === null && $this->equipe !== null) {
            $this->equipe->setPresident(null);
        }

        // set the owning side of the relation if necessary
        if ($equipe !== null && $equipe->getPresident() !== $this) {
            $equipe->setPresident($this);
        }

        $this->equipe = $equipe;

        return $this;
    }

    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(ContratPresident $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setPresident($this);
        }

        return $this;
    }

    public function removeContrat(ContratPresident $contrat): self
    {
        if ($this->contrats->removeElement($contrat)) {
            if ($contrat->getPresident() === $this) {
                $contrat->setPresident(null);
            }
        }

        return $this;
    }
}
