<?php

namespace App\Tests\Response;

use App\Response\Popularity;
use PHPUnit\Framework\TestCase;

class PopularityTest extends TestCase
{
    public function testSettersAndGetters(): void
    {
        $popularity = new Popularity('pacman', 22, 13, 201901, 201902);

        $this->assertEquals('pacman', $popularity->getName());
        $this->assertEquals(22, $popularity->getSamples());
        $this->assertEquals(13, $popularity->getCount());
        $this->assertEquals(59.09, $popularity->getPopularity());
        $this->assertEquals(201901, $popularity->getStartMonth());
        $this->assertEquals(201902, $popularity->getEndMonth());
    }

    public function testEmptyPopularity(): void
    {
        $popularity = new Popularity('pacman', 0, 1, 201901, 201902);
        $this->assertEquals(0, $popularity->getPopularity());
    }

    public function testInvalidPopularity(): void
    {
        $popularity = new Popularity('pacman', 1, 2, 201901, 201902);
        $this->assertEquals(100, $popularity->getPopularity());
    }
}
