<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('projet')]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateProjet = null;

    #[ORM\Column]
    private ?int $tauxHeure = null;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    private ?Entreprise $entreprise = null;

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: Tache::class)]
    private Collection $taches;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
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

    public function getDateProjet(): ?\DateTimeInterface
    {
        return $this->dateProjet;
    }

    public function setDateProjet(\DateTimeInterface $dateProjet): self
    {
        $this->dateProjet = $dateProjet;

        return $this;
    }

    public function getTauxHeure(): ?int
    {
        return $this->tauxHeure;
    }

    public function setTauxHeure(int $tauxHeure): self
    {
        $this->tauxHeure = $tauxHeure;

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
    public function getTaches(): Collection
    {
        return $this->taches;
    }
    public function getTotal(): int
    {
        
        $total = 0;
        foreach($this->taches as $tache)
            $total += $tache->getNombreHeure();
        return $total*$this->tauxHeure;
    } 

    public function addTach(Tache $tach): self
    {
        if (!$this->taches->contains($tach)) {
            $this->taches->add($tach);
            $tach->setProjet($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): self
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getProjet() === $this) {
                $tach->setProjet(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;//$this->dateProjet->format('Y/m/d')." - ".
    }

}
