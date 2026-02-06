<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste des campus à créer
        $nomsCampus = ['Nantes', 'Rennes', 'Niort', 'Quimper', 'En ligne'];

        // On boucle sur la liste pour créer chaque campus
        foreach ($nomsCampus as $nom) {
            $campus = new Campus();
            $campus->setNom($nom);

            // On demande au manager de "préparer" l'objet
            $manager->persist($campus);
        }

        // On valide tout en base de données en une seule fois
        $manager->flush();
    }
}
