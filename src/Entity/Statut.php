<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups('statut')]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: TacheStatut::class)]
    private Collection $tacheStatuts;

    public function __construct()
    {
        $this->tacheStatuts = new ArrayCollection();
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

    /**
     * @return Collection<int, TacheStatut>
     */
    public function getTacheStatuts(): Collection
    {
        return $this->tacheStatuts;
    }

    public function addTacheStatut(TacheStatut $tacheStatut): self
    {
        if (!$this->tacheStatuts->contains($tacheStatut)) {
            $this->tacheStatuts->add($tacheStatut);
            $tacheStatut->setStatut($this);
        }

        return $this;
    }

    public function removeTacheStatut(TacheStatut $tacheStatut): self
    {
        if ($this->tacheStatuts->removeElement($tacheStatut)) {
            // set the owning side to null (unless already changed)
            if ($tacheStatut->getStatut() === $this) {
                $tacheStatut->setStatut(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
