<?php

namespace App\Tests\Response;

use App\Response\Popularity;
use App\Response\PopularityList;
use PHPUnit\Framework\TestCase;

class PopularityListTest extends TestCase
{
    public function testSettersAndGetters(): void
    {
        $popularity = new Popularity('pacman', 22, 13, 201901, 201902);
        $popularityList = new PopularityList([$popularity], 34, 10, 0);

        $this->assertEquals(1, $popularityList->getCount());
        $this->assertEquals(34, $popularityList->getTotal());
        $this->assertCount(1, $popularityList->getPopularities());
        $this->assertEquals($popularity, $popularityList->getPopularities()[0]);
    }
}
