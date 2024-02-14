<?php

namespace App\Repository;

use App\Entity\DockerImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DockerImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DockerImage::class);
    }

    /**
     * @return DockerImage[]
     */
    public function findAll(): array
    {
        $qb = $this->createQueryBuilder('di');
        $qb->orderBy('di.id', 'ASC');
        $query = $qb->getQuery();

        return $query->getResult();
    }
}
