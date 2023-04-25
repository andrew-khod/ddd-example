<?php

namespace App\Identity\UI\Dashboard\Permission\Normalizer;

use App\Identity\Domain\Permission\PermissionConfiguration;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PermissionConfigurationNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param PermissionConfiguration $permissionConfiguration
     * @param null                    $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($permissionConfiguration, $format = null, array $context = []): array
    {
        $permissions = $roles = [];

        foreach ($permissionConfiguration->permissions()->toArray() as $permission) {
            $permissions[] = [
                'id' => (string) $permission->id(),
                'name' => $permission->permission(),
            ];
        }

        foreach ($permissionConfiguration->roles()->toArray() as $role) {
            $build = [
                'id' => (string) $role->id(),
                'name' => $role->role(),
            ];
            $build['permissions'] = [];

            foreach ($role->permissions()->toArray() as $permission) {
                $build['permissions'][] = [
                    'id' => (string) $permission->id(),
                ];
            }

            $roles[] = $build;
        }

        return parent::normalize([
            'permissions' => $permissions,
            'roles' => $roles,
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof PermissionConfiguration;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
