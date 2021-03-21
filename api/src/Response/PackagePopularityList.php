<?php

namespace App\Response;

class PackagePopularityList
{
    /**
     * @var PackagePopularity[]
     */
    private $packagePopularities;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @param PackagePopularity[] $packagePopularities
     * @param int $total
     * @param int $limit
     * @param int $offset
     */
    public function __construct(array $packagePopularities, int $total, int $limit, int $offset)
    {
        $this->packagePopularities = $packagePopularities;
        $this->total = $total;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->getPackagePopularities());
    }

    /**
     * @return PackagePopularity[]
     */
    public function getPackagePopularities(): array
    {
        return $this->packagePopularities;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
