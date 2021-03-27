<?php

namespace App\Service;

use App\Repository\CountableRepositoryInterface;
use App\Request\QueryRequest;
use App\Request\PaginationRequest;
use App\Request\StatisticsRangeRequest;
use App\Response\Popularity;
use App\Response\PopularityList;

class PopularityCalculator
{
    /** @var CountableRepositoryInterface */
    private $countableRepository;

    /**
     * @param CountableRepositoryInterface $countableRepository
     */
    public function __construct(CountableRepositoryInterface $countableRepository)
    {
        $this->countableRepository = $countableRepository;
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
        $count = $this->countableRepository->getCountByNameAndRange(
            $name,
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth()
        );

        return new Popularity(
            $name,
            $rangeCount,
            $count,
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
        return $this->countableRepository->getMaximumCountByRange(
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
    public function findPopularity(
        StatisticsRangeRequest $statisticsRangeRequest,
        PaginationRequest $paginationRequest,
        QueryRequest $queryRequest
    ): PopularityList {
        $rangeCount = $this->getRangeCount($statisticsRangeRequest);
        $countables = $this->countableRepository->findByRange(
            $queryRequest->getQuery(),
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth(),
            $paginationRequest->getOffset(),
            $paginationRequest->getLimit()
        );

        $popularities = iterator_to_array(
            (function () use ($countables, $rangeCount, $statisticsRangeRequest) {
                foreach ($countables['countables'] as $countable) {
                    $popularity = new Popularity(
                        $countable['name'],
                        $rangeCount,
                        $countable['count'],
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
            $countables['total'],
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
        $countables = $this->countableRepository->findMonthlyByNameAndRange(
            $name,
            $statisticsRangeRequest->getStartMonth(),
            $statisticsRangeRequest->getEndMonth(),
            $paginationRequest->getOffset(),
            $paginationRequest->getLimit()
        );

        $popularities = iterator_to_array(
            (function () use (
                $countables,
                $rangeCountSeries
            ) {
                foreach ($countables['countables'] as $countable) {
                    $popularity = new Popularity(
                        $countable['name'],
                        $rangeCountSeries[$countable['month']],
                        $countable['count'],
                        $countable['month'],
                        $countable['month']
                    );
                    if ($popularity->getPopularity() > 0) {
                        yield $popularity;
                    }
                }
            })()
        );

        return new PopularityList(
            $popularities,
            $countables['total'],
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
        $monthlyCount = $this->countableRepository->getMonthlyMaximumCountByRange(
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
