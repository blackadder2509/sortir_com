<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
<<<<<<< HEAD
=======
#[ORM\Table(name: 'campus')]
>>>>>>> 36a4293e1a9bb7a7545a4bbefbe00ef463bcbc97
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
<<<<<<< HEAD
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
=======
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;
>>>>>>> 36a4293e1a9bb7a7545a4bbefbe00ef463bcbc97

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

<<<<<<< HEAD
    public function setNom(string $nom): static
=======
    public function setNom(string $nom): self
>>>>>>> 36a4293e1a9bb7a7545a4bbefbe00ef463bcbc97
    {
        $this->nom = $nom;

        return $this;
    }
<<<<<<< HEAD
=======

    // Cette fonction est indispensable pour que la liste déroulante affiche le nom
    public function __toString()
    {
        return $this->nom;
    }
>>>>>>> 36a4293e1a9bb7a7545a4bbefbe00ef463bcbc97
}
