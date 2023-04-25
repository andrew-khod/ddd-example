<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\AccessibilityPolicy;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use HTMLPurifier;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AccessibilityPolicyNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ActiveLanguage $activeLanguage;
    private HTMLPurifier $purifier;

    public function __construct(ActiveLanguage $activeLanguage, HTMLPurifier $purifier)
    {
        $this->activeLanguage = $activeLanguage;
        $this->purifier = $purifier;
    }

    /**
     * @param AccessibilityPolicy $contact
     * @param null     $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($policy, $format = null, array $context = []): array
    {
        $response = [
            'title' => $policy->title(),
            'description' => $this->purifier->purify($policy->description()),
        ];

        return parent::normalize($response, $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof AccessibilityPolicy;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
