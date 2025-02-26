<?php

namespace App\Entity;

use App\Repository\TrainingsessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingsessionRepository::class)]
class Trainingsession
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;

    /**
     * @var array<int>
     */
    #[ORM\Column(type: Types::JSON)]
    private array $tasks = [];

     /**
     * @var array<int>
     */
    #[ORM\Column(type: Types::JSON)]
    private array $joueurs = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): static
    {
        $this->time = $time;
        return $this;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function setTasks(array $tasks): static
    {
        $this->tasks = $tasks;
        return $this;
    }
    public function getJoueurs(): ?array
    {
        return $this->joueurs;
    }

    public function setJoueurs(array $joueurs): self
    {
        $this->joueurs = $joueurs;
        return $this;
    }
}
