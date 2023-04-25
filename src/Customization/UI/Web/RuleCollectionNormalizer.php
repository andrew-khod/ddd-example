<?php

namespace App\Customization\UI\Web;

use App\Customization\Domain\Rule;
use App\Customization\Domain\RuleCollection;
use App\Customization\UI\Dashboard\RuleNormalizer;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RuleCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private RuleNormalizer $normalizer;
    private ActiveLanguage $activeLanguage;

    public function __construct(RuleNormalizer $normalizer, ActiveLanguage $activeLanguage)
    {
        $this->normalizer = $normalizer;
        $this->activeLanguage = $activeLanguage;
    }

    /**
     * @param RuleCollection $rules
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($rules, $format = null, array $context = []): array
    {
        $rules = array_values(array_filter(
            $rules->toArray(),
            fn(Rule $rule) => (string) $rule->language() === $this->activeLanguage->language()
        ));

        return parent::normalize(count($rules) ? $this->normalizer
            ->setWrapped(false)
            ->normalize($rules[0], $format, $context) : [], $format, $context);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof RuleCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
