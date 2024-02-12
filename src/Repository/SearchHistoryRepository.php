<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SearchHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchHistory>
 */
class SearchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchHistory::class);
    }

    // Przykładowa metoda do wyszukiwania historii po nazwie obrazu
    public function findByImageName(string $imageName): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.imageName = :val')
            ->setParameter('val', $imageName)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Dodaj więcej metod zgodnie z wymaganiami aplikacji
}
