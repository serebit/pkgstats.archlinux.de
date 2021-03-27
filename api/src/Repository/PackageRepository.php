<?php

namespace App\Repository;

use App\Entity\Package;
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
}
