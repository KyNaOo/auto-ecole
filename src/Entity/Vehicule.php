<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $immatriculation = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $marque = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $modele = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $annee = null;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Categorie $codecategorie = null;

    #[ORM\OneToMany(mappedBy: 'codevehicule', targetEntity: Lecon::class)]
    private Collection $lecons;

    public function __construct()
    {
        $this->lecons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

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
            $lecon->setCodevehicule($this);
        }

        return $this;
    }

    public function removeLecon(Lecon $lecon): self
    {
        if ($this->lecons->removeElement($lecon)) {
            // set the owning side to null (unless already changed)
            if ($lecon->getCodevehicule() === $this) {
                $lecon->setCodevehicule(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->marque;
    }


}
