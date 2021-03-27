<?php

namespace App\Repository;

use App\Entity\Mirror;
use Doctrine\Persistence\ManagerRegistry;

class MirrorRepository extends CountableRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mirror::class);
    }
}
