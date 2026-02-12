<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Sortie::class);
    }

    public function findSearch(SearchData $search, $user): array
    {
        $qb = $this->createQueryBuilder('s')
            ->addSelect('e', 'c', 'o')
            ->join('s.etat', 'e')
            ->join('s.campus', 'c')
            ->join('s.organisateur', 'o');

        if (!empty($search->q)) {
            $qb->andWhere('s.nom LIKE :q')->setParameter('q', "%{$search->q}%");
        }

        if ($search->campus) {
            $qb->andWhere('c = :campus')->setParameter('campus', $search->campus);
        }

        if ($search->isOrganisateur) {
            $qb->andWhere('s.organisateur = :user')->setParameter('user', $user);
        }

        return $qb->getQuery()->getResult();
    }
}
