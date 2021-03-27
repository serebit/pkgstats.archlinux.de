<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class CountableRepository extends ServiceEntityRepository implements CountableRepositoryInterface
{
    /**
     * @param string $name
     * @param int $startMonth
     * @param int $endMonth
     * @return int
     */
    public function getCountByNameAndRange(string $name, int $startMonth, int $endMonth): int
    {
        $queryBuilder = $this->createQueryBuilder('countable')
            ->where('countable.name = :name')
            ->setParameter('name', $name);

        if ($startMonth == $endMonth) {
            $queryBuilder
                ->select('countable.count')
                ->andWhere('countable.month = :month')
                ->setParameter('month', $startMonth);
        } else {
            $queryBuilder
                ->select('SUM(countable.count)')
                ->andWhere('countable.month >= :startMonth')
                ->andWhere('countable.month <= :endMonth')
                ->groupBy('countable.name')
                ->setParameter('startMonth', $startMonth)
                ->setParameter('endMonth', $endMonth);
        }

        try {
            return $queryBuilder
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }
    }

    /**
     * @param string $name
     * @param int $startMonth
     * @param int $endMonth
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function findMonthlyByNameAndRange(
        string $name,
        int $startMonth,
        int $endMonth,
        int $offset,
        int $limit
    ): array {
        $queryBuilder = $this->createQueryBuilder('countable')
            ->where('countable.name = :name')
            ->andWhere('countable.month >= :startMonth')
            ->andWhere('countable.month <= :endMonth')
            ->orderBy('countable.month', 'asc')
            ->setParameter('name', $name)
            ->setParameter('startMonth', $startMonth)
            ->setParameter('endMonth', $endMonth)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $pagination = new Paginator($queryBuilder, false);
        return [
            'total' => $pagination->count(),
            'countables' => $pagination->getQuery()->getArrayResult()
        ];
    }

    /**
     * @param int $startMonth
     * @param int $endMonth
     * @return int
     */
    public function getCountByRange(int $startMonth, int $endMonth): int
    {
        $queryBuilder = $this->createQueryBuilder('countable');
        $queryBuilder
            ->select('SUM(countable.count) AS count')
            ->where('countable.month >= :startMonth')
            ->andWhere('countable.month <= :endMonth')
            ->setParameter('startMonth', $startMonth)
            ->setParameter('endMonth', $endMonth);

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
     * @param string $query
     * @param int $startMonth
     * @param int $endMonth
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function findByRange(
        string $query,
        int $startMonth,
        int $endMonth,
        int $offset,
        int $limit
    ): array {
        $queryBuilder = $this->createQueryBuilder('countable')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        if ($startMonth == $endMonth) {
            $queryBuilder
                ->where('countable.month = :month')
                ->orderBy('countable.count', 'desc')
                ->setParameter('month', $startMonth);
        } else {
            $queryBuilder
                ->select('countable.name AS countable_name')
                ->addSelect('SUM(countable.count) AS countable_count')
                ->where('countable.month >= :startMonth')
                ->andWhere('countable.month <= :endMonth')
                ->groupBy('countable.name')
                ->orderBy('countable_count', 'desc')
                ->setParameter('startMonth', $startMonth)
                ->setParameter('endMonth', $endMonth);
        }
        if (!empty($query)) {//@TODO: testen, ob das greift
            $queryBuilder
                ->andWhere('countable.name LIKE :query')
                ->setParameter('query', $query . '%');
        }

        $pagination = new Paginator($queryBuilder, false);
        $total = $pagination->count();
        $countables = $pagination->getQuery()->getScalarResult();

        $countables = array_map(
            function ($countable) {
                return [
                    'name' => $countable['countable_name'],
                    'count' => $countable['countable_count']
                ];
            },
            $countables
        );

        return [
            'total' => $total,
            'countables' => $countables
        ];
    }

    /**
     * @param int $startMonth
     * @param int $endMonth
     * @return array
     */
    public function getMonthlyCountByRange(int $startMonth, int $endMonth): array
    {
        return $this->createQueryBuilder('countable')
            ->select('SUM(countable.count) AS count')
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
