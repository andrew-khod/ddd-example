<?php

namespace App\Identity\UI\Dashboard\User\Normalizer;

use App\Identity\Domain\User\UserCollection;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UserCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private UserNormalizer $userNormalizer;

    public function __construct(UserNormalizer $userNormalizer)
    {
        $this->userNormalizer = $userNormalizer;
    }

    /**
     * @param UserCollection $users
     * @param null            $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($users, $format = null, array $context = []): array
    {
        $response = [];

        foreach ($users->toArray() as $user) {
            $response[] = $this->userNormalizer
                ->setWrapped(false)
                ->normalize($user, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof UserCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}