<?php

namespace App\Identity\UI\Dashboard\User\Normalizer;

use App\Identity\UI\Dashboard\Security\Http\Controller\UserPayload;
use App\Shared\Domain\Language;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UserPayloadNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private UserNormalizer $normalizer;

    public function __construct(UserNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param UserPayload $payload
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($payload, $format = null, array $context = []): array
    {
        $user = $payload->user();
        $languages = $payload->languages();
        return parent::normalize(array_merge(
            $this->normalizer->setWrapped(false)->normalize($user, $format, $context),
            [
                'languages' => array_map(fn(Language $language) => (string) $language, $languages),
            ]
        ), $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof UserPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
