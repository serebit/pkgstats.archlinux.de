<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class PackageRepository extends CountableRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    /**
     * @param int $startMonth
     * @param int $endMonth
     * @return int
     */
    public function getCountByRange(int $startMonth, int $endMonth): int
    {
        $queryBuilder = $this->createQueryBuilder('countable');

        if ($startMonth == $endMonth) {
            $queryBuilder
                ->select('countable.count')
                ->where('countable.month = :month')
                ->orderBy('countable.count', 'DESC')
                ->setMaxResults(1)
                ->setParameter('month', $startMonth);
        } else {
            $queryBuilder
                ->select('SUM(countable.count) AS count')
                ->where('countable.month >= :startMonth')
                ->andWhere('countable.month <= :endMonth')
                ->groupBy('countable.name')
                ->orderBy('count', 'DESC')
                ->setMaxResults(1)
                ->setParameter('startMonth', $startMonth)
                ->setParameter('endMonth', $endMonth);
        }

        try {
            return $queryBuilder
                ->getQuery()
                ->enableResultCache(60 * 60 * 24 * 30)
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }
    }

    /**
     * @param int $startMonth
     * @param int $endMonth
     * @return array
     */
    public function getMonthlyCountByRange(int $startMonth, int $endMonth): array
    {
        return $this->createQueryBuilder('countable')
            ->select('MAX(countable.count) AS count')
            ->addSelect('countable.month')
            ->where('countable.month >= :startMonth')
            ->andWhere('countable.month <= :endMonth')
            ->groupBy('countable.month')
            ->orderBy('countable.month', 'asc')
            ->setParameter('startMonth', $startMonth)
            ->setParameter('endMonth', $endMonth)
            ->getQuery()
            ->enableResultCache(60 * 60 * 24 * 30)
            ->getScalarResult();
    }
}
