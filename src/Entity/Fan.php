<?php

namespace App\Entity;

use App\Repository\FanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FanRepository::class)]
class Fan
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
    private ?int $numero_telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $service_fourni = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedeb = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\Column]
    private ?float $prix = null;

    /**
     * @var Collection<int, FanRevenue>
     */
    #[ORM\OneToMany(targetEntity: FanRevenue::class, mappedBy: 'fan', cascade: ['persist', 'remove'])]
    private Collection $fan_revenue;

    public function __construct()
    {
        $this->fan_revenue = new ArrayCollection();
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

    public function getNumeroTelephone(): ?int
    {
        return $this->numero_telephone;
    }

    public function setNumeroTelephone(int $numero_telephone): static
    {
        $this->numero_telephone = $numero_telephone;

        return $this;
    }

    public function getServiceFourni(): ?string
    {
        return $this->service_fourni;
    }

    public function setServiceFourni(string $service_fourni): static
    {
        $this->service_fourni = $service_fourni;

        return $this;
    }

    public function getDatedeb(): ?\DateTimeInterface
    {
        return $this->datedeb;
    }

    public function setDatedeb(\DateTimeInterface $datedeb): static
    {
        $this->datedeb = $datedeb;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, FanRevenue>
     */
    public function getFanRevenue(): Collection
    {
        return $this->fan_revenue;
    }

    public function addFanRevenue(FanRevenue $fanRevenue): static
    {
        if (!$this->fan_revenue->contains($fanRevenue)) {
            $this->fan_revenue->add($fanRevenue);
            $fanRevenue->setFan($this);
        }

        return $this;
    }

    public function removeFanRevenue(FanRevenue $fanRevenue): static
    {
        if ($this->fan_revenue->removeElement($fanRevenue)) {
            // set the owning side to null (unless already changed)
            if ($fanRevenue->getFan() === $this) {
                $fanRevenue->setFan(null);
            }
        }

        return $this;
    }
}
