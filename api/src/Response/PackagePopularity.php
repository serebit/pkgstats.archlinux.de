<?php

namespace App\Response;

class PackagePopularity
{
    /** @var string */
    private $name;

    /** @var int */
    private $samples;

    /** @var int */
    private $count;

    /** @var int */
    private $startMonth;

    /** @var int */
    private $endMonth;

    /**
     * @param string $name
     * @param int $samples
     * @param int $count
     * @param int $startMonth
     * @param int $endMonth
     */
    public function __construct(string $name, int $samples, int $count, int $startMonth, int $endMonth)
    {
        $this->name = $name;
        $this->samples = $samples;
        $this->count = $count;
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getSamples(): int
    {
        return $this->samples;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return float
     */
    public function getPopularity(): float
    {
        if ($this->getSamples() < 1 || $this->getCount() < 0) {
            return 0;
        }
        if ($this->getCount() >= $this->getSamples()) {
            return 100;
        }
        return round($this->getCount() / $this->getSamples() * 100, 2);
    }

    /**
     * @return int
     */
    public function getStartMonth(): int
    {
        return $this->startMonth;
    }

    /**
     * @return int
     */
    public function getEndMonth(): int
    {
        return $this->endMonth;
    }
}
