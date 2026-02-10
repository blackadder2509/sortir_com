<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création du Campus (on le crée en premier pour l'utiliser partout)
        $campus = new Campus();
        $campus->setNom("Campus de Rennes");
        $manager->persist($campus);

        // 2. Création de l'organisateur avec son Campus
        $admin = new User();
        $admin->setEmail('admin@test.com');
        $admin->setUsername('admin');
        $admin->setNom('Dupont');
        $admin->setPrenom('Jean');
        $admin->setRoles(['ROLE_USER']);
        $admin->setCampus($campus); // FIX : On relie l'utilisateur au campus

        $password = $this->hasher->hashPassword($admin, '123456');
        $admin->setPassword($password);
        $manager->persist($admin);

        // 3. Création des États
        $nomsEtats = ['En création', 'Ouverte', 'Clôturée', 'Passée'];
        $objetsEtats = [];
        foreach ($nomsEtats as $libelle) {
            $etat = new Etat();
            $etat->setLibelle($libelle);
            $manager->persist($etat);
            $objetsEtats[$libelle] = $etat;
        }

        // 4. Création de 10 Sorties
        for ($i = 1; $i <= 10; $i++) {
            $sortie = new Sortie();
            $sortie->setNom("Sortie n°" . $i);
            $sortie->setDateHeureDebut(new \DateTime('now + ' . mt_rand(5, 15) . ' days'));
            $sortie->setDateLimiteInscription(new \DateTime('now + ' . mt_rand(1, 4) . ' days'));
            $sortie->setNbInscriptionsMax(mt_rand(5, 25));
            $sortie->setCampus($campus);
            $sortie->setOrganisateur($admin);

            $etatAleatoire = $objetsEtats[array_rand($objetsEtats)];
            $sortie->setEtat($etatAleatoire);

            $manager->persist($sortie);
        }

        $manager->flush();
    }
}
