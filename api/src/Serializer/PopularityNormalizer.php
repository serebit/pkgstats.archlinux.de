<?php

namespace App\Serializer;

use App\Response\Popularity;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PopularityNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param Popularity $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'name' => $object->getName(),
            'samples' => $object->getSamples(),
            'count' => $object->getCount(),
            'popularity' => $object->getPopularity(),
            'startMonth' => $object->getStartMonth(),
            'endMonth' => $object->getEndMonth()
        ];
    }

    /**
     * @param object $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Popularity && $format == 'json';
    }

    /**
     * @return bool
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
