<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Data\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * Récupère les sorties en lien avec une recherche
     * @return Sortie[]
     */
    public function findSearch(SearchData $searchData): array
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('c', 's')
            ->join('s.campus', 'c');

        if (!empty($searchData->q)) {
            $query = $query
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        if (!empty($searchData->campus)) {
            $query = $query
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $searchData->campus);
        }

        return $query->getQuery()->getResult();
    }
}
