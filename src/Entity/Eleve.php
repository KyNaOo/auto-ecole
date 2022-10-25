<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomeleve = null;

    #[ORM\Column(length: 50)]
    private ?string $prenomeleve = null;

    #[ORM\Column(length: 25)]
    private ?string $sexeeleve = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datenaissance = null;

    #[ORM\Column(length: 50)]
    private ?string $adresse = null;

    #[ORM\Column(length: 5)]
    private ?string $codepostale = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(length: 12)]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'codeeleve', targetEntity: Lecon::class)]
    private Collection $lecons;

    public function __construct()
    {
        $this->lecons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomeleve(): ?string
    {
        return $this->nomeleve;
    }

    public function setNomeleve(string $nomeleve): self
    {
        $this->nomeleve = $nomeleve;

        return $this;
    }

    public function getPrenomeleve(): ?string
    {
        return $this->prenomeleve;
    }

    public function setPrenomeleve(string $prenomeleve): self
    {
        $this->prenomeleve = $prenomeleve;

        return $this;
    }

    public function getSexeeleve(): ?string
    {
        return $this->sexeeleve;
    }

    public function setSexeeleve(string $sexeeleve): self
    {
        $this->sexeeleve = $sexeeleve;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodepostale(): ?string
    {
        return $this->codepostale;
    }

    public function setCodepostale(string $codepostale): self
    {
        $this->codepostale = $codepostale;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Lecon>
     */
    public function getLecons(): Collection
    {
        return $this->lecons;
    }

    public function addLecon(Lecon $lecon): self
    {
        if (!$this->lecons->contains($lecon)) {
            $this->lecons->add($lecon);
            $lecon->setCodeeleve($this);
        }

        return $this;
    }

    public function removeLecon(Lecon $lecon): self
    {
        if ($this->lecons->removeElement($lecon)) {
            // set the owning side to null (unless already changed)
            if ($lecon->getCodeeleve() === $this) {
                $lecon->setCodeeleve(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->nomeleve;
    }

}
