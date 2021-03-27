<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Persistence\ManagerRegistry;

class CountryRepository extends CountableRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }
}
