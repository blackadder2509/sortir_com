<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[ORM\Column]
    private ?int $nbInscriptionsMax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $infosSortie = null;

    #[ORM\ManyToOne(inversedBy: 'sortiesOrganisees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    // --- C'est ici qu'on change le nom de la table pour éviter le conflit ---
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sorties')]
    #[ORM\JoinTable(name: 'participants_sortie')]
    private Collection $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    // --- GETTERS & SETTERS ---

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getDateHeureDebut(): ?\DateTimeInterface { return $this->dateHeureDebut; }
    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): static { $this->dateHeureDebut = $dateHeureDebut; return $this; }

    public function getDateLimiteInscription(): ?\DateTimeInterface { return $this->dateLimiteInscription; }
    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): static { $this->dateLimiteInscription = $dateLimiteInscription; return $this; }

    public function getNbInscriptionsMax(): ?int { return $this->nbInscriptionsMax; }
    public function setNbInscriptionsMax(int $nbInscriptionsMax): static { $this->nbInscriptionsMax = $nbInscriptionsMax; return $this; }

    public function getInfosSortie(): ?string { return $this->infosSortie; }
    public function setInfosSortie(?string $infosSortie): static { $this->infosSortie = $infosSortie; return $this; }

    public function getOrganisateur(): ?User { return $this->organisateur; }
    public function setOrganisateur(?User $organisateur): static { $this->organisateur = $organisateur; return $this; }

    public function getEtat(): ?Etat { return $this->etat; }
    public function setEtat(?Etat $etat): static { $this->etat = $etat; return $this; }

    public function getCampus(): ?Campus { return $this->campus; }
    public function setCampus(?Campus $campus): static { $this->campus = $campus; return $this; }

    /**
     * @return Collection<int, User>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(User $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
        }
        return $this;
    }

    public function removeInscription(User $inscription): static
    {
        $this->inscriptions->removeElement($inscription);
        return $this;
    }
}
