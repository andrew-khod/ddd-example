<?php

namespace App\Customization\UI\Web;

use App\Customization\Domain\PrivacyPolicy;
use App\Customization\Domain\PrivacyPolicyCollection;
use App\Customization\UI\Dashboard\PrivacyPolicyNormalizer;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\UI\Normalizer\ApiResponseNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PrivacyPolicyCollectionNormalizer extends ApiResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private PrivacyPolicyNormalizer $normalizer;
    private ActiveLanguage $activeLanguage;

    public function __construct(PrivacyPolicyNormalizer $normalizer, ActiveLanguage $activeLanguage)
    {
        $this->normalizer = $normalizer;
        $this->activeLanguage = $activeLanguage;
    }

    /**
     * @param PrivacyPolicyCollection $collection
     * @param null $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($collection, $format = null, array $context = []): array
    {
        $collection = array_values(array_filter(
            $collection->toArray(),
            fn(PrivacyPolicy $policy) => (string) $policy->language() === $this->activeLanguage->language()
        ));

        return parent::normalize(['url' => count($collection) ? $collection[0]->url(): null]);
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof PrivacyPolicyCollection;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
