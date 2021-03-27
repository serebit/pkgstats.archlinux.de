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

    public function getCountByRange(int $startMonth, int $endMonth): int;

    public function findByRange(
        string $query,
        int $startMonth,
        int $endMonth,
        int $offset,
        int $limit
    ): array;

    public function getMonthlyCountByRange(int $startMonth, int $endMonth): array;
}
