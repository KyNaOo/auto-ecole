<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $codecategorie = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodecategorie(): ?int
    {
        return $this->codecategorie;
    }

    public function setCodecategorie(int $codecategorie): self
    {
        $this->codecategorie = $codecategorie;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
