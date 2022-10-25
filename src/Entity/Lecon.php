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
    private ?int $reglee = null;

    #[ORM\ManyToOne(inversedBy: 'lecons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Eleve $codeeleve = null;

    #[ORM\ManyToOne(inversedBy: 'lecons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Moniteur $codemoniteur = null;

    #[ORM\ManyToOne(inversedBy: 'lecons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicule $codevehicule = null;

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

    public function getReglee(): ?int
    {
        return $this->reglee;
    }

    public function setReglee(int $reglee): self
    {
        $this->reglee = $reglee;

        return $this;
    }

    public function getCodeeleve(): ?Eleve
    {
        return $this->codeeleve;
    }

    public function setCodeeleve(?Eleve $codeeleve): self
    {
        $this->codeeleve = $codeeleve;

        return $this;
    }

    public function getCodemoniteur(): ?Moniteur
    {
        return $this->codemoniteur;
    }

    public function setCodemoniteur(?Moniteur $codemoniteur): self
    {
        $this->codemoniteur = $codemoniteur;

        return $this;
    }

    public function getCodevehicule(): ?Vehicule
    {
        return $this->codevehicule;
    }

    public function setCodevehicule(?Vehicule $codevehicule): self
    {
        $this->codevehicule = $codevehicule;

        return $this;
    }
}
