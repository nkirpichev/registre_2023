<?php

namespace App\Entity;

use App\Repository\TacheStatutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TacheStatutRepository::class)]
class TacheStatut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('statut')]  
    private ?\DateTimeInterface $dateChangement = null;

    #[ORM\ManyToOne(inversedBy: 'tacheStatuts')]
    private ?Tache $tache = null;

    #[ORM\ManyToOne(inversedBy: 'tacheStatuts')]
    #[Groups('statut')]
    private ?Statut $statut = null;
   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateChangement(): ?\DateTimeInterface
    {
        return $this->dateChangement;
    }

    public function setDateChangement(\DateTimeInterface $dateChangement): self
    {
        $this->dateChangement = $dateChangement;

        return $this;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): self
    {
        $this->tache = $tache;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
    
    public function __toString()
    {
        return $this->statut->getNom();//$this->dateAchevement->format('Y/m/d')." - ".
    }

    public function showStatusDate()
    {
        return $this->statut->getNom()." - ".$this->dateChangement->format('d/m/Y');
    }
}
