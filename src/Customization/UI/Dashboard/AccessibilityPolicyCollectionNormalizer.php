<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\AccessibilityPolicy;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AccessibilityPolicyCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private AccessibilityPolicyNormalizer $normalizer;

    public function __construct(AccessibilityPolicyNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param AccessibilityPolicyCollectionPayload $policies
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($policies, $format = null, array $context = []): array
    {
        $response = [];

        /** @var AccessibilityPolicy $policy */
        foreach ($policies->get()->toArray() as $policy) {
            $response[(string) $policy->language()] = $this->normalizer
                ->setWrapped(false)
                ->normalize($policy, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof AccessibilityPolicyCollectionPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
