<?php

namespace App\Serializer;

use App\Entity\Country;
use App\Entity\Mirror;
use App\Entity\OperatingSystemArchitecture;
use App\Entity\Package;
use App\Entity\SystemArchitecture;
use App\Request\PkgstatsRequest;
use App\Service\GeoIp;
use App\Service\MirrorUrlFilter;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PkgstatsRequestV2Denormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    /** @var GeoIp */
    private $geoIp;

    /** @var MirrorUrlFilter */
    private $mirrorUrlFilter;

    /**
     * @param GeoIp $geoIp
     * @param MirrorUrlFilter $mirrorUrlFilter
     */
    public function __construct(GeoIp $geoIp, MirrorUrlFilter $mirrorUrlFilter)
    {
        $this->geoIp = $geoIp;
        $this->mirrorUrlFilter = $mirrorUrlFilter;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $packages = $this->filterList((string)($data['packages'] ?? ''));
        $arch = (string)($data['arch'] ?? '');
        $cpuArch = $data['cpuarch'] ?? $arch;
        $mirror = $this->mirrorUrlFilter->filter((string)($data['mirror'] ?? ''));

        $clientIp = $context['clientIp'] ?? '127.0.0.1';

        $pkgstatsver = str_replace('pkgstats/', '', $context['userAgent'] ?? '');
        $pkgstatsRequest = new PkgstatsRequest($pkgstatsver);
        $pkgstatsRequest->setOperatingSystemArchitecture(new OperatingSystemArchitecture($arch));
        $pkgstatsRequest->setSystemArchitecture(new SystemArchitecture($cpuArch));

        if ($mirror) {
            $pkgstatsRequest->setMirror(new Mirror($mirror));
        }

        $countryCode = $this->geoIp->getCountryCode($clientIp);
        if ($countryCode) {
            $pkgstatsRequest->setCountry(new Country($countryCode));
        }

        foreach ($packages as $package) {
            $pkgstatsRequest->addPackage(new Package($package));
        }

        return $pkgstatsRequest;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $type === PkgstatsRequest::class && $format === 'form';
    }

    /**
     * @param string $string
     * @return string[]
     */
    private function filterList(string $string): array
    {
        return array_filter(array_unique(explode("\n", trim($string))));
    }
}
