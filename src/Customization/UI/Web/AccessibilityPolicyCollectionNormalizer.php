<?php

namespace App\Customization\UI\Web;

use App\Customization\Domain\AccessibilityPolicy;
use App\Customization\Domain\AccessibilityPolicyCollection;
use App\Customization\UI\Dashboard\AccessibilityPolicyNormalizer;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AccessibilityPolicyCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private AccessibilityPolicyNormalizer $normalizer;
    private ActiveLanguage $activeLanguage;

    public function __construct(AccessibilityPolicyNormalizer $normalizer, ActiveLanguage $activeLanguage)
    {
        $this->normalizer = $normalizer;
        $this->activeLanguage = $activeLanguage;
    }

    /**
     * @param AccessibilityPolicyCollection $rules
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($rules, $format = null, array $context = []): array
    {
        $rules = array_values(array_filter(
            $rules->toArray(),
            fn(AccessibilityPolicy $rule) => (string) $rule->language() === $this->activeLanguage->language()
        ));

        return parent::normalize(count($rules) ? $this->normalizer
            ->setWrapped(false)
            ->normalize($rules[0], $format, $context) : [], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof AccessibilityPolicyCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
