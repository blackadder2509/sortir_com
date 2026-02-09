<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    // Relation "Organisateur" (OneToMany)
    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class)]
    private Collection $sortiesOrganisees;

    // Relation "Participant" (ManyToMany inverse)
    #[ORM\ManyToMany(targetEntity: Sortie::class, mappedBy: 'inscriptions')]
    private Collection $sorties;

    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getUserIdentifier(): string { return (string) $this->email; }
    public function getRoles(): array { $roles = $this->roles; $roles[] = 'ROLE_USER'; return array_unique($roles); }
    public function setRoles(array $roles): static { $this->roles = $roles; return $this; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }
    public function eraseCredentials(): void {}

    // --- Gestion des Sorties Organisées ---
    public function getSortiesOrganisees(): Collection { return $this->sortiesOrganisees; }
    public function addSortieOrganisee(Sortie $sortie): static {
        if (!$this->sortiesOrganisees->contains($sortie)) {
            $this->sortiesOrganisees->add($sortie);
            $sortie->setOrganisateur($this);
        }
        return $this;
    }
    public function removeSortieOrganisee(Sortie $sortie): static {
        if ($this->sortiesOrganisees->removeElement($sortie)) {
            if ($sortie->getOrganisateur() === $this) {
                $sortie->setOrganisateur(null);
            }
        }
        return $this;
    }

    // --- Gestion des Inscriptions (Participation) ---
    public function getSorties(): Collection { return $this->sorties; }
    public function addSortie(Sortie $sortie): static {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties->add($sortie);
            $sortie->addInscription($this);
        }
        return $this;
    }
    public function removeSortie(Sortie $sortie): static {
        if ($this->sorties->removeElement($sortie)) {
            $sortie->removeInscription($this);
        }
        return $this;
    }
}
