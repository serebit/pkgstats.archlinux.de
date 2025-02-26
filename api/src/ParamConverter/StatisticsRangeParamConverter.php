<?php

namespace App\ParamConverter;

use App\Entity\Month;
use App\Request\PkgstatsRequestException;
use App\Request\StatisticsRangeRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StatisticsRangeParamConverter implements ParamConverterInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $defaultMonth = Month::create()->getYearMonth();

        $statisticsRangeRequest = new StatisticsRangeRequest(
            $request->query->getInt('startMonth', $defaultMonth),
            $request->query->getInt('endMonth', $defaultMonth)
        );

        $errors = $this->validator->validate($statisticsRangeRequest);
        if ($errors->count() > 0) {
            throw new PkgstatsRequestException($errors);
        }

        $request->attributes->set(
            $configuration->getName(),
            $statisticsRangeRequest
        );

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() == StatisticsRangeRequest::class;
    }
}
