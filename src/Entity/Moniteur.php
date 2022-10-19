<?php

namespace App\Entity;

use App\Repository\MoniteurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoniteurRepository::class)]
class Moniteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $codemoniteur = null;

    #[ORM\Column(length: 50)]
    private ?string $nommoniteur = null;

    #[ORM\Column(length: 50)]
    private ?string $prenommoniteur = null;

    #[ORM\Column(length: 25)]
    private ?string $sexemoniteur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $maissancemoniteur = null;

    #[ORM\Column(length: 255)]
    private ?string $adressemoniteur = null;

    #[ORM\Column(length: 5)]
    private ?string $codepostalemoniteur = null;

    #[ORM\Column(length: 50)]
    private ?string $villemoniteur = null;

    #[ORM\Column(length: 12)]
    private ?string $telephonemoniteur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNommoniteur(): ?string
    {
        return $this->nommoniteur;
    }

    public function setNommoniteur(string $nommoniteur): self
    {
        $this->nommoniteur = $nommoniteur;

        return $this;
    }

    public function getPrenommoniteur(): ?string
    {
        return $this->prenommoniteur;
    }

    public function setPrenommoniteur(string $prenommoniteur): self
    {
        $this->prenommoniteur = $prenommoniteur;

        return $this;
    }

    public function getSexemoniteur(): ?string
    {
        return $this->sexemoniteur;
    }

    public function setSexemoniteur(string $sexemoniteur): self
    {
        $this->sexemoniteur = $sexemoniteur;

        return $this;
    }

    public function getMaissancemoniteur(): ?\DateTimeInterface
    {
        return $this->maissancemoniteur;
    }

    public function setMaissancemoniteur(\DateTimeInterface $maissancemoniteur): self
    {
        $this->maissancemoniteur = $maissancemoniteur;

        return $this;
    }

    public function getAdressemoniteur(): ?string
    {
        return $this->adressemoniteur;
    }

    public function setAdressemoniteur(string $adressemoniteur): self
    {
        $this->adressemoniteur = $adressemoniteur;

        return $this;
    }

    public function getCodepostalemoniteur(): ?string
    {
        return $this->codepostalemoniteur;
    }

    public function setCodepostalemoniteur(string $codepostalemoniteur): self
    {
        $this->codepostalemoniteur = $codepostalemoniteur;

        return $this;
    }

    public function getVillemoniteur(): ?string
    {
        return $this->villemoniteur;
    }

    public function setVillemoniteur(string $villemoniteur): self
    {
        $this->villemoniteur = $villemoniteur;

        return $this;
    }

    public function getTelephonemoniteur(): ?string
    {
        return $this->telephonemoniteur;
    }

    public function setTelephonemoniteur(string $telephonemoniteur): self
    {
        $this->telephonemoniteur = $telephonemoniteur;

        return $this;
    }
}
