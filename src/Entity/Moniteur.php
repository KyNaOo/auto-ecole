<?php

namespace App\Entity;

use App\Repository\MoniteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoniteurRepository::class)]
class Moniteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    #[ORM\OneToMany(mappedBy: 'codemoniteur', targetEntity: Licence::class)]
    private Collection $licences;

    #[ORM\OneToMany(mappedBy: 'codemoniteur', targetEntity: Lecon::class)]
    private Collection $lecons;

    public function __construct()
    {
        $this->licences = new ArrayCollection();
        $this->lecons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Licence>
     */
    public function getLicences(): Collection
    {
        return $this->licences;
    }

    public function addLicence(Licence $licence): self
    {
        if (!$this->licences->contains($licence)) {
            $this->licences->add($licence);
            $licence->setCodemoniteur($this);
        }

        return $this;
    }

    public function removeLicence(Licence $licence): self
    {
        if ($this->licences->removeElement($licence)) {
            // set the owning side to null (unless already changed)
            if ($licence->getCodemoniteur() === $this) {
                $licence->setCodemoniteur(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->nommoniteur;
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
            $lecon->setCodemoniteur($this);
        }

        return $this;
    }

    public function removeLecon(Lecon $lecon): self
    {
        if ($this->lecons->removeElement($lecon)) {
            // set the owning side to null (unless already changed)
            if ($lecon->getCodemoniteur() === $this) {
                $lecon->setCodemoniteur(null);
            }
        }

        return $this;
    }
}
