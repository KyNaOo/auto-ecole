<?php

namespace App\Entity;

use App\Repository\LicenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LicenceRepository::class)]
class Licence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $codemoniteur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateobtention = null;

    #[ORM\ManyToOne(inversedBy: 'licences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $codecategorie = null;

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

    public function getDateobtention(): ?\DateTimeInterface
    {
        return $this->dateobtention;
    }

    public function setDateobtention(\DateTimeInterface $dateobtention): self
    {
        $this->dateobtention = $dateobtention;

        return $this;
    }

    public function getCodecategorie(): ?Categorie
    {
        return $this->codecategorie;
    }

    public function setCodecategorie(?Categorie $codecategorie): self
    {
        $this->codecategorie = $codecategorie;

        return $this;
    }
}
