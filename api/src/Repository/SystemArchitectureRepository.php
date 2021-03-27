<?php

namespace App\Repository;

use App\Entity\SystemArchitecture;
use Doctrine\Persistence\ManagerRegistry;

class SystemArchitectureRepository extends CountableRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SystemArchitecture::class);
    }
}
