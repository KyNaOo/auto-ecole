<?php

namespace App\Entity;

use App\Repository\LeconRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeconRepository::class)]
class Lecon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $reglee = null;

    #[ORM\ManyToOne(inversedBy: 'lecons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicule $codevehicule = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'lecons')]
    private Collection $codeuser;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    public function __construct()
    {
        $this->codeuser = new ArrayCollection();
    }

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

    public function getCodevehicule(): ?Vehicule
    {
        return $this->codevehicule;
    }

    public function setCodevehicule(?Vehicule $codevehicule): self
    {
        $this->codevehicule = $codevehicule;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCodeuser(): Collection
    {
        return $this->codeuser;
    }

    public function addCodeuser(User $codeuser): self
    {
        if (!$this->codeuser->contains($codeuser)) {
            $this->codeuser->add($codeuser);
        }

        return $this;
    }

    public function removeCodeuser(User $codeuser): self
    {
        $this->codeuser->removeElement($codeuser);

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }
}
