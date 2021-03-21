<?php

namespace App\Serializer;

use App\Response\PackagePopularityList;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PackagePopularityListNormalizer implements
    NormalizerInterface,
    NormalizerAwareInterface,
    CacheableSupportsMethodInterface
{
    /** @var NormalizerInterface */
    private $normalizer;

    /**
     * @param PackagePopularityList $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'total' => $object->getTotal(),
            'count' => $object->getCount(),
            'limit' => $object->getLimit(),
            'offset' => $object->getOffset(),
            'packagePopularities' => $this->normalizer->normalize($object->getPackagePopularities(), $format, $context)
        ];
    }

    /**
     * @param object $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof PackagePopularityList && $format == 'json';
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
