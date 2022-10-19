<?php

namespace App\Entity;

use App\Repository\LeconRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeconRepository::class)]
class Lecon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 10)]
    private ?string $heure = null;

    #[ORM\Column]
    private ?int $codemoniteur = null;

    #[ORM\Column]
    private ?int $codeeleve = null;

    #[ORM\Column(length: 50)]
    private ?string $immatriculation = null;

    #[ORM\Column]
    private ?int $reglee = null;

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
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getCodemoniteur(): ?int
    {
        return $this->codemoniteur;
    }

    public function setCodemoniteur(int $codemoniteur): self
    {
        $this->codemoniteur = $codemoniteur;

        return $this;
    }

    public function getCodeeleve(): ?int
    {
        return $this->codeeleve;
    }

    public function setCodeeleve(int $codeeleve): self
    {
        $this->codeeleve = $codeeleve;

        return $this;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getReglee(): ?int
    {
        return $this->reglee;
    }

    public function setReglee(int $reglee): self
    {
        $this->reglee = $reglee;

        return $this;
    }
}
