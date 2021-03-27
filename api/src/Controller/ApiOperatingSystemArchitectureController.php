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
class ApiOperatingSystemArchitectureController extends AbstractController
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
     *     "/api/os/architecture/{name}",
     *      methods={"GET"},
     *      requirements={"name"="^[\w-]{1,10}$"},
     *      name="app_api_os_architecture"
     * )
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @return Response
     *
     * @OA\Tag(name="operating system architecture")
     * @OA\Response(
     *     description="Returns popularity of given system arhitecture",
     *     response=200,
     *     @Model(type=Popularity::class)
     * )
     * @OA\Parameter(
     *     in="path",
     *     name="name",
     *     description="Name of the operating system architecture",
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
    public function operatingSystemArchitectureAction(
        string $name,
        StatisticsRangeRequest $statisticsRangeRequest
    ): Response {
        return $this->json(
            $this->popularityCalculator->getPopularity($name, $statisticsRangeRequest)
        );
    }

    /**
     * @Route(
     *     "/api/os/architecture/{name}/series",
     *      methods={"GET"},
     *      requirements={"name"="^[\w-]{1,10}$"},
     *      name="app_api_os_architecture_series"
     * )
     * @param string $name
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @return Response
     *
     * @OA\Tag(name="operating system architecture")
     * @OA\Response(
     *     description="Returns popularities of given operating system architecture in a monthly series",
     *     response=200,
     *     @Model(type=PopularityList::class)
     * )
     * @OA\Parameter(
     *     in="path",
     *     name="name",
     *     description="Name of the operating system architecture",
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
    public function operatingSystemArchitectureSeriesAction(
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
     *     "/api/os/architecture",
     *      methods={"GET"},
     *      name="app_api_os_architectures"
     * )
     * @param StatisticsRangeRequest $statisticsRangeRequest
     * @param PaginationRequest $paginationRequest
     * @param QueryRequest $queryRequest
     * @return Response
     *
     * @OA\Tag(name="operating system architecture")
     * @OA\Response(
     *     description="Returns list of operating system architecture popularities",
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
     *     description="Search by operating system architecture name",
     *     @OA\Schema(
     *         type="string",
     *         maxLength=10
     *     )
     * )
     */
    public function operatingSystemArchitectureJsonAction(
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
