<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DockerTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DockerTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method DockerTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method DockerTag[]    findAll()
 * @method DockerTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DockerTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DockerTag::class);
    }

    // Custom methods
}
