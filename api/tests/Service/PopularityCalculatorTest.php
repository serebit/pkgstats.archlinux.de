<?php

namespace App\Tests\Service;

use App\Repository\PackageRepository;
use App\Request\QueryRequest;
use App\Request\PaginationRequest;
use App\Request\StatisticsRangeRequest;
use App\Service\PopularityCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PopularityCalculatorTest extends TestCase
{
    /** @var PackageRepository|MockObject */
    private $packageRepository;

    /** @var PopularityCalculator */
    private $popularityCalculator;

    public function setUp(): void
    {
        $this->packageRepository = $this->createMock(PackageRepository::class);
        $this->popularityCalculator = new PopularityCalculator($this->packageRepository);
    }

    public function testGetPopularity(): void
    {
        $this
            ->packageRepository
            ->expects($this->once())
            ->method('getCountByNameAndRange')
            ->with('foo', 201801, 201812)
            ->willReturn(42);
        $this
            ->packageRepository
            ->expects($this->once())
            ->method('getCountByRange')
            ->with(201801, 201812)
            ->willReturn(43);

        $popularity = $this->popularityCalculator->getPopularity(
            'foo',
            new StatisticsRangeRequest(201801, 201812)
        );

        $this->assertEquals('foo', $popularity->getName());
        $this->assertEquals(42, $popularity->getCount());
        $this->assertEquals(43, $popularity->getSamples());
    }

    public function testFindPackagesPopularity(): void
    {
        $this
            ->packageRepository
            ->expects($this->once())
            ->method('findByRange')
            ->with('foo', 201801, 201812, 2, 12)
            ->willReturn([
                'countables' => [
                    [
                        'name' => 'foo',
                        'count' => 43
                    ]
                ],
                'total' => 13
            ]);
        $this
            ->packageRepository
            ->expects($this->once())
            ->method('getCountByRange')
            ->with(201801, 201812)
            ->willReturn(44);

        $popularityList = $this->popularityCalculator->findPopularity(
            new StatisticsRangeRequest(201801, 201812),
            new PaginationRequest(2, 12),
            new QueryRequest('foo')
        );

        $this->assertEquals(1, $popularityList->getCount());
    }

    public function testGetPopularitySeries(): void
    {
        $this
            ->packageRepository
            ->expects($this->once())
            ->method('getMonthlyCountByRange')
            ->with(201801, 201812)
            ->willReturn([
                [
                    'month' => 201801,
                    'count' => 100
                ]
            ]);

        $this
            ->packageRepository
            ->expects($this->once())
            ->method('findMonthlyByNameAndRange')
            ->with('foo', 201801, 201812, 2, 12)
            ->willReturn([
                'countables' => [
                    [
                        'name' => 'foo',
                        'count' => 43,
                        'month' => 201801
                    ]
                ],
                'total' => 13
            ]);

        $popularitySeries = $this->popularityCalculator->getPopularitySeries(
            'foo',
            new StatisticsRangeRequest(201801, 201812),
            new PaginationRequest(2, 12)
        );

        $this->assertEquals(1, $popularitySeries->getCount());
    }
}
