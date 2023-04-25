<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\PrivacyPolicy;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PrivacyPolicyCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private PrivacyPolicyNormalizer $normalizer;

    public function __construct(PrivacyPolicyNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param PrivacyPolicyCollectionPayload $policies
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($policies, $format = null, array $context = []): array
    {
        $response = [];

        /** @var PrivacyPolicy $policy */
        foreach ($policies->get()->toArray() as $policy) {
            $response[(string) $policy->language()] = $policy->url();
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof PrivacyPolicyCollectionPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
