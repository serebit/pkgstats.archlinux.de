<?php

namespace App\Service;

use App\Repository\PackageRepository;
use App\Request\QueryRequest;
use App\Request\PaginationRequest;
use App\Request\StatisticsRangeRequest;
use App\Response\Popularity;
use App\Response\PopularityList;

class PopularityCalculator
{
    /** @var PackageRepository */
    private $packageRepository;

    /**
     * @param PackageRepository $packageRepository
     */
    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    /**
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @return Popularity
     */
    public function getPopularity(
        string $name,
        StatisticsRangeRequest $statisticsRangeRequest
    ): Popularity {
        $rangeCount = $this->getRangeCount($statisticsRangeRequest);
        $packageCount = $this->packageRepository->getCountByNameAndRange(
            $name,
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth()
        );

        return new Popularity(
            $name,
            $rangeCount,
            $packageCount,
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth()
        );
    }

    /**
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @return int
     */
    private function getRangeCount(StatisticsRangeRequest $statisticsRangeRequest): int
    {
        return $this->packageRepository->getMaximumCountByRange(
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth()
        );
    }

    /**
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @param QueryRequest $queryRequest
     * @return PopularityList
     */
    public function findPackagesPopularity(
        StatisticsRangeRequest $statisticsRangeRequest,
        PaginationRequest $paginationRequest,
        QueryRequest $queryRequest
    ): PopularityList {
        $rangeCount = $this->getRangeCount($statisticsRangeRequest);
        $packages = $this->packageRepository->findPackagesCountByRange(
            $queryRequest->getQuery(),
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth(),
            $paginationRequest->getOffset(),
            $paginationRequest->getLimit()
        );

        $popularities = iterator_to_array(
            (function () use ($packages, $rangeCount, $statisticsRangeRequest) {
                foreach ($packages['packages'] as $package) {
                    $popularity = new Popularity(
                        $package['name'],
                        $rangeCount,
                        $package['count'],
                        $statisticsRangeRequest->getStartMonth(),
                        $statisticsRangeRequest->getEndMonth()
                    );
                    if ($popularity->getPopularity() > 0) {
                        yield $popularity;
                    }
                }
            })()
        );

        return new PopularityList(
            $popularities,
            $packages['total'],
            $paginationRequest->getLimit(),
            $paginationRequest->getOffset()
        );
    }

    /**
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @return PopularityList
     */
    public function getPopularitySeries(
        string $name,
        StatisticsRangeRequest $statisticsRangeRequest,
        PaginationRequest $paginationRequest
    ): PopularityList {
        $rangeCountSeries = $this->getRangeCountSeries($statisticsRangeRequest);
        $packages = $this->packageRepository->findMonthlyByNameAndRange(
            $name,
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth(),
            $paginationRequest->getOffset(),
            $paginationRequest->getLimit()
        );

        $popularities = iterator_to_array(
            (function () use (
                $packages,
                $rangeCountSeries
            ) {
                foreach ($packages['packages'] as $package) {
                    $popularity = new Popularity(
                        $package['name'],
                        $rangeCountSeries[$package['month']],
                        $package['count'],
                        $package['month'],
                        $package['month']
                    );
                    if ($popularity->getPopularity() > 0) {
                        yield $popularity;
                    }
                }
            })()
        );

        return new PopularityList(
            $popularities,
            $packages['total'],
            $paginationRequest->getLimit(),
            $paginationRequest->getOffset()
        );
    }

    /**
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @return array
     */
    private function getRangeCountSeries(StatisticsRangeRequest $statisticsRangeRequest): array
    {
        $monthlyCount = $this->packageRepository->getMonthlyMaximumCountByRange(
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth()
        );

        return iterator_to_array(
            (function () use ($monthlyCount) {
                foreach ($monthlyCount as $month) {
                    yield $month['month'] => $month['count'];
                }
            })()
        );
    }
}
