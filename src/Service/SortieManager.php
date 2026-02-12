<?php

namespace App\Service;

use App\Repository\SortieRepository;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;

class SortieManager
{
    public function __construct(
        private SortieRepository $sortieRepo,
        private EtatRepository $etatRepo,
        private EntityManagerInterface $em
    ) {}

    public function updateEtats(): void
    {
        $sorties = $this->sortieRepo->findAll();
        $now = new \DateTime();

        $cloturee = $this->etatRepo->findOneBy(['libelle' => 'Clôturée']);
        $passee = $this->etatRepo->findOneBy(['libelle' => 'Passée']);
        $historisee = $this->etatRepo->findOneBy(['libelle' => 'Historisée']);

        foreach ($sorties as $sortie) {
            // Tâche 13 : Clôture si date limite d'inscription dépassée
            if ($sortie->getEtat()?->getLibelle() === 'Ouverte' && $sortie->getDateLimiteInscription() < $now) {
                if ($cloturee) $sortie->setEtat($cloturee);
            }

            // Passage en "Passée" si la date de début est derrière nous
            if ($sortie->getDateHeureDebut() < $now && $sortie->getEtat()?->getLibelle() !== 'Passée') {
                if ($passee) $sortie->setEtat($passee);
            }

            // Tâche 14 : Historisation (Archive) après 30 jours
            $dateArchive = (clone $sortie->getDateHeureDebut())->modify('+30 days');
            if ($dateArchive < $now) {
                if ($historisee) $sortie->setEtat($historisee);
            }
        }
        $this->em->flush();
    }
}
