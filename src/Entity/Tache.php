<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('tache')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('tache')]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups('tache')]
    private ?\DateTimeInterface $dateAchevement = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreHeure = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[Groups('tache')]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(inversedBy: 'tachesPrestataire')]
    #[Groups('tache')]
    private ?Personne $prestataire = null;

    #[ORM\ManyToOne(inversedBy: 'tachesEmploye')]
    private ?Personne $employe = null;

    #[ORM\ManyToOne(inversedBy: 'tache')]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'tache', targetEntity: TacheStatut::class)]
    #[Groups('tache')]
    #[ORM\OrderBy(['dateChangement'=>'desc'])]
    private Collection $tacheStatuts;

    public function __construct()
    {
        $this->tacheStatuts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateAchevement(): ?\DateTimeInterface
    {
        return $this->dateAchevement;
    }

    public function setDateAchevement(?\DateTimeInterface $dateAchevement): self
    {
        $this->dateAchevement = $dateAchevement;

        return $this;
    }

    public function getNombreHeure(): ?int
    {
        return $this->nombreHeure;
    }

    public function setNombreHeure(?int $nombreHeure): self
    {
        $this->nombreHeure = $nombreHeure;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getPrestataire(): ?Personne
    {
        return $this->prestataire;
    }

    public function setPrestataire(?Personne $prestataire): self
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    public function getEmploye(): ?Personne
    {
        return $this->employe;
    }

    public function setEmploye(?Personne $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, TacheStatut>
     */
    public function getTacheStatuts(): Collection
    {
        return $this->tacheStatuts;
    }
    
    public function getLastStatut() 
    {
        return $this->tacheStatuts[0];
    }
    
    public function addTacheStatut(TacheStatut $tacheStatut): self
    {
        if (!$this->tacheStatuts->contains($tacheStatut)) {
            $this->tacheStatuts->add($tacheStatut);
            $tacheStatut->setTache($this);
        }

        return $this;
    }

    public function removeTacheStatut(TacheStatut $tacheStatut): self
    {
        if ($this->tacheStatuts->removeElement($tacheStatut)) {
            // set the owning side to null (unless already changed)
            if ($tacheStatut->getTache() === $this) {
                $tacheStatut->setTache(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->description;//$this->dateAchevement->format('Y/m/d')." - ".
    }
}
