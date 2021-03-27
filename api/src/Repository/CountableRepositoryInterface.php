<?php

namespace App\Repository;

interface CountableRepositoryInterface
{
    public function getCountByNameAndRange(string $name, int $startMonth, int $endMonth): int;

    public function findMonthlyByNameAndRange(
        string $name,
        int $startMonth,
        int $endMonth,
        int $offset,
        int $limit
    ): array;

    public function getMaximumCountByRange(int $startMonth, int $endMonth): int;

    public function findByRange(
        string $query,
        int $startMonth,
        int $endMonth,
        int $offset,
        int $limit
    ): array;

    public function getMonthlyMaximumCountByRange(int $startMonth, int $endMonth): array;
}
