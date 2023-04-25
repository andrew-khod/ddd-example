<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\PrivacyPolicy;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PrivacyPolicyNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param PrivacyPolicy $contact
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($policy, $format = null, array $context = []): array
    {
        $response = [
            'url' => $policy->url(),
        ];

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof PrivacyPolicy;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
