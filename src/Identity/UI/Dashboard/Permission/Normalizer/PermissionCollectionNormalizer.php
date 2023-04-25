<?php

namespace App\Identity\UI\Dashboard\Permission\Normalizer;

use App\Identity\Domain\Permission\PermissionCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PermissionCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private PermissionNormalizer $permissionNormalizer;

    public function __construct(PermissionNormalizer $permissionNormalizer)
    {
        $this->permissionNormalizer = $permissionNormalizer;
    }

    /**
     * @param PermissionCollection $permission
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($permissions, $format = null, array $context = []): ?array
    {
        $response = [];

        foreach ($permissions->toArray() as $permission) {
            $response[] = $this->permissionNormalizer
                ->setWrapped(false)
                ->normalize($permission, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof PermissionCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}