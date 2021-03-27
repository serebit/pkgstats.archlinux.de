<?php

namespace App\Controller;

use App\Request\QueryRequest;
use App\Request\PaginationRequest;
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
class ApiPackagesController extends AbstractController
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
     *     "/api/packages/{name}",
     *      methods={"GET"},
     *      requirements={"name"="^[^-/]{1}[^/\s]{0,190}$"},
     *      name="app_api_package"
     * )
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @return Response
     *
     * @OA\Tag(name="packages")
     * @OA\Response(
     *     description="Returns popularity of given package",
     *     response=200,
     *     @Model(type=Popularity::class)
     * )
     * @OA\Parameter(
     *     in="path",
     *     name="name",
     *     description="Name of the package",
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
    public function packageAction(string $name, StatisticsRangeRequest $statisticsRangeRequest): Response
    {
        return $this->json(
            $this->popularityCalculator->getPopularity($name, $statisticsRangeRequest)
        );
    }

    /**
     * @Route(
     *     "/api/packages/{name}/series",
     *      methods={"GET"},
     *      requirements={"name"="^[^-/]{1}[^/\s]{0,190}$"},
     *      name="app_api_package_series"
     * )
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @return Response
     *
     * @OA\Tag(name="packages")
     * @OA\Response(
     *     description="Returns popularities of given package in a monthly series",
     *     response=200,
     *     @Model(type=PopularityList::class)
     * )
     * @OA\Parameter(
     *     in="path",
     *     name="name",
     *     description="Name of the package",
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
    public function packageSeriesAction(
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
     *     "/api/packages",
     *      methods={"GET"},
     *      name="app_api_packages"
     * )
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @param QueryRequest $queryRequest
     * @return Response
     *
     * @OA\Tag(name="packages")
     * @OA\Response(
     *     description="Returns list of package popularities",
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
     *     description="Search by package name",
     *     @OA\Schema(
     *         type="string",
     *         maxLength=191
     *     )
     * )
     */
    public function packageJsonAction(
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
