<?php

namespace App\Tests\Response;

use App\Response\PackagePopularity;
use App\Response\PackagePopularityList;
use PHPUnit\Framework\TestCase;

class PackagePopularityListTest extends TestCase
{
    public function testSettersAndGetters(): void
    {
        $packagePopularity = new PackagePopularity('pacman', 22, 13, 201901, 201902);
        $packagePopularityList = new PackagePopularityList([$packagePopularity], 34, 10, 0);

        $this->assertEquals(1, $packagePopularityList->getCount());
        $this->assertEquals(34, $packagePopularityList->getTotal());
        $this->assertCount(1, $packagePopularityList->getPackagePopularities());
        $this->assertEquals($packagePopularity, $packagePopularityList->getPackagePopularities()[0]);
    }
}
