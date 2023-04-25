<?php

namespace App\Identity\UI\Dashboard\User\Normalizer;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\User\User;
use App\Identity\UI\Dashboard\Permission\Normalizer\PermissionCollectionNormalizer;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UserNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private PermissionCollectionNormalizer $permissionCollectionNormalizer;

    public function __construct(PermissionCollectionNormalizer $permissionCollectionNormalizer)
    {
        $this->permissionCollectionNormalizer = $permissionCollectionNormalizer;
    }

    /**
     * @param User $user
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($user, $format = null, array $context = []): array
    {
        return parent::normalize([
            'id' => (string) $user->id(),
            'email' => (string) $user->email(),
            'username' => (string) $user->username(),
            'is_superadmin' => $user->isSuperAdmin(),
            'permissions' => $this->permissionCollectionNormalizer
                ->setWrapped(false)
                ->normalize($user->permissions(), $format, $context),
            // todo CompanyNormalizer
            'active_company' => [
                'id' => (string) $user->activeCompany()->id(),
                'name' => (string) $user->activeCompany()->name(),
            ],
            'companies' => array_map(fn(Company $company) => [
                'id' => (string) $company->id(),
                'name' => (string) $company->name(),
            ], $user->companies()),
            'created' => $user->created(),
        ], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
