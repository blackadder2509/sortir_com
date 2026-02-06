<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'mail invalide')]
    #[Assert\NotBlank]
    private ?string $email = null;


    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 50, minMessage: "Minimum 8 characters svp")]
    #[Assert\Regex(pattern: '/^[a-z0-9_-]+$/i', message: 'Utilisez lettres, chiffres, _ ou -')]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?bool $administrateur = false;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 60)]
    private ?string $prenom = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?bool $actif = null;

// GETTER ET SETTERS
    public function getUserIdentifier(): string
    {
        return (string) $this->email; // C'est l'email qui sert d'identifiant
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        if ($this->administrateur) {
            $roles[] = 'ROLE_ADMIN';
        }
        return array_unique($roles);
    }
    public function eraseCredentials(): void
    {
        // Obligatoire, peut rester vide
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }


    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isAdministrateur(): bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }
    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
}
