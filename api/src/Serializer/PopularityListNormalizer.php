<?php

namespace App\Serializer;

use App\Response\PopularityList;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PopularityListNormalizer implements
    NormalizerInterface,
    NormalizerAwareInterface,
    CacheableSupportsMethodInterface
{
    /** @var NormalizerInterface */
    private $normalizer;

    /**
     * @param PopularityList $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $popularities = $this->normalizer->normalize($object->getPopularities(), $format, $context);
        return [
            'total' => $object->getTotal(),
            'count' => $object->getCount(),
            'limit' => $object->getLimit(),
            'offset' => $object->getOffset(),
            'popularities' => $popularities,
            // @TODO: Find better way to deal with API break instead of duplication
            'packagePopularities' => $popularities
        ];
    }

    /**
     * @param object $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof PopularityList && $format == 'json';
    }

    /**
     * @param NormalizerInterface $normalizer
     */
    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @return bool
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
