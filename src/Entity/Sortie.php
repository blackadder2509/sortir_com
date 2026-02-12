<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[ORM\ManyToOne(inversedBy: 'sortiesOrganisees')]
    private ?User $organisateur = null;

    #[ORM\ManyToOne]
    private ?Etat $etat = null;

    #[ORM\ManyToOne]
    private ?Campus $campus = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $participants;

    public function __construct() { $this->participants = new ArrayCollection(); }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getDateHeureDebut(): ?\DateTimeInterface { return $this->dateHeureDebut; }
    public function setDateHeureDebut(\DateTimeInterface $date): static { $this->dateHeureDebut = $date; return $this; }
    public function getDateLimiteInscription(): ?\DateTimeInterface { return $this->dateLimiteInscription; }
    public function setDateLimiteInscription(\DateTimeInterface $date): static { $this->dateLimiteInscription = $date; return $this; }
    public function getOrganisateur(): ?User { return $this->organisateur; }
    public function setOrganisateur(?User $organisateur): static { $this->organisateur = $organisateur; return $this; }
    public function getEtat(): ?Etat { return $this->etat; }
    public function setEtat(?Etat $etat): static { $this->etat = $etat; return $this; }
    public function getCampus(): ?Campus { return $this->campus; }
    public function setCampus(?Campus $campus): static { $this->campus = $campus; return $this; }
}
