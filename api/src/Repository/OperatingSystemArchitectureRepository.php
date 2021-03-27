<?php

namespace App\Repository;

use App\Entity\OperatingSystemArchitecture;
use Doctrine\Persistence\ManagerRegistry;

class OperatingSystemArchitectureRepository extends CountableRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperatingSystemArchitecture::class);
    }
}
