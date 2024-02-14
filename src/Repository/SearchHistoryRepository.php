<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SearchHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SearchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchHistory::class);
    }

    public function findByImageName(string $imageName): array
    {
        return $this->createQueryBuilder('sh')
            ->select('sh.id', 'sh.imageName', 'sh.tagName', 'sh.searchedAt')
            ->where('sh.imageName = :imageName')
            ->setParameter('imageName', $imageName)
            ->orderBy('sh.searchedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByTagName(string $tagName): array
    {
        return $this->createQueryBuilder('sh')
            ->select('sh.id', 'sh.imageName', 'sh.tagName', 'sh.searchedAt')
            ->where('sh.tagName = :tagName')
            ->setParameter('tagName', $tagName)
            ->orderBy('sh.searchedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByImageNameAndTagName(string $imageName, string $tagName): array
    {
        return $this->createQueryBuilder('sh')
            ->select('sh.id', 'sh.imageName', 'sh.tagName', 'sh.searchedAt')
            ->where('sh.imageName = :imageName')
            ->andWhere('sh.tagName = :tagName')
            ->setParameter('imageName', $imageName)
            ->setParameter('tagName', $tagName)
            ->orderBy('sh.searchedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
