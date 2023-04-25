<?php

namespace App\Identity\UI\Dashboard\Permission\Normalizer;

use App\Identity\Domain\Permission\Permission;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PermissionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param Permission $permission
     * @param null             $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($permission, $format = null, array $context = []): ?array
    {
        return parent::normalize([
            'id' => (string) $permission->id(),
            'name' => $permission->permission(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Permission;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}