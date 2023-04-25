<?php

namespace App\Initiative\UI\Web\Initiative\Normalizer;

use App\Initiative\Domain\Initiative\Location;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class LocationNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param Location $location
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($location, $format = null, array $context = []): array
    {
        return parent::normalize([
            'location' => (string) $location,
            'name' => $location->name(),
            'radius_value' => $location->radiusValue(),
            'radius_unit' => $location->radiusUnit(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Location;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
