<?php

namespace App\Tests\Serializer;

use App\Response\PackagePopularity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Serializer;

class PackagePopularityNormalizerTest extends KernelTestCase
{
    /** @var Serializer */
    private $serializer;

    public function setUp(): void
    {
        self::bootKernel();
        $this->serializer = self::$container->get('serializer');
    }

    public function testNormalize(): void
    {
        $packagePopularity = new PackagePopularity('pacman', 22, 13, 201901, 201902);

        $json = $this->serializer->serialize($packagePopularity, 'json');
        $this->assertJson($json);
        $jsonArray = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals(
            [
                'name' => 'pacman',
                'samples' => 22,
                'count' => 13,
                'popularity' => 59.09,
                'startMonth' => 201901,
                'endMonth' => 201902
            ],
            $jsonArray
        );
    }
}
