<?php

namespace App\Response;

class PopularityList
{
    /**
     * @var Popularity[]
     */
    private $popularities;

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
     * @param Popularity[] $popularities
     * @param int $total
     * @param int $limit
     * @param int $offset
     */
    public function __construct(array $popularities, int $total, int $limit, int $offset)
    {
        $this->popularities = $popularities;
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
        return count($this->getPopularities());
    }

    /**
     * @return Popularity[]
     */
    public function getPopularities(): array
    {
        return $this->popularities;
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
