<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function findLikeName(string $name)
    {
        $queryBuider = $this->createQueryBuilder('p')
            ->where('p.title LIKE :name')
            ->join('p.actors', 'a')
            ->orWhere('a.lastname LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.title', 'ASC')
            ->getQuery();
        return $queryBuider->getResult();
    }
}
