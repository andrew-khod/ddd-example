<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\CookiesPolicy;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CookiesPolicyCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private CookiesPolicyNormalizer $normalizer;

    public function __construct(CookiesPolicyNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param CookiesPolicyCollectionPayload $policies
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($policies, $format = null, array $context = []): array
    {
        $response = [];

        /** @var CookiesPolicy $policy */
        foreach ($policies->get()->toArray() as $policy) {
            $response[(string) $policy->language()] = $this->normalizer
                ->setWrapped(false)
                ->normalize($policy, $format, $context);
        }

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CookiesPolicyCollectionPayload;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
