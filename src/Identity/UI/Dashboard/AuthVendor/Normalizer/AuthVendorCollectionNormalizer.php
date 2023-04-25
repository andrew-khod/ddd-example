<?php

namespace App\Identity\UI\Dashboard\AuthVendor\Normalizer;

use App\Identity\Domain\AuthVendor\AuthVendorCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AuthVendorCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param AuthVendorCollection $vendors
     * @param null                 $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($vendors, $format = null, array $context = []): array
    {
        $response = [];

        foreach ($vendors->toArray() as $vendor) {
            $response[] = [
                'id' => $vendor->vendorUserId(),
                'type' => $vendor->vendorType(),
            ];
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof AuthVendorCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
