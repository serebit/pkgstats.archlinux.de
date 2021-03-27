<?php

namespace App\Controller;

use App\Request\PaginationRequest;
use App\Request\QueryRequest;
use App\Request\StatisticsRangeRequest;
use App\Response\Popularity;
use App\Response\PopularityList;
use App\Service\PopularityCalculator;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Cache(smaxage="first day of next month", maxage="+5 minutes")
 */
class ApiCountriesController extends AbstractController
{
    /** @var PopularityCalculator */
    private $popularityCalculator;

    /**
     * @param PopularityCalculator $popularityCalculator
     */
    public function __construct(PopularityCalculator $popularityCalculator)
    {
        $this->popularityCalculator = $popularityCalculator;
    }

    /**
     * @Route(
     *     "/api/countries/{name}",
     *      methods={"GET"},
     *      requirements={"name"="^[a-zA-Z0-9]{2}$"},
     *      name="app_api_country"
     * )
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @return Response
     *
     * @OA\Tag(name="countries")
     * @OA\Response(
     *     description="Returns popularity of given country",
     *     response=200,
     *     @Model(type=Popularity::class)
     * )
     * @OA\Parameter(
     *     in="path",
     *     name="name",
     *     description="Name of the country",
     *     @OA\Schema(
     *         type="string"
     *     )
     * )
     * @OA\Parameter(
     *     name="startMonth",
     *     required=false,
     *     in="query",
     *     description="Specify start month in the form of 'Ym', e.g. 201901. Defaults to last month.",
     *     @OA\Schema(
     *         type="integer"
     *     )
     * )
     * @OA\Parameter(
     *     name="endMonth",
     *     required=false,
     *     in="query",
     *     description="Specify end month in the format of 'Ym', e.g. 201901. Defaults to last month.",
     *     @OA\Schema(
     *         type="integer"
     *     )
     * )
     */
    public function countryAction(string $name, StatisticsRangeRequest $statisticsRangeRequest): Response
    {
        return $this->json(
            $this->popularityCalculator->getPopularity($name, $statisticsRangeRequest)
        );
    }

    /**
     * @Route(
     *     "/api/countries/{name}/series",
     *      methods={"GET"},
     *      requirements={"name"="^[a-zA-Z0-9]{2}$"},
     *      name="app_api_countries_series"
     * )
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @return Response
     *
     * @OA\Tag(name="countries")
     * @OA\Response(
     *     description="Returns popularities of given country in a monthly series",
     *     response=200,
     *     @Model(type=PopularityList::class)
     * )
     * @OA\Parameter(
     *     in="path",
     *     name="name",
     *     description="Name of the country",
     *     @OA\Schema(
     *         type="string"
     *     )
     * )
     * @OA\Parameter(
     *     name="startMonth",
     *     required=false,
     *     in="query",
     *     description="Specify start month in the form of 'Ym', e.g. 201901. Defaults to last month.",
     *     @OA\Schema(
     *         type="integer"
     *     )
     * )
     * @OA\Parameter(
     *     name="endMonth",
     *     required=false,
     *     in="query",
     *     description="Specify end month in the format of 'Ym', e.g. 201901. Defaults to last month.",
     *     @OA\Schema(
     *         type="integer"
     *     )
     * )
     * @OA\Parameter(
     *     name="limit",
     *     required=false,
     *     in="query",
     *     description="Limit the result set",
     *     @OA\Schema(
     *         type="integer",
     *         default=100,
     *         minimum=1,
     *         maximum=10000
     *     )
     * )
     * @OA\Parameter(
     *     name="offset",
     *     required=false,
     *     in="query",
     *     description="Offset the result set",
     *     @OA\Schema(
     *         type="integer",
     *         default=0,
     *         minimum=0,
     *         maximum=100000
     *     )
     * )
     */
    public function countrySeriesAction(
        string $name,
        StatisticsRangeRequest $statisticsRangeRequest,
        PaginationRequest $paginationRequest
    ): Response {
        return $this->json(
            $this->popularityCalculator->getPopularitySeries(
                $name,
                $statisticsRangeRequest,
                $paginationRequest
            )
        );
    }

    /**
     * @Route(
     *     "/api/countries",
     *      methods={"GET"},
     *      name="app_api_countries"
     * )
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @param QueryRequest $queryRequest
     * @return Response
     *
     * @OA\Tag(name="countries")
     * @OA\Response(
     *     description="Returns list of countries popularities",
     *     response=200,
     *     @Model(type=PopularityList::class)
     * )
     * @OA\Parameter(
     *     name="startMonth",
     *     required=false,
     *     in="query",
     *     description="Specify start month in the format of 'Ym', e.g. 201901. Defaults to last month.",
     *     @OA\Schema(
     *         type="integer",
     *         format="Ym"
     *     )
     * )
     * @OA\Parameter(
     *     name="endMonth",
     *     required=false,
     *     in="query",
     *     description="Specify end month in the format of 'Ym', e.g. 201901. Defaults to last month.",
     *     @OA\Schema(
     *         type="integer"
     *     )
     * )
     * @OA\Parameter(
     *     name="limit",
     *     required=false,
     *     in="query",
     *     description="Limit the result set",
     *     @OA\Schema(
     *         type="integer",
     *         default=100,
     *         minimum=1,
     *         maximum=10000
     *     )
     * )
     * @OA\Parameter(
     *     name="offset",
     *     required=false,
     *     in="query",
     *     description="Offset the result set",
     *     @OA\Schema(
     *         type="integer",
     *         default=0,
     *         minimum=0,
     *         maximum=100000
     *     )
     * )
     * @OA\Parameter(
     *     name="query",
     *     required=false,
     *     in="query",
     *     description="Search by country name",
     *     @OA\Schema(
     *         type="string",
     *         maxLength=2
     *     )
     * )
     */
    public function countryJsonAction(
        StatisticsRangeRequest $statisticsRangeRequest,
        PaginationRequest $paginationRequest,
        QueryRequest $queryRequest
    ): Response {
        return $this->json(
            $this->popularityCalculator->findPopularity(
                $statisticsRangeRequest,
                $paginationRequest,
                $queryRequest
            )
        );
    }
}
