<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('personne')]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups('personne')]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?bool $isPrestataire = null;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?Entreprise $entreprise = null;

    #[ORM\OneToMany(mappedBy: 'prestataire', targetEntity: Tache::class)]
    private Collection $tachesPrestataire;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Tache::class)]
    private Collection $tachesEmploye;

    public function __construct()
    {
        $this->tachesPrestataire = new ArrayCollection();
        $this->tachesEmploye = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function isIsPrestataire(): ?bool
    {
        return $this->isPrestataire;
    }

    public function setIsPrestataire(bool $isPrestataire): self
    {
        $this->isPrestataire = $isPrestataire;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTachesPrestataire(): Collection
    {
        return $this->tachesPrestataire;
    }

    public function addTachesPrestataire(Tache $tachesPrestataire): self
    {
        if (!$this->tachesPrestataire->contains($tachesPrestataire)) {
            $this->tachesPrestataire->add($tachesPrestataire);
            $tachesPrestataire->setPrestataire($this);
        }

        return $this;
    }

    public function removeTachesPrestataire(Tache $tachesPrestataire): self
    {
        if ($this->tachesPrestataire->removeElement($tachesPrestataire)) {
            // set the owning side to null (unless already changed)
            if ($tachesPrestataire->getPrestataire() === $this) {
                $tachesPrestataire->setPrestataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTachesEmploye(): Collection
    {
        return $this->tachesEmploye;
    }

    public function addTachesEmploye(Tache $tachesEmploye): self
    {
        if (!$this->tachesEmploye->contains($tachesEmploye)) {
            $this->tachesEmploye->add($tachesEmploye);
            $tachesEmploye->setEmploye($this);
        }

        return $this;
    }

    public function removeTachesEmploye(Tache $tachesEmploye): self
    {
        if ($this->tachesEmploye->removeElement($tachesEmploye)) {
            // set the owning side to null (unless already changed)
            if ($tachesEmploye->getEmploye() === $this) {
                $tachesEmploye->setEmploye(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom." ".$this->prenom;
    }
}
